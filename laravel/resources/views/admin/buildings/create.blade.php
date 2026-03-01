@extends('layouts.admin')

@section('title', 'Add Building')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.buildings.index') }}" class="text-muted text-decoration-none">← Buildings</a>
</div>
<div class="card shadow-sm p-4" style="max-width: 560px;">
    <h5 class="fw-bold mb-4">Add Building</h5>
    <form method="POST" action="{{ route('admin.buildings.store') }}">
        @csrf
        @include('admin.buildings._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.buildings.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
