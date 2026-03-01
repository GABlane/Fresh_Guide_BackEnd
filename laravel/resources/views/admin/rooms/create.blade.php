@extends('layouts.admin')
@section('title', 'Add Room')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.rooms.index') }}">Rooms</a>
    <span class="sep">/</span> Add
</div>

<div class="form-card form-wide">
    <h2>Add Room</h2>
    <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf
        @include('admin.rooms._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
