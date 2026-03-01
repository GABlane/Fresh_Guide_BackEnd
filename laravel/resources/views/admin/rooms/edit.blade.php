@extends('layouts.admin')
@section('title', 'Edit Room')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.rooms.index') }}">Rooms</a>
    <span class="sep">/</span> {{ $room->name }}
</div>

<div class="form-card form-wide">
    <h2>Edit Room — <span style="color:var(--accent);">{{ $room->code }}</span></h2>
    <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
        @csrf @method('PUT')
        @include('admin.rooms._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
