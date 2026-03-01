@extends('layouts.admin')

@section('title', 'Buildings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Buildings</h5>
    <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary btn-sm">+ Add Building</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Floors</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($buildings as $building)
            <tr>
                <td><code>{{ $building->code }}</code></td>
                <td>{{ $building->name }}</td>
                <td>{{ $building->floors_count }}</td>
                <td>{{ Str::limit($building->description, 60) }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.buildings.destroy', $building) }}"
                          class="d-inline" onsubmit="return confirm('Delete this building?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No buildings yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
