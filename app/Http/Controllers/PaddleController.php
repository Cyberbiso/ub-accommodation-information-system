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

        $response = Http::withToken(config('services.paddle.api_key'))
            ->acceptJson()
            ->post($this->apiBase() . '/transactions', [
                'collection_mode' => 'automatic',
                'items' => [[
                    'price' => [
                        'name'        => 'Accommodation Payment — ' . $payment->payable->booking_reference,
                        'description' => 'Off-campus accommodation payment via UB-UniStay',
                        'product_id'  => config('services.paddle.product_id'),
                        'unit_price'  => [
                            'amount'        => $amountInCents,
                            'currency_code' => 'USD',
                        ],
                        'tax_mode'    => 'account_setting',
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
        $base          = config('services.paddle.environment') === 'sandbox'
            ? 'https://sandbox-buy.paddle.com'
            : 'https://buy.paddle.com';
        $checkoutUrl   = $base . '/checkout/' . $transactionId;

        Log::info('Paddle transaction created', [
            'transaction_id' => $transactionId,
            'checkout_url'   => $checkoutUrl,
        ]);

        $details = $payment->payment_details ?? [];
        $details['paddle_transaction_id'] = $transactionId;
        $payment->update(['payment_details' => $details]);

        return response()->json([
            'transaction_id' => $transactionId,
            'checkout_url'   => $checkoutUrl,
        ]);
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
