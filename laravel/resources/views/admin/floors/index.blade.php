@extends('layouts.admin')

@section('title', 'Floors')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Floors</h5>
    <a href="{{ route('admin.floors.create') }}" class="btn btn-primary btn-sm">+ Add Floor</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Building</th>
                <th>#</th>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($floors as $floor)
            <tr>
                <td>{{ $floor->building->name ?? '—' }}</td>
                <td>{{ $floor->number }}</td>
                <td>{{ $floor->name }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.floors.edit', $floor) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.floors.destroy', $floor) }}"
                          class="d-inline" onsubmit="return confirm('Delete this floor?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No floors yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
