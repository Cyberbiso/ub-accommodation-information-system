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

    // FRONTEND CHECK 1: Paddle.js loaded?
    if (!paddleBtn) { console.error('[Paddle] Button not found:', @json($paddleBtnId)); return; }
    if (typeof Paddle === 'undefined') { console.error('[Paddle] Paddle.js not loaded — check client_side_token config'); return; }

    console.log('[Paddle] Initialized. Environment:', Paddle.Environment?.get?.() ?? 'unknown');

    paddleBtn.addEventListener('click', async () => {
        const paymentId = paddleBtn.dataset.paymentId;
        paddleBtn.disabled = true;
        paddleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Opening checkout…';

        try {
            console.log('[Paddle] Requesting transaction for payment_id:', paymentId);

            const resp = await fetch(@json(route('student.payments.paddle.checkout')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ payment_id: paymentId }),
            });

            const json = await resp.json();
            console.log('[Paddle] Backend response (HTTP ' + resp.status + '):', json);

            if (!resp.ok || json.error) {
                console.error('[Paddle] BACKEND ERROR:', json.error ?? json);
                alert('[Backend error] ' + (json.error ?? 'Failed to create transaction. Check laravel.log.'));
                paddleBtn.disabled = false;
                paddleBtn.innerHTML = '<i class="fas fa-credit-card"></i> Pay now';
                return;
            }

            // FRONTEND CHECK 2: transaction_id present?
            if (!json.transaction_id) {
                console.error('[Paddle] No transaction_id in response:', json);
                alert('[Frontend error] No transaction_id returned from server.');
                paddleBtn.disabled = false;
                paddleBtn.innerHTML = '<i class="fas fa-credit-card"></i> Pay now';
                return;
            }

            console.log('[Paddle] Opening overlay for transaction:', json.transaction_id);

            // Listen for successful payment before opening overlay
            window.addEventListener('paddle:completed', async function onPaddleCompleted(e) {
                window.removeEventListener('paddle:completed', onPaddleCompleted);
                console.log('[Paddle] Payment completed, verifying with server...', e.detail);

                const txnId = e.detail?.data?.transaction_id ?? e.detail?.transaction_id ?? null;

                try {
                    await fetch(@json(route('student.payments.paddle.verify')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ transaction_id: txnId }),
                    });
                } catch (err) {
                    console.error('[Paddle] Verify call failed:', err);
                }

                window.location.href = @json(route('student.payments'));
            }, { once: true });

            Paddle.Checkout.open({ transactionId: json.transaction_id });

        } catch (e) {
            console.error('[Paddle] NETWORK/JS ERROR:', e);
            alert('[Network error] ' + e.message);
            paddleBtn.disabled = false;
            paddleBtn.innerHTML = '<i class="fas fa-credit-card"></i> Pay now';
        }
    });
});
</script>
