@extends('layouts.admin')
@section('title', 'Add Item')
@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.facilities.index') }}">Items</a>
    <span class="sep">/</span> Add
</div>

<div class="form-card form-wrap">
    <h2>Add Item (Facility)</h2>
    <form method="POST" action="{{ route('admin.facilities.store') }}">
        @csrf
        @include('admin.facilities._form')
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

@endsection
