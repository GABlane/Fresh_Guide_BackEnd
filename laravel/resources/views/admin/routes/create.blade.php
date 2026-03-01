@extends('layouts.admin')

@section('title', 'Add Route')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.routes.index') }}" class="text-muted text-decoration-none">← Routes</a>
</div>
<div class="card shadow-sm p-4" style="max-width: 800px;">
    <h5 class="fw-bold mb-4">Add Route</h5>
    <form method="POST" action="{{ route('admin.routes.store') }}">
        @csrf
        @include('admin.routes._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Save Route</button>
            <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
