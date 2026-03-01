@extends('layouts.admin')

@section('title', 'Starts (Origins)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Starts (Origins)</h5>
    <a href="{{ route('admin.origins.create') }}" class="btn btn-primary btn-sm">+ Add Start</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($origins as $origin)
            <tr>
                <td><code>{{ $origin->code }}</code></td>
                <td>{{ $origin->name }}</td>
                <td>{{ Str::limit($origin->description, 60) }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.origins.edit', $origin) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.origins.destroy', $origin) }}"
                          class="d-inline" onsubmit="return confirm('Delete this origin?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">No origins yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
