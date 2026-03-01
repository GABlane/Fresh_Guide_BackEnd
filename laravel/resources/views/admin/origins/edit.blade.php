@extends('layouts.admin')
@section('title', 'Edit Start')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.origins.index') }}">Starts</a>
    <span class="sep">/</span> {{ $origin->name }}
</div>

<div class="form-card form-wrap">
    <h2>Edit Start</h2>
    <form method="POST" action="{{ route('admin.origins.update', $origin) }}">
        @csrf @method('PUT')
        @include('admin.origins._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.origins.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
