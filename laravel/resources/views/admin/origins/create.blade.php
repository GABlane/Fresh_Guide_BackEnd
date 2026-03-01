@extends('layouts.admin')
@section('title', 'Add Start')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.origins.index') }}">Starts</a>
    <span class="sep">/</span> Add
</div>

<div class="form-card form-wrap">
    <h2>Add Start (Origin)</h2>
    <form method="POST" action="{{ route('admin.origins.store') }}">
        @csrf
        @include('admin.origins._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.origins.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
