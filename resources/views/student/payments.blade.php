@extends('layouts.app')

@section('title', 'Payments')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Payments</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Completed</p>
                <p class="text-3xl font-bold text-green-700 mt-3">P{{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm uppercase tracking-[0.2em] text-gray-500">Outstanding</p>
                <p class="text-3xl font-bold text-red-700 mt-3">P{{ number_format($pendingPayments, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-2xl font-bold text-gray-900">Payment history</h1>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                    <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-lg font-bold text-gray-900">{{ $payment->type_label }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $payment->formatted_amount }} • Created {{ $payment->created_at->format('d M Y') }}</p>
                            @if($payment->transaction_id)
                                <p class="text-sm text-gray-600 mt-1">Transaction: {{ $payment->transaction_id }}</p>
                            @endif
                        </div>
                        @if($payment->status === 'pending')
                            <form method="POST" action="{{ route('student.payments.process') }}" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                <select name="payment_method" class="border border-gray-300 rounded-lg px-4 py-2">
                                    <option value="card">Card</option>
                                    <option value="bank_transfer">Bank transfer</option>
                                    <option value="mobile_money">Mobile money</option>
                                </select>
                                <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-900 transition">Pay now</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No payments recorded yet.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
