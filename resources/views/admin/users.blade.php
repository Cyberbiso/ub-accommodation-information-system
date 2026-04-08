@extends('layouts.app')

@section('title', 'User Management')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">User Management</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage portal users</h1>
                    <p class="text-gray-600 mt-2">Verify users, adjust roles, activate or deactivate accounts, and remove users when needed.</p>
                </div>
                <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Search name, email, ID">
                    <select name="role" class="border border-gray-300 rounded-lg px-4 py-3">
                        <option value="">All roles</option>
                        @foreach(['student', 'landlord', 'welfare', 'admin'] as $role)
                            <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="border border-gray-300 rounded-lg px-4 py-3">
                        <option value="">Any status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Filter users</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900">Create user</h2>
            <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @csrf
                <input type="text" name="name" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Full name" required>
                <input type="email" name="email" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Email address" required>
                <select name="role" class="border border-gray-300 rounded-lg px-4 py-3" required>
                    @foreach(['student', 'landlord', 'welfare', 'admin'] as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                <input type="text" name="student_id" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Student ID (optional)">
                <input type="text" name="company_name" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Company name (optional)">
                <input type="text" name="phone" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Phone number">
                <input type="password" name="password" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Password" required>
                <input type="password" name="password_confirmation" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Confirm password" required>
                <label class="flex items-center gap-3 text-sm text-gray-700 md:col-span-2 xl:col-span-1">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                    Activate immediately
                </label>
                <div class="md:col-span-2 xl:col-span-3 flex justify-end">
                    <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Create user</button>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($users as $user)
                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="lg:w-72">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">{{ ucfirst($user->role) }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $user->email }}</p>
                            @if($user->student_id)
                                <p class="text-sm text-gray-600 mt-1">Student ID: {{ $user->student_id }}</p>
                            @endif
                            @if($user->company_name)
                                <p class="text-sm text-gray-600 mt-1">Company: {{ $user->company_name }}</p>
                            @endif
                            @if($user->isLandlord())
                                <p class="text-sm text-gray-600 mt-1">Verification: {{ ucfirst(str_replace('_', ' ', $user->landlord_verification_status ?? 'pending')) }}</p>
                            @endif
                        </div>

                        <div class="flex-1">
                            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $user->name }}" class="border border-gray-300 rounded-lg px-4 py-3" required>
                                <input type="email" name="email" value="{{ $user->email }}" class="border border-gray-300 rounded-lg px-4 py-3" required>
                                <select name="role" class="border border-gray-300 rounded-lg px-4 py-3" required>
                                    @foreach(['student', 'landlord', 'welfare', 'admin'] as $role)
                                        <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="student_id" value="{{ $user->student_id }}" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Student ID">
                                <input type="text" name="company_name" value="{{ $user->company_name }}" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Company name">
                                <input type="text" name="phone" value="{{ $user->phone }}" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Phone number">
                                <input type="password" name="password" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="New password (optional)">
                                <input type="password" name="password_confirmation" class="border border-gray-300 rounded-lg px-4 py-3" placeholder="Confirm new password">
                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                                    Account active
                                </label>
                                <div class="md:col-span-2 xl:col-span-3 flex flex-wrap gap-3 justify-end">
                                    <button type="submit" class="bg-red-800 text-white px-5 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Save changes</button>
                                </div>
                            </form>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mt-4 flex justify-end" onsubmit="return confirm('Delete this user account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border border-red-200 text-red-700 px-4 py-2 rounded-lg font-semibold hover:bg-red-50 transition">Delete account</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow p-12 text-center text-gray-500">No users found for the current filter.</div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
