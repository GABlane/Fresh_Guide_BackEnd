@extends('layouts.admin')
@section('title', 'Starts')
@section('content')

<div class="page-header">
    <div><h1>Starts (Origins)</h1><div class="sub">Fixed route starting points</div></div>
    <a href="{{ route('admin.origins.create') }}" class="btn btn-primary">+ Add Start</a>
</div>

<div class="card table-wrap">
    <table>
        <thead>
            <tr><th>Code</th><th>Name</th><th>Description</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($origins as $origin)
            <tr>
                <td><code>{{ $origin->code }}</code></td>
                <td>{{ $origin->name }}</td>
                <td style="color:var(--txt-muted);">{{ Str::limit($origin->description, 60) }}</td>
                <td class="td-actions">
                    <a href="{{ route('admin.origins.edit', $origin) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.origins.destroy', $origin) }}"
                          style="display:inline" onsubmit="return confirm('Delete this origin?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row"><td colspan="4">No origins yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
