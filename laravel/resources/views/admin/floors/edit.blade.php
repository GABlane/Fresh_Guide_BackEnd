@extends('layouts.admin')

@section('title', 'Edit Floor')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.floors.index') }}" class="text-muted text-decoration-none">← Floors</a>
</div>
<div class="card shadow-sm p-4" style="max-width: 560px;">
    <h5 class="fw-bold mb-4">Edit Floor</h5>
    <form method="POST" action="{{ route('admin.floors.update', $floor) }}">
        @csrf @method('PUT')
        @include('admin.floors._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.floors.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
