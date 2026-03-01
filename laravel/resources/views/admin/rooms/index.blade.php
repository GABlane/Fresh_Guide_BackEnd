@extends('layouts.admin')
@section('title', 'Rooms')
@section('content')

<div class="page-header">
    <div><h1>Rooms</h1><div class="sub">Campus room directory</div></div>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">+ Add Room</a>
</div>

<div class="card table-wrap">
    <table>
        <thead>
            <tr><th>Code</th><th>Name</th><th>Type</th><th>Location</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($rooms as $room)
            <tr>
                <td><code>{{ $room->code }}</code></td>
                <td>{{ $room->name }}</td>
                <td style="color:var(--txt-muted);">{{ ucfirst($room->type) }}</td>
                <td style="color:var(--txt-muted);">
                    {{ $room->floor->building->name ?? '?' }} ›
                    {{ $room->floor->name ?? '?' }}
                </td>
                <td class="td-actions">
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                          style="display:inline" onsubmit="return confirm('Delete this room?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row"><td colspan="5">No rooms yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
