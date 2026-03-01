@extends('layouts.admin')
@section('title', 'Edit Building')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.buildings.index') }}">Buildings</a>
    <span class="sep">/</span> {{ $building->name }}
</div>

<div class="form-card form-wrap">
    <h2>Edit Building</h2>
    <form method="POST" action="{{ route('admin.buildings.update', $building) }}">
        @csrf @method('PUT')
        @include('admin.buildings._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.buildings.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
