@php
    $paddleBtnId = 'paddle-btn-' . $payment->id;
    $formClass   = $formClass ?? '';
@endphp

<div class="space-y-3 {{ $formClass }}">
    @if(config('services.paddle.client_side_token'))
        <button
            id="{{ $paddleBtnId }}"
            type="button"
            data-payment-id="{{ $payment->id }}"
            class="w-full flex items-center justify-center gap-2 bg-red-800 hover:bg-red-900 text-white px-4 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-credit-card"></i>
            {{ $submitLabel ?? 'Pay now' }} — P{{ number_format($payment->amount, 2) }}
        </button>
        @if(config('services.paddle.environment') === 'sandbox')
            <p class="text-xs text-gray-400 text-center">Sandbox — test card <strong>4242 4242 4242 4242</strong>, any future date, any CVV.</p>
        @endif
    @else
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Online payment is not configured yet. Please contact the administrator.
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const paddleBtn = document.getElementById(@json($paddleBtnId));
    if (!paddleBtn || typeof Paddle === 'undefined') return;

    paddleBtn.addEventListener('click', async () => {
        const paymentId = paddleBtn.dataset.paymentId;
        paddleBtn.disabled = true;
        paddleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Opening checkout…';

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
                paddleBtn.innerHTML = '<i class="fas fa-credit-card"></i> Pay now';
                return;
            }

            window.location.href = json.checkout_url;
        } catch (e) {
            alert('Network error. Please check your connection and try again.');
            paddleBtn.disabled = false;
            paddleBtn.innerHTML = '<i class="fas fa-credit-card"></i> Pay now';
        }
    });
});
</script>
