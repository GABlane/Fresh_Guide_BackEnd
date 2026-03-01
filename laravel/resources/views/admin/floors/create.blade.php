@extends('layouts.admin')
@section('title', 'Add Floor')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.floors.index') }}">Floors</a>
    <span class="sep">/</span> Add
</div>

<div class="form-card form-wrap">
    <h2>Add Floor</h2>
    <form method="POST" action="{{ route('admin.floors.store') }}">
        @csrf
        @include('admin.floors._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.floors.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
