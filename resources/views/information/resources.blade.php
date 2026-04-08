@extends('layouts.public')

@section('title', 'Resources Library')

@section('content')
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-2">Resources Library</h1>
        <p class="text-lg opacity-90">Download guides, open useful links, and find onboarding documents in one place.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow p-6">
        <form method="GET" action="{{ route('information.resources') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Search titles or descriptions">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex flex-wrap gap-3">
                <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Filter Resources</button>
                <a href="{{ route('information.resources') }}" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-8">
        @forelse($resources as $resource)
            @php
                $resourceUrl = ($resource->file_path || $resource->external_link)
                    ? route('information.resources.download', $resource)
                    : null;
                $resourceActionLabel = in_array($resource->type, ['link', 'video'], true) ? 'Open Resource' : 'Download';
            @endphp
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-red-700 font-semibold">{{ ucfirst(str_replace('_', ' ', $resource->type)) }}</p>
                        <h2 class="text-xl font-bold text-gray-900 mt-2">{{ $resource->title }}</h2>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs">
                        {{ ucfirst(str_replace('_', ' ', $resource->category)) }}
                    </span>
                </div>

                <p class="text-gray-600 mt-4">{{ $resource->description ?: 'No description available for this resource yet.' }}</p>

                @if(is_array($resource->tags) && count($resource->tags) > 0)
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($resource->tags as $tag)
                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 flex items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">{{ number_format($resource->download_count) }} downloads</p>
                    @if($resourceUrl)
                        <a href="{{ $resourceUrl }}" class="inline-flex items-center gap-2 bg-red-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-900 transition">
                            <i class="fas fa-download"></i>{{ $resourceActionLabel }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">Resource not available yet</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow p-12 text-center text-gray-500">
                No resources matched your filters.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $resources->links() }}
    </div>
</div>
@endsection
