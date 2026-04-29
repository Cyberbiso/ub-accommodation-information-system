@extends('layouts.app')

@section('title', 'Landlord Verifications')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Landlord Verifications</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Admin landlord verification queue</h1>
                    <p class="text-gray-600 mt-2">Review company registration, tax clearance, ID, and property ownership documents before landlords can list accommodation.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'needs_more_info' => 'Needs Info', 'verified' => 'Verified', 'rejected' => 'Rejected'] as $status => $label)
                        <a href="{{ route('admin.landlords.verifications', ['status' => $status === 'all' ? null : $status]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status', 'all') === $status || ($status === 'all' && !request('status')) ? 'bg-red-800 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($landlords as $landlord)
                <div class="bg-white rounded-2xl shadow overflow-hidden">
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $landlord->company_name ?? $landlord->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $landlord->landlord_verification_status === 'verified' ? 'bg-green-100 text-green-800' : ($landlord->landlord_verification_status === 'rejected' ? 'bg-red-100 text-red-800' : ($landlord->landlord_verification_status === 'needs_more_info' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $landlord->email }} • {{ $landlord->phone ?? 'No phone supplied' }}</p>
                            <p class="text-sm text-gray-600 mt-1">Current stage: {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_stage ?? 'company_registration')) }}</p>
                            @if($landlord->verification_notes)
                                <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    <span class="font-semibold text-gray-900">Latest notes:</span>
                                    {{ $landlord->verification_notes }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                @foreach($landlord->landlordVerificationDocuments as $document)
                                    <div class="border border-gray-200 rounded-xl p-4">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="font-semibold text-gray-900">{{ $document->document_type_label }}</p>
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $document->status === 'verified' ? 'bg-green-100 text-green-800' : ($document->status === 'rejected' ? 'bg-red-100 text-red-800' : ($document->status === 'more_info_required' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">{{ $document->original_name }}</p>
                                        @if($document->review_notes)
                                            <div class="mt-3 bg-gray-50 rounded-lg p-3 text-sm text-gray-700">
                                                <span class="font-semibold text-gray-900">Review notes:</span>
                                                {{ $document->review_notes }}
                                            </div>
                                        @endif
                                        <a href="{{ route('documents.landlord-verification.show', $document) }}" target="_blank" class="inline-flex mt-3 text-sm text-red-800 hover:underline">Open document</a>

                                        @if($landlord->landlord_verification_status !== 'verified' || $document->status !== 'verified')
                                            <form method="POST" action="{{ route('admin.landlords.verifications.documents.review', $document) }}" class="mt-4 space-y-2 border-t border-gray-100 pt-3" onsubmit="this.querySelectorAll('button[type=submit]').forEach(b => b.disabled = true)">
                                                @csrf
                                                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Notes for this document">{{ $document->review_notes }}</textarea>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <button type="submit" name="action" value="approve" class="bg-green-700 text-white px-2 py-2 rounded-md text-xs font-semibold hover:bg-green-800 transition">Approve</button>
                                                    <button type="submit" name="action" value="request_more_info" class="bg-blue-700 text-white px-2 py-2 rounded-md text-xs font-semibold hover:bg-blue-800 transition">More info</button>
                                                    <button type="submit" name="action" value="reject" class="bg-red-700 text-white px-2 py-2 rounded-md text-xs font-semibold hover:bg-red-800 transition">Reject</button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6">
                            @if($landlord->landlord_verification_status === 'verified')
                                <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                                    This landlord is fully verified and can advertise accommodation.
                                </div>
                            @else
                                <form method="POST" action="{{ route('admin.landlords.verifications.process', $landlord) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Review notes</label>
                                        <textarea name="notes" rows="8" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Decision notes, checklist findings, or request for more information"></textarea>
                                    </div>
                                    <button type="submit" name="action" value="approve" class="w-full bg-green-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-800 transition">Approve current stage</button>
                                    <button type="submit" name="action" value="request_more_info" class="w-full bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-800 transition">Request more information</button>
                                    <button type="submit" name="action" value="reject" class="w-full bg-red-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-800 transition">Reject verification</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow p-12 text-center text-gray-500">No landlord verification records match the current filter.</div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow px-6 py-4">
            {{ $landlords->links() }}
        </div>
    </div>
</div>
@endsection
