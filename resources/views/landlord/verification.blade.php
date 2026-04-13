@extends('layouts.app')

@section('title', 'Landlord Verification')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Landlord Verification</h2>
@endsection

@section('content')
@php $packageLocked = $landlord->isVerifiedLandlord(); @endphp
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900">Verification progress</h1>
                <p class="text-gray-600 mt-2">Your listings are enabled only after each stage below is approved.</p>

                <div class="mt-6 space-y-4">
                    @foreach($steps as $key => $label)
                        @php
                            $document = $documents[$key] ?? null;
                            $status = $document?->status ?? 'pending';
                        @endphp
                        <div class="border rounded-xl p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $label }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $document?->original_name ?? 'Awaiting upload' }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status === 'verified' ? 'bg-green-100 text-green-800' : ($status === 'rejected' ? 'bg-red-100 text-red-800' : ($status === 'more_info_required' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                @if($document?->path)
                                    <a href="{{ route('documents.landlord-verification.show', $document) }}" target="_blank" class="inline-flex text-sm text-red-800 hover:underline">Open document</a>
                                @endif
                                @if($document)
                                    <form method="POST" action="{{ route('landlord.verification.documents.replace', $document) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        <input type="file" name="document" class="block w-full text-xs text-gray-700" @disabled($packageLocked)>
                                        <button type="submit" class="px-3 py-2 rounded-lg text-xs font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed" @disabled($packageLocked)>Replace</button>
                                    </form>
                                    <form method="POST" action="{{ route('landlord.verification.documents.destroy', $document) }}" onsubmit="return confirm('Remove this verification document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-lg text-xs font-semibold border border-red-200 text-red-700 hover:bg-red-50 transition disabled:opacity-50 disabled:cursor-not-allowed" @disabled($packageLocked)>Delete</button>
                                    </form>
                                @endif
                            </div>
                            @if($document?->review_notes)
                                <p class="text-sm text-gray-700 mt-3">{{ $document->review_notes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Submit or update verification documents</h2>
                        <p class="text-gray-600 mt-2">Admin reviews company registration, tax clearance, director or signatory ID, and property ownership documentation in sequence.</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $landlord->isVerifiedLandlord() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_status)) }}
                    </span>
                </div>

                @if($landlord->verification_notes)
                    <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                        <span class="font-semibold text-gray-900">Latest reviewer notes:</span>
                        {{ $landlord->verification_notes }}
                    </div>
                @endif

                @if($packageLocked)
                    <div class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-800">
                        Your verification package has been approved, so the edit, replace, delete, and update actions are now inactive.
                    </div>
                @endif

                <form method="POST" action="{{ route('landlord.verification.update') }}" enctype="multipart/form-data" class="space-y-6 mt-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $landlord->company_name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required @disabled($packageLocked)>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $landlord->phone) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required @disabled($packageLocked)>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Registration Number</label>
                            <input type="text" name="company_registration_number" value="{{ old('company_registration_number', $landlord->company_registration_number) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required @disabled($packageLocked)>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax Identification Number</label>
                            <input type="text" name="tax_identification_number" value="{{ old('tax_identification_number', $landlord->tax_identification_number) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required @disabled($packageLocked)>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Registration Document</label>
                            <input type="file" name="company_registration_document" class="block w-full text-sm text-gray-700" @disabled($packageLocked)>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax Clearance Certificate</label>
                            <input type="file" name="tax_clearance_certificate" class="block w-full text-sm text-gray-700" @disabled($packageLocked)>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Director / Signatory ID</label>
                            <input type="file" name="identity_document" class="block w-full text-sm text-gray-700" @disabled($packageLocked)>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Ownership Document</label>
                            <input type="file" name="property_ownership_document" class="block w-full text-sm text-gray-700" @disabled($packageLocked)>
                        </div>
                    </div>

                    <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition disabled:opacity-50 disabled:cursor-not-allowed" @disabled($packageLocked)>
                        Submit verification package
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
