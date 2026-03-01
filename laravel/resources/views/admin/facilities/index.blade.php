@extends('layouts.admin')

@section('title', 'Facilities (Items)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Items (Facilities)</h5>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary btn-sm">+ Add Item</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Icon key</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($facilities as $facility)
            <tr>
                <td>{{ $facility->name }}</td>
                <td><code>{{ $facility->icon ?? '—' }}</code></td>
                <td class="text-end">
                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                          class="d-inline" onsubmit="return confirm('Delete this facility?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted py-4">No items yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
