@extends('layouts.admin')

@section('title', 'Edit Start')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.origins.index') }}" class="text-muted text-decoration-none">← Starts</a>
</div>
<div class="card shadow-sm p-4" style="max-width: 560px;">
    <h5 class="fw-bold mb-4">Edit Start (Origin)</h5>
    <form method="POST" action="{{ route('admin.origins.update', $origin) }}">
        @csrf @method('PUT')
        @include('admin.origins._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.origins.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
