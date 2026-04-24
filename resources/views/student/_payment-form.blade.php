@php
    $submitLabel = $submitLabel ?? 'Pay now';
    $formClass = $formClass ?? '';
    $paymentFormId = 'payment-form-' . $payment->id;
    $paddleBtnId   = 'paddle-btn-' . $payment->id;
    $isCurrentPayment = (string) old('payment_id') === (string) $payment->id;
    $storedCapture = $payment->payment_details['method_capture'] ?? [];
    $selectedMethod = $isCurrentPayment
        ? old('payment_method', 'card')
        : (($payment->payment_method && $payment->payment_method !== 'online') ? $payment->payment_method : 'card');
    $cardholderName = $isCurrentPayment ? old('cardholder_name', auth()->user()->name) : ($storedCapture['cardholder_name'] ?? auth()->user()->name);
    $bankAccountName = $isCurrentPayment ? old('account_name', auth()->user()->name) : ($storedCapture['account_name'] ?? auth()->user()->name);
@endphp

{{-- ===== PADDLE SANDBOX CHECKOUT ===== --}}
@if(config('services.paddle.client_side_token'))
<div class="space-y-2 mb-4">
    <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
        <span class="flex-1 border-t border-gray-200"></span>
        <span>Pay with Paddle (Sandbox)</span>
        <span class="flex-1 border-t border-gray-200"></span>
    </div>
    <button
        id="{{ $paddleBtnId }}"
        type="button"
        data-payment-id="{{ $payment->id }}"
        class="paddle-checkout-btn w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
        Pay {{ $payment->formatted_amount }} via Paddle
    </button>
    <p class="text-xs text-gray-400 text-center">Sandbox mode — use test card <strong>4242 4242 4242 4242</strong>, any future date, any CVV.</p>
</div>

<div class="flex items-center gap-2 mb-4">
    <span class="flex-1 border-t border-gray-200"></span>
    <span class="text-xs text-gray-400">or pay manually below</span>
    <span class="flex-1 border-t border-gray-200"></span>
</div>
@endif

