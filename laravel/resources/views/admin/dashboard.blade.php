@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0 fw-bold">Dashboard</h4>
</div>

{{-- Primary CTA --}}
<a href="{{ route('admin.routes.create') }}" class="btn btn-primary btn-lg mb-4 px-5">
    + Add Route
</a>

{{-- Quick links --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label' => 'Rooms',              'route' => 'admin.rooms.index'],
        ['label' => 'Starts (Origins)',   'route' => 'admin.origins.index'],
        ['label' => 'Items (Facilities)', 'route' => 'admin.facilities.index'],
        ['label' => 'Routes',             'route' => 'admin.routes.index'],
        ['label' => 'Buildings',          'route' => 'admin.buildings.index'],
        ['label' => 'Floors',             'route' => 'admin.floors.index'],
    ] as $link)
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route($link['route']) }}" class="card text-center text-decoration-none p-3 h-100 d-flex align-items-center justify-content-center shadow-sm">
            <span class="fw-semibold text-dark">{{ $link['label'] }}</span>
        </a>
    </div>
    @endforeach
</div>

{{-- Publish Update --}}
<div class="card shadow-sm p-4" style="max-width: 480px;">
    <h6 class="fw-semibold mb-3">Publish Dataset Update</h6>
    <form method="POST" action="{{ route('admin.publish') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Release note <span class="text-muted">(optional)</span></label>
            <input type="text" name="note" class="form-control" placeholder="e.g. Added Block C rooms">
        </div>
        <button type="submit" class="btn btn-success"
                onclick="return confirm('Publish a new dataset version?')">
            Publish Update
        </button>
    </form>
</div>
@endsection
