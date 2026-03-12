@extends('layouts.app')

@section('title', 'Manage Accommodations')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Manage Accommodations</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">
                @isset($block) Block {{ $block }} Rooms @else All Rooms @endisset
            </h3>
            <a href="{{ route('welfare.accommodations.create') }}"
               class="bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Add New Room
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            @if($accommodations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Block</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Floor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Occupancy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rent/mo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($accommodations as $room)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 text-sm">{{ $room->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $room->block ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $room->floor ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($room->type) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-900">{{ $room->current_occupancy }}/{{ $room->capacity }}</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                            @php $pct = $room->capacity > 0 ? ($room->current_occupancy / $room->capacity) * 100 : 0; @endphp
                                            <div class="h-1.5 rounded-full {{ $pct >= 100 ? 'bg-red-600' : ($pct >= 80 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                 style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">P{{ number_format($room->monthly_rent, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $room->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $room->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('welfare.accommodations.edit', $room) }}"
                                       class="text-red-800 hover:text-red-900 text-sm font-medium">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $accommodations->links() }}
                </div>
            @else
                <div class="text-center py-16 text-gray-500">
                    <i class="fas fa-bed text-5xl mb-4 text-gray-300"></i>
                    <p class="mb-4">No accommodations found.</p>
                    <a href="{{ route('welfare.accommodations.create') }}"
                       class="bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition text-sm">
                        Add First Room
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
