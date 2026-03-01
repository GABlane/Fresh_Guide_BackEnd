@extends('layouts.admin')
@section('title', 'Items')
@section('content')

<div class="page-header">
    <div><h1>Items (Facilities)</h1><div class="sub">Room facility dictionary</div></div>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">+ Add Item</a>
</div>

<div class="card table-wrap">
    <table>
        <thead>
            <tr><th>Name</th><th>Icon key</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($facilities as $facility)
            <tr>
                <td>{{ $facility->name }}</td>
                <td><code>{{ $facility->icon ?? '—' }}</code></td>
                <td class="td-actions">
                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                          style="display:inline" onsubmit="return confirm('Delete this item?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row"><td colspan="3">No items yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
