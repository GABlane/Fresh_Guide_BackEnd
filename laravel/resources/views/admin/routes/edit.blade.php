@extends('layouts.admin')
@section('title', 'Edit Route')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.routes.index') }}">Routes</a>
    <span class="sep">/</span> {{ $route->name }}
</div>

<div class="form-card form-wide">
    <h2>Edit Route — <span style="color:var(--accent);">{{ $route->origin->name ?? '' }} → {{ $route->destinationRoom->name ?? '' }}</span></h2>
    <form method="POST" action="{{ route('admin.routes.update', $route) }}">
        @csrf @method('PUT')
        @include('admin.routes._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Route</button>
            <a href="{{ route('admin.routes.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
