@extends('layouts.app')

@section('title', 'Announcements')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Announcements</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900">Create announcement</h1>
                <p class="text-gray-600 mt-2">Publish updates to all users or target a specific role with timed visibility.</p>

                <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4 mt-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea name="content" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target role</label>
                        <select name="target_role" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            <option value="">All users</option>
                            @foreach(['student', 'landlord', 'welfare', 'admin'] as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="important">Important</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publish at</label>
                        <input type="datetime-local" name="published_at" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expires at</label>
                        <input type="datetime-local" name="expires_at" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <label class="flex items-center gap-3 text-sm text-gray-700">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" checked class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                        Publish announcement
                    </label>
                    <button type="submit" class="w-full bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Save announcement</button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="space-y-4">
                @forelse($announcements as $announcement)
                    <div class="bg-white rounded-2xl shadow p-6">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $announcement->title }}</h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $announcement->priority === 'important' ? 'bg-red-100 text-red-800' : ($announcement->priority === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($announcement->priority) }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $announcement->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $announcement->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">{{ $announcement->target_role ? ucfirst($announcement->target_role) : 'All users' }} • Created by {{ $announcement->creator->name ?? 'Admin' }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @csrf
                            @method('PUT')
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                <input type="text" name="title" value="{{ $announcement->title }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                                <textarea name="content" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>{{ $announcement->content }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target role</label>
                                <select name="target_role" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                    <option value="">All users</option>
                                    @foreach(['student', 'landlord', 'welfare', 'admin'] as $role)
                                        <option value="{{ $role }}" {{ $announcement->target_role === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                    @foreach(['info', 'warning', 'important'] as $priority)
                                        <option value="{{ $priority }}" {{ $announcement->priority === $priority ? 'selected' : '' }}>{{ ucfirst($priority) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Publish at</label>
                                <input type="datetime-local" name="published_at" value="{{ optional($announcement->published_at)->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expires at</label>
                                <input type="datetime-local" name="expires_at" value="{{ optional($announcement->expires_at)->format('Y-m-d\TH:i') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            </div>
                            <label class="flex items-center gap-3 text-sm text-gray-700">
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" name="is_published" value="1" {{ $announcement->is_published ? 'checked' : '' }} class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                                Publish announcement
                            </label>
                            <div class="md:col-span-2 flex flex-wrap gap-3 justify-end">
                                <button type="submit" class="bg-red-800 text-white px-5 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Update</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="mt-4 flex justify-end" onsubmit="return confirm('Delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="border border-red-200 text-red-700 px-4 py-2 rounded-lg font-semibold hover:bg-red-50 transition">Delete announcement</button>
                        </form>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow p-12 text-center text-gray-500">No announcements created yet.</div>
                @endforelse
            </div>

            <div class="bg-white rounded-2xl shadow px-6 py-4 mt-6">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
