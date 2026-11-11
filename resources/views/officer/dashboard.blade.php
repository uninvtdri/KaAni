@extends('layouts.app')

@section('content')
<h1>Officer Dashboard</h1>
<p class="lead">Check the weather risk per town, then submit an advisory for admin approval.</p>

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
        <div><a href="{{ route('officer.advisories.index') }}">My pending advisories</a></div>
    </div>
    <div class="card Low">
        <div class="num">{{ $approved }}</div>
        <div>My approved advisories</div>
    </div>
</div>

@include('partials.weather')
@endsection
