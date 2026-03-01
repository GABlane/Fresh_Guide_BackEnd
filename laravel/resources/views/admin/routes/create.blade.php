@extends('layouts.admin')
@section('title', 'Add Route')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.routes.index') }}">Routes</a>
    <span class="sep">/</span> Add
</div>

<div class="form-card form-wide">
    <h2>Add Route</h2>
    <form method="POST" action="{{ route('admin.routes.store') }}">
        @csrf
        @include('admin.routes._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Route</button>
            <a href="{{ route('admin.routes.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
