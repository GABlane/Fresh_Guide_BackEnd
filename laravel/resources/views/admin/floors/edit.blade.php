@extends('layouts.admin')
@section('title', 'Edit Floor')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.floors.index') }}">Floors</a>
    <span class="sep">/</span> {{ $floor->name }}
</div>

<div class="form-card form-wrap">
    <h2>Edit Floor</h2>
    <form method="POST" action="{{ route('admin.floors.update', $floor) }}">
        @csrf @method('PUT')
        @include('admin.floors._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.floors.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
