@extends('layouts.admin')

@section('title', 'Edit Room')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.rooms.index') }}" class="text-muted text-decoration-none">← Rooms</a>
</div>
<div class="card shadow-sm p-4" style="max-width: 700px;">
    <h5 class="fw-bold mb-4">Edit Room — {{ $room->name }}</h5>
    <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
        @csrf @method('PUT')
        @include('admin.rooms._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
