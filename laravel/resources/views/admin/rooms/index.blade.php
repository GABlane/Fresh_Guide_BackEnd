@extends('layouts.admin')

@section('title', 'Rooms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Rooms</h5>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">+ Add Room</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Floor / Building</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($rooms as $room)
            <tr>
                <td><code>{{ $room->code }}</code></td>
                <td>{{ $room->name }}</td>
                <td>{{ ucfirst($room->type) }}</td>
                <td>
                    {{ $room->floor->building->name ?? '?' }} ›
                    {{ $room->floor->name ?? '?' }}
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                          class="d-inline" onsubmit="return confirm('Delete this room?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No rooms yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
