@extends('layouts.admin')

@section('title', 'Routes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Routes</h5>
    <a href="{{ route('admin.routes.create') }}" class="btn btn-primary btn-sm">+ Add Route</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Start</th>
                <th>Destination</th>
                <th>Steps</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($routes as $route)
            <tr>
                <td>{{ $route->name }}</td>
                <td>{{ $route->origin->name ?? '—' }}</td>
                <td>{{ $route->destinationRoom->name ?? '—' }}</td>
                <td>{{ $route->steps_count ?? $route->steps->count() }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.routes.destroy', $route) }}"
                          class="d-inline" onsubmit="return confirm('Delete this route and all its steps?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No routes yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