<form id="{{ $paymentFormId }}" method="POST" action="{{ route('student.payments.process') }}" class="space-y-4 {{ $formClass }}">
    @csrf
    <input type="hidden" name="payment_id" value="{{ $payment->id }}">

    @if($isCurrentPayment && $errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            Please review the payment details and try again.
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Payment method</label>
        <select name="payment_method" class="payment-method-select w-full border border-gray-300 rounded-lg px-4 py-3" data-payment-method>
            <option value="card" {{ $selectedMethod === 'card' ? 'selected' : '' }}>Card</option>
            <option value="bank_transfer" {{ $selectedMethod === 'bank_transfer' ? 'selected' : '' }}>Bank transfer</option>
            <option value="mobile_money" {{ $selectedMethod === 'mobile_money' ? 'selected' : '' }}>Mobile money</option>
        </select>
    </div>

    <div data-payment-section="card" class="space-y-3 {{ $selectedMethod === 'card' ? '' : 'hidden' }}">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder name</label>
            <input type="text" name="cardholder_name" value="{{ $cardholderName }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" autocomplete="cc-name">
            @if($isCurrentPayment && $errors->has('cardholder_name'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('cardholder_name') }}</p>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Card number</label>
            <input type="text" name="card_number" value="{{ $isCurrentPayment ? old('card_number') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="4242 4242 4242 4242" autocomplete="cc-number" inputmode="numeric">
            @if($isCurrentPayment && $errors->has('card_number'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('card_number') }}</p>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry month</label>
                <input type="number" name="expiry_month" value="{{ $isCurrentPayment ? old('expiry_month') : '' }}" min="1" max="12" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="MM" autocomplete="cc-exp-month">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry year</label>
                <input type="number" name="expiry_year" value="{{ $isCurrentPayment ? old('expiry_year') : '' }}" min="{{ now()->format('Y') }}" max="{{ now()->addYears(20)->format('Y') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="YYYY" autocomplete="cc-exp-year">
            </div>
        </div>
        @if($isCurrentPayment && ($errors->has('expiry_month') || $errors->has('expiry_year')))
            <p class="text-sm text-red-600">{{ $errors->first('expiry_month') ?: $errors->first('expiry_year') }}</p>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
            <input type="password" name="cvv" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="123" autocomplete="cc-csc" inputmode="numeric">
            @if($isCurrentPayment && $errors->has('cvv'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('cvv') }}</p>
            @endif
        </div>
    </div>

    <div data-payment-section="bank_transfer" class="space-y-3 {{ $selectedMethod === 'bank_transfer' ? '' : 'hidden' }}">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bank name</label>
            <input type="text" name="bank_name" value="{{ $isCurrentPayment ? old('bank_name') : ($storedCapture['bank_name'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
            @if($isCurrentPayment && $errors->has('bank_name'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('bank_name') }}</p>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Account name</label>
            <input type="text" name="account_name" value="{{ $bankAccountName }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
            @if($isCurrentPayment && $errors->has('account_name'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('account_name') }}</p>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Account number</label>
                <input type="text" name="account_number" value="{{ $isCurrentPayment ? old('account_number') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" inputmode="numeric">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Branch code</label>
                <input type="text" name="branch_code" value="{{ $isCurrentPayment ? old('branch_code') : ($storedCapture['branch_code'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
            </div>
        </div>
        @if($isCurrentPayment && ($errors->has('account_number') || $errors->has('branch_code')))
            <p class="text-sm text-red-600">{{ $errors->first('account_number') ?: $errors->first('branch_code') }}</p>
        @endif
    </div>

    <div data-payment-section="mobile_money" class="space-y-3 {{ $selectedMethod === 'mobile_money' ? '' : 'hidden' }}">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile money provider</label>
            <input type="text" name="mobile_provider" value="{{ $isCurrentPayment ? old('mobile_provider') : ($storedCapture['provider'] ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Orange Money, Mascom MyZaka, BTC Smega">
            @if($isCurrentPayment && $errors->has('mobile_provider'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('mobile_provider') }}</p>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile number</label>
            <input type="text" name="mobile_number" value="{{ $isCurrentPayment ? old('mobile_number') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="+267 71 234 567">
            @if($isCurrentPayment && $errors->has('mobile_number'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('mobile_number') }}</p>
            @endif
        </div>
    </div>

    <div class="rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-600">
        Enter the details for the selected payment method before confirming this payment.
    </div>

    <button type="submit" class="w-full bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
        {{ $submitLabel }}
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById(@json($paymentFormId));

    if (!form || form.dataset.paymentSetup === 'true') {
        return;
    }

    form.dataset.paymentSetup = 'true';

    const methodSelect = form.querySelector('[data-payment-method]');
    const sections = form.querySelectorAll('[data-payment-section]');

    const updateSections = () => {
        const selectedMethod = methodSelect.value;

        sections.forEach((section) => {
            const isActive = section.dataset.paymentSection === selectedMethod;
            section.classList.toggle('hidden', !isActive);

            section.querySelectorAll('input, select, textarea').forEach((input) => {
                input.disabled = !isActive;
            });
        });
    };

    methodSelect.addEventListener('change', updateSections);
    updateSections();

    // ===== Paddle checkout button =====
    const paddleBtn = document.getElementById(@json($paddleBtnId));
    if (!paddleBtn || typeof Paddle === 'undefined') return;

    paddleBtn.addEventListener('click', async () => {
        const paymentId = paddleBtn.dataset.paymentId;
        paddleBtn.disabled = true;
        paddleBtn.textContent = 'Opening checkout…';

        try {
            const resp = await fetch(@json(route('student.payments.paddle.checkout')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ payment_id: paymentId }),
            });

            const json = await resp.json();

            if (!resp.ok || json.error) {
                alert(json.error ?? 'Failed to open checkout. Please try again.');
                paddleBtn.disabled = false;
                paddleBtn.textContent = 'Pay {{ $payment->formatted_amount }} via Paddle';
                return;
            }

            Paddle.Checkout.open({ transactionId: json.transaction_id });
        } catch (e) {
            alert('Network error. Please check your connection and try again.');
            paddleBtn.disabled = false;
            paddleBtn.textContent = 'Pay {{ $payment->formatted_amount }} via Paddle';
        }
    });

    // Redirect to payments page when Paddle checkout completes
    window.addEventListener('paddle:completed', () => {
        window.location.href = @json(route('student.payments'));
    });
});
</script>
