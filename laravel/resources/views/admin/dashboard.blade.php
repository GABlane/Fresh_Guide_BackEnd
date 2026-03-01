@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

@php
    $stats = [
        ['emoji'=>'↗️', 'label'=>'Routes',    'val'=>\App\Models\CampusRoute::count(), 'color'=>'primary', 'route'=>'admin.routes.index'],
        ['emoji'=>'🚪', 'label'=>'Rooms',     'val'=>\App\Models\Room::count(),        'color'=>'secondary','route'=>'admin.rooms.index'],
        ['emoji'=>'🏛️', 'label'=>'Buildings', 'val'=>\App\Models\Building::count(),    'color'=>'green',   'route'=>'admin.buildings.index'],
        ['emoji'=>'📍', 'label'=>'Starts',    'val'=>\App\Models\Origin::count(),      'color'=>'rose',    'route'=>'admin.origins.index'],
    ];
    $latest = \App\Models\DataVersion::latest('published_at')->first();
@endphp

{{-- Hero --}}
<div style="margin-bottom:28px;">
    <h1 style="font-family:'Fraunces',serif; font-size:32px; font-weight:900; color:var(--txt); line-height:1.1; margin-bottom:6px;">
        Hey there! 👋
    </h1>
    <p style="font-size:15px; color:var(--txt-muted); font-style:italic;">
        Here's what's happening on campus today.
    </p>
</div>

{{-- Big CTA --}}
<div style="background:linear-gradient(135deg,var(--primary),var(--primary-dim)); border-radius:18px; padding:24px 28px; margin-bottom:28px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 8px 28px rgba(124,58,237,.28);">
    <div>
        <div style="font-family:'Fraunces',serif; font-size:22px; font-weight:700; color:#fff; margin-bottom:4px;">
            Add a new route ↗️
        </div>
        <div style="font-size:13.5px; color:rgba(255,255,255,.75);">
            Create step-by-step directions for students
        </div>
    </div>
    <a href="{{ route('admin.routes.create') }}"
       style="background:#fff; color:var(--primary); padding:11px 24px; border-radius:999px; font-family:'Nunito',sans-serif; font-weight:800; font-size:14px; text-decoration:none; white-space:nowrap; box-shadow:0 4px 14px rgba(0,0,0,.15); transition:transform .15s; display:inline-block;"
       onmouseover="this.style.transform='scale(1.04)'"
       onmouseout="this.style.transform='scale(1)'">
        Get started →
    </a>
</div>

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px;">
@foreach($stats as $s)
    @php
        $colors = [
            'primary'   => ['soft'=>'var(--primary-soft)',   'txt'=>'var(--primary)',   'shadow'=>'rgba(124,58,237,.15)'],
            'secondary' => ['soft'=>'var(--secondary-soft)', 'txt'=>'var(--secondary)', 'shadow'=>'rgba(245,158,11,.15)'],
            'green'     => ['soft'=>'var(--green-soft)',     'txt'=>'var(--green)',     'shadow'=>'rgba(5,150,105,.15)'],
            'rose'      => ['soft'=>'var(--rose-soft)',      'txt'=>'var(--rose)',      'shadow'=>'rgba(225,29,72,.15)'],
        ][$s['color']];
    @endphp
    <a href="{{ route($s['route']) }}" style="text-decoration:none;">
        <div class="card" style="padding:20px 22px; transition:all .2s; cursor:pointer;"
             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 32px {{ $colors['shadow'] }}';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
            <div style="font-size:24px; margin-bottom:8px;">{{ $s['emoji'] }}</div>
            <div style="font-size:36px; font-family:'Fraunces',serif; font-weight:900; color:var(--txt); line-height:1; margin-bottom:4px;">
                {{ $s['val'] }}
            </div>
            <div style="font-size:12.5px; font-weight:700; color:var(--txt-muted);">{{ $s['label'] }}</div>
        </div>
    </a>
@endforeach
</div>

{{-- Bottom grid --}}
<div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

    {{-- Quick links --}}
    <div class="card" style="padding:26px;">
        <div style="font-family:'Fraunces',serif; font-size:18px; font-weight:700; color:var(--txt); margin-bottom:18px;">
            Quick links
        </div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:10px;">
            @foreach([
                ['🚪','Rooms',     'admin.rooms.index'],
                ['📍','Starts',    'admin.origins.index'],
                ['✨','Items',     'admin.facilities.index'],
                ['↗️','Routes',    'admin.routes.index'],
                ['🏛️','Buildings', 'admin.buildings.index'],
                ['📐','Floors',    'admin.floors.index'],
            ] as [$emoji, $label, $route])
            <a href="{{ route($route) }}"
               style="display:flex; flex-direction:column; align-items:center; gap:6px; padding:16px 10px; background:var(--surface2); border-radius:12px; text-decoration:none; color:var(--txt-muted); font-size:13px; font-weight:700; transition:all .18s; text-align:center;"
               onmouseover="this.style.background='var(--primary-soft)'; this.style.color='var(--primary)'; this.style.transform='scale(1.04)';"
               onmouseout="this.style.background='var(--surface2)'; this.style.color='var(--txt-muted)'; this.style.transform='scale(1)';">
                <span style="font-size:22px;">{{ $emoji }}</span>
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Publish --}}
    <div class="card" style="padding:26px;">
        <div style="font-family:'Fraunces',serif; font-size:18px; font-weight:700; color:var(--txt); margin-bottom:6px;">
            Publish update 🚀
        </div>

        @if($latest)
            <div style="display:inline-flex; align-items:center; gap:8px; background:var(--green-soft); color:var(--green); padding:6px 14px; border-radius:999px; font-size:12.5px; font-weight:700; margin-bottom:14px;">
                ✅ Version {{ $latest->version }} live · {{ $latest->published_at?->diffForHumans() }}
            </div>
        @else
            <div style="color:var(--txt-muted); font-style:italic; font-size:13.5px; margin-bottom:14px;">
                No versions published yet!
            </div>
        @endif

        <form method="POST" action="{{ route('admin.publish') }}">
            @csrf
            <div class="field">
                <label>What changed?</label>
                <input type="text" name="note" placeholder="e.g. Added Block C rooms 🏫">
            </div>
            <button type="submit" class="btn btn-primary"
                    style="width:100%; justify-content:center;"
                    onclick="return confirm('Publish a new dataset version?')">
                🚀 Publish now
            </button>
        </form>
    </div>

</div>

@endsection
