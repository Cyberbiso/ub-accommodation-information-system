<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PropertyBooking;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaddleController extends Controller
{
    private function apiBase(): string
    {
        return config('services.paddle.environment') === 'sandbox'
            ? 'https://sandbox-api.paddle.com'
            : 'https://api.paddle.com';
    }

    public function debugConfig()
    {
        return response()->json([
            'environment'   => config('services.paddle.environment'),
            'api_base'      => $this->apiBase(),
            'api_key_prefix'       => substr(config('services.paddle.api_key') ?? '', 0, 20) . '...',
            'client_token_prefix'  => substr(config('services.paddle.client_side_token') ?? '', 0, 12) . '...',
            'price_id'      => config('services.paddle.price_id'),
            'product_id'    => config('services.paddle.product_id'),
        ]);
    }

    public function createCheckout(Request $request)
    {
        $request->validate(['payment_id' => 'required|integer']);

        $payment = Payment::where('id', $request->payment_id)
            ->where('student_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Paddle requires amounts in the smallest currency unit (cents for USD).
        // BWP is not supported by Paddle, so we use USD for sandbox simulation.
        $amountInCents = (string) (int) round($payment->amount * 100);

        $user = Auth::user();

        $customerId = $this->resolveCustomerId($user->email, $user->name ?? $user->email);

        if (!$customerId) {
            Log::error('Paddle customer resolution failed', ['payment_id' => $payment->id]);
            return response()->json(['error' => 'Could not initialise Paddle customer.'], 500);
        }

        // Step 1: Create transaction with inline price (starts as draft)
        $response = Http::withToken(config('services.paddle.api_key'))
            ->acceptJson()
            ->post($this->apiBase() . '/transactions', [
                'collection_mode' => 'automatic',
                'customer_id'     => $customerId,
                'items' => [[
                    'price' => [
                        'name'        => 'Accommodation Payment',
                        'description' => 'Booking ' . $payment->payable->booking_reference,
                        'product_id'  => config('services.paddle.product_id'),
                        'unit_price'  => [
                            'amount'        => $amountInCents,
                            'currency_code' => 'ZAR',
                        ],
                        'quantity'   => ['minimum' => 1, 'maximum' => 1],
                        'tax_mode'   => 'account_setting',
                    ],
                    'quantity' => 1,
                ]],
                'custom_data' => [
                    'payment_id' => (string) $payment->id,
                    'student_id' => (string) Auth::id(),
                ],
            ]);

        if (!$response->successful()) {
            Log::error('Paddle transaction creation failed', [
                'status'     => $response->status(),
                'body'       => $response->json(),
                'payment_id' => $payment->id,
            ]);
            return response()->json(['error' => 'Could not create Paddle checkout. Check your API key.'], 500);
        }

        $transactionId = $response->json('data.id');

        // Step 2: Move transaction from draft → ready so the JS overlay accepts it
        $readyResponse = Http::withToken(config('services.paddle.api_key'))
            ->acceptJson()
            ->patch($this->apiBase() . '/transactions/' . $transactionId, [
                'status' => 'ready',
            ]);

        $transactionData = $readyResponse->successful()
            ? $readyResponse->json('data')
            : $response->json('data');

        Log::info('Paddle transaction created', [
            'transaction_id' => $transactionId,
            'status'         => $transactionData['status'] ?? null,
            'customer_id'    => $transactionData['customer_id'] ?? null,
        ]);

        $checkoutBase = config('services.paddle.environment') === 'sandbox'
            ? 'https://sandbox-buy.paddle.com/checkout/'
            : 'https://buy.paddle.com/checkout/';
        $checkoutUrl = $checkoutBase . $transactionId;

        $details = $payment->payment_details ?? [];
        $details['paddle_transaction_id'] = $transactionId;
        $payment->update(['payment_details' => $details]);

        return response()->json([
            'transaction_id' => $transactionId,
            'checkout_url'   => $checkoutUrl,
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate(['transaction_id' => 'required|string']);

        $txnId = $request->transaction_id;

        // Fetch transaction from Paddle API to confirm it's completed
        $response = Http::withToken(config('services.paddle.api_key'))
            ->acceptJson()
            ->get($this->apiBase() . '/transactions/' . $txnId);

        if (!$response->successful()) {
            Log::error('Paddle verifyPayment: API fetch failed', ['txn_id' => $txnId]);
            return response()->json(['error' => 'Could not verify transaction.'], 500);
        }

        $data   = $response->json('data');
        $status = $data['status'] ?? null;

        if ($status !== 'completed') {
            Log::warning('Paddle verifyPayment: transaction not completed', ['txn_id' => $txnId, 'status' => $status]);
            return response()->json(['error' => 'Payment not completed yet.'], 422);
        }

        $customData = $data['custom_data'] ?? [];
        $paymentId  = $customData['payment_id'] ?? null;

        $payment = Payment::where('id', $paymentId)
            ->where('student_id', Auth::id())
            ->where('status', 'pending')
            ->with('payable')
            ->first();

        if (!$payment) {
            // Already processed or doesn't belong to this user — treat as success
            return response()->json(['success' => true]);
        }

        try {
            DB::transaction(function () use ($payment, $txnId) {
                $details                          = $payment->payment_details ?? [];
                $details['paddle_transaction_id'] = $txnId;

                $payment->update([
                    'payment_method'  => 'paddle',
                    'payment_details' => $details,
                ]);

                $payment->markAsCompleted($txnId);

                if ($payment->payable instanceof PropertyBooking) {
                    if (!$payment->payable->confirm()) {
                        throw new RuntimeException('property_unavailable');
                    }

                    SystemNotification::notifyUser(
                        $payment->payable->landlord_id,
                        'Booking payment received',
                        'Payment completed for booking ' . $payment->payable->booking_reference . '.',
                        route('landlord.bookings'),
                        'success',
                        $payment->student_id
                    );
                }
            });

            SystemNotification::notifyUser(
                $payment->student_id,
                'Payment successful',
                'Your payment of ' . $payment->formatted_amount . ' was processed successfully via Paddle.',
                route('student.payments'),
                'success',
                $payment->student_id
            );

            Log::info('Paddle verifyPayment: payment marked complete', ['payment_id' => $payment->id, 'txn_id' => $txnId]);
        } catch (RuntimeException $e) {
            Log::error('Paddle verifyPayment: processing failed', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment recorded but booking confirmation failed.'], 500);
        }

        return response()->json(['success' => true]);
    }

    public function webhook(Request $request)
    {
        $signature = $request->header('Paddle-Signature');

        if (!$this->verifySignature($request->getContent(), $signature)) {
            Log::warning('Paddle webhook signature verification failed');
            return response('Unauthorized', 401);
        }

        $event = $request->json()->all();

        if (($event['event_type'] ?? null) !== 'transaction.completed') {
            return response('OK', 200);
        }

        $data       = $event['data'] ?? [];
        $customData = $data['custom_data'] ?? [];
        $paymentId  = $customData['payment_id'] ?? null;

        if (!$paymentId) {
            return response('OK', 200);
        }

        $payment = Payment::where('id', $paymentId)
            ->where('status', 'pending')
            ->with('payable')
            ->first();

        if (!$payment) {
            return response('OK', 200);
        }

        $paddleTransactionId = $data['id'] ?? null;

        try {
            DB::transaction(function () use ($payment, $paddleTransactionId) {
                $details                          = $payment->payment_details ?? [];
                $details['paddle_transaction_id'] = $paddleTransactionId;

                $payment->update([
                    'payment_method'  => 'paddle',
                    'payment_details' => $details,
                ]);

                $payment->markAsCompleted($paddleTransactionId);

                if ($payment->payable instanceof PropertyBooking) {
                    if (!$payment->payable->confirm()) {
                        throw new RuntimeException('property_unavailable');
                    }

                    SystemNotification::notifyUser(
                        $payment->payable->landlord_id,
                        'Booking payment received',
                        'Paddle payment completed for booking ' . $payment->payable->booking_reference . '.',
                        route('landlord.bookings'),
                        'success',
                        $payment->student_id
                    );
                }
            });

            SystemNotification::notifyUser(
                $payment->student_id,
                'Payment successful',
                'Your payment of ' . $payment->formatted_amount . ' was processed successfully via Paddle.',
                route('student.payments'),
                'success',
                $payment->student_id
            );
        } catch (RuntimeException $e) {
            Log::error('Paddle webhook: payment processing failed', [
                'payment_id' => $payment->id,
                'error'      => $e->getMessage(),
            ]);
        }

        return response('OK', 200);
    }

    private function resolveCustomerId(string $email, string $name): ?string
    {
        // Search for existing Paddle customer by email
        $search = Http::withToken(config('services.paddle.api_key'))
            ->acceptJson()
            ->get($this->apiBase() . '/customers', ['search' => $email]);

        if ($search->successful()) {
            $customers = $search->json('data', []);
            foreach ($customers as $customer) {
                if (isset($customer['email']) && strtolower($customer['email']) === strtolower($email)) {
                    return $customer['id'];
                }
            }
        }

        // Create new customer
        $create = Http::withToken(config('services.paddle.api_key'))
            ->acceptJson()
            ->post($this->apiBase() . '/customers', [
                'email' => $email,
                'name'  => $name,
            ]);

        return $create->successful() ? $create->json('data.id') : null;
    }

    private function verifySignature(string $payload, ?string $signatureHeader): bool
    {
        $secret = config('services.paddle.webhook_secret');

        if (!$signatureHeader || !$secret) {
            return false;
        }

        // Paddle signature format: ts=TIMESTAMP;h1=HASH
        $parts = [];
        foreach (explode(';', $signatureHeader) as $part) {
            [$k, $v]   = explode('=', $part, 2);
            $parts[$k] = $v;
        }

        if (!isset($parts['ts'], $parts['h1'])) {
            return false;
        }

        $signed   = $parts['ts'] . ':' . $payload;
        $expected = hash_hmac('sha256', $signed, $secret);

        return hash_equals($expected, $parts['h1']);
    }
}
