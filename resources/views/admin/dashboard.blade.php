@extends('layouts.app')

@section('content')
<h1>Admin Dashboard</h1>
<p class="lead">Provincial view of weather risk and advisories submitted by municipal officers.</p>

<div class="cards">
    <div class="card High">
        <div class="num">{{ $municipalities->where('risk', 'High')->count() }}</div>
        <div>High risk towns</div>
    </div>
    <div class="card Moderate">
        <div class="num">{{ $municipalities->where('risk', 'Moderate')->count() }}</div>
        <div>Moderate risk towns</div>
    </div>
    <div class="card">
        <div class="num">{{ $pending }}</div>
        <div><a href="{{ route('admin.advisories.index') }}">Advisories to review</a></div>
    </div>
    <div class="card Low">
        <div class="num">{{ $approved }}</div>
        <div>Approved advisories</div>
    </div>
</div>

@include('partials.weather')
@endsection
