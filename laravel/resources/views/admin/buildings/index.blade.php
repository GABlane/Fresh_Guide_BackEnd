@extends('layouts.admin')
@section('title', 'Buildings')
@section('content')

<div class="page-header">
    <div>
        <h1>Buildings</h1>
        <div class="sub">Campus building registry</div>
    </div>
    <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">+ Add Building</a>
</div>

<div class="card table-wrap">
    <table>
        <thead>
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
                <td style="color:var(--txt-muted);">{{ $building->floors_count }}</td>
                <td style="color:var(--txt-muted);">{{ Str::limit($building->description, 60) }}</td>
                <td class="td-actions">
                    <a href="{{ route('admin.buildings.edit', $building) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.buildings.destroy', $building) }}"
                          style="display:inline" onsubmit="return confirm('Delete this building?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row"><td colspan="5">No buildings yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
