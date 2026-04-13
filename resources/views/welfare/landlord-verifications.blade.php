@extends('layouts.app')

@section('title', 'Landlord Verifications')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Landlord Verifications</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-2xl font-bold text-gray-900">Multi-stage landlord verification</h1>
                <p class="text-gray-600 mt-1">Approve the current stage to move landlords through company registration, tax clearance, and property ownership checks.</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($landlords as $landlord)
                    @php
                        $currentDocument = $landlord->landlordVerificationDocuments->firstWhere('document_type', $landlord->landlord_verification_stage);
                    @endphp
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $landlord->company_name ?? $landlord->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $landlord->landlord_verification_status === 'verified' ? 'bg-green-100 text-green-800' : ($landlord->landlord_verification_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Current stage: {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_stage)) }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                                @foreach($landlord->landlordVerificationDocuments as $document)
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="font-semibold text-gray-900">{{ $document->document_type_label }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $document->original_name }}</p>
                                        <a href="{{ route('documents.landlord-verification.show', $document) }}" target="_blank" class="inline-flex mt-3 text-sm text-red-800 hover:underline">View document</a>
                                    </div>
                                @endforeach
                            </div>
                            @if($landlord->verification_notes)
                                <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    <span class="font-semibold text-gray-900">Latest notes:</span>
                                    {{ $landlord->verification_notes }}
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @if($landlord->landlord_verification_status !== 'verified')
                                <form method="POST" action="{{ route('welfare.landlords.verifications.process', $landlord) }}" class="space-y-3 bg-green-50 rounded-xl p-4">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <textarea name="notes" rows="3" class="w-full border border-green-200 rounded-lg px-4 py-3" placeholder="Optional approval notes"></textarea>
                                    <button type="submit" class="w-full bg-green-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-800 transition">Approve current stage</button>
                                </form>

                                <form method="POST" action="{{ route('welfare.landlords.verifications.process', $landlord) }}" class="space-y-3 bg-red-50 rounded-xl p-4">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <textarea name="notes" rows="3" class="w-full border border-red-200 rounded-lg px-4 py-3" placeholder="Reason for rejection"></textarea>
                                    <button type="submit" class="w-full bg-red-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-800 transition">Reject stage</button>
                                </form>
                            @else
                                <div class="bg-green-50 rounded-xl p-4 text-sm text-green-900">This landlord is fully verified and can advertise accommodation.</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No landlord verification records found.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $landlords->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
