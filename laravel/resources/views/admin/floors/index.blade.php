@extends('layouts.admin')
@section('title', 'Floors')
@section('content')

<div class="page-header">
    <div><h1>Floors</h1><div class="sub">Building floor registry</div></div>
    <a href="{{ route('admin.floors.create') }}" class="btn btn-primary">+ Add Floor</a>
</div>

<div class="card table-wrap">
    <table>
        <thead>
            <tr><th>Building</th><th>#</th><th>Name</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($floors as $floor)
            <tr>
                <td>{{ $floor->building->name ?? '—' }}</td>
                <td style="color:var(--txt-muted);">{{ $floor->number }}</td>
                <td>{{ $floor->name }}</td>
                <td class="td-actions">
                    <a href="{{ route('admin.floors.edit', $floor) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.floors.destroy', $floor) }}"
                          style="display:inline" onsubmit="return confirm('Delete this floor?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row"><td colspan="4">No floors yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
