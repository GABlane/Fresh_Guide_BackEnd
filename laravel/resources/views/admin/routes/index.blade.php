@extends('layouts.admin')
@section('title', 'Routes')
@section('content')

<div class="page-header">
    <div><h1>Routes</h1><div class="sub">Origin → destination step guides</div></div>
    <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">+ Add Route</a>
</div>

<div class="card table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Start</th><th>Destination</th><th>Steps</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($routes as $route)
            <tr>
                <td>{{ $route->name }}</td>
                <td><code>{{ $route->origin->name ?? '—' }}</code></td>
                <td style="color:var(--txt-muted);">{{ $route->destinationRoom->name ?? '—' }}</td>
                <td style="color:var(--txt-muted);">{{ $route->steps->count() }}</td>
                <td class="td-actions">
                    <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.routes.destroy', $route) }}"
                          style="display:inline" onsubmit="return confirm('Delete this route and all steps?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row"><td colspan="5">No routes yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
