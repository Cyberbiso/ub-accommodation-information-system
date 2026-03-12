@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Occupancy Overview</h1>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Capacity</h5>
                    <h2>{{ $stats['total_capacity'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Occupied</h5>
                    <h2>{{ $stats['total_occupied'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Occupancy Rate</h5>
                    <h2>{{ $stats['occupancy_rate'] }}%</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Available Rooms</h5>
                    <h2>{{ $stats['available_rooms'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    @foreach($occupancyData as $block => $rooms)
    <div class="card mb-4">
        <div class="card-header">
            <h3>Block {{ $block }}</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Type</th>
                        <th>Floor</th>
                        <th>Capacity</th>
                        <th>Occupied</th>
                        <th>Available</th>
                        <th>Occupancy Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ ucfirst($room->type) }}</td>
                        <td>{{ $room->floor ?? 'N/A' }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ $room->current_occupancy }}</td>
                        <td>{{ $room->capacity - $room->current_occupancy }}</td>
                        <td>
                            @php $rate = $room->capacity > 0 ? round(($room->current_occupancy / $room->capacity) * 100, 1) : 0; @endphp
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{ $rate }}%">{{ $rate }}%</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endsection