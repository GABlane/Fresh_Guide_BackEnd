@extends('layouts.admin')
@section('title', 'Edit Item')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.facilities.index') }}">Items</a>
    <span class="sep">/</span> {{ $facility->name }}
</div>

<div class="form-card form-wrap">
    <h2>Edit Item</h2>
    <form method="POST" action="{{ route('admin.facilities.update', $facility) }}">
        @csrf @method('PUT')
        @include('admin.facilities._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
