@extends('layouts.admin')

@section('title', 'Edit Item')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.facilities.index') }}" class="text-muted text-decoration-none">← Items</a>
</div>
<div class="card shadow-sm p-4" style="max-width: 560px;">
    <h5 class="fw-bold mb-4">Edit Item (Facility)</h5>
    <form method="POST" action="{{ route('admin.facilities.update', $facility) }}">
        @csrf @method('PUT')
        @include('admin.facilities._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
