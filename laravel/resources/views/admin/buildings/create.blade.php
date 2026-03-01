@extends('layouts.admin')
@section('title', 'Add Building')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.buildings.index') }}">Buildings</a>
    <span class="sep">/</span> Add
</div>

<div class="form-card form-wrap">
    <h2>Add Building</h2>
    <form method="POST" action="{{ route('admin.buildings.store') }}">
        @csrf
        @include('admin.buildings._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.buildings.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
