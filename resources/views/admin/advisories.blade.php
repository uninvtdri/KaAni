@extends('layouts.app')

@section('content')
<h1>Review Advisories</h1>

@error('review_note') <p class="alert error">{{ $message }}</p> @enderror

<h2>Waiting for approval ({{ $pending->count() }})</h2>

@forelse($pending as $a)
    <div class="box">
        <span class="risk {{ $a->risk_level }}">{{ $a->risk_level }}</span>
        <strong>{{ $a->crop }} · {{ $a->municipality->name }}</strong>
        <p class="message">{{ $a->message }}</p>
        <small>Submitted by {{ $a->user->name }} · {{ $a->created_at->format('M d, Y g:i A') }}</small>

        <form method="POST" class="row review" novalidate>
            @csrf
            @method('PATCH')
            <input type="text" name="review_note" placeholder="Note (required when rejecting)">
            <button formaction="{{ route('admin.advisories.approve', $a) }}">Approve</button>
            <button formaction="{{ route('admin.advisories.reject', $a) }}" class="danger">Reject</button>
        </form>
    </div>
@empty
    <p>No advisories waiting for approval.</p>
@endforelse

<h2>Reviewed</h2>

@forelse($reviewed as $a)
    <div class="box">
        <span class="status {{ $a->status }}">{{ ucfirst($a->status) }}</span>
        <span class="risk {{ $a->risk_level }}">{{ $a->risk_level }}</span>
        <strong>{{ $a->crop }} · {{ $a->municipality->name }}</strong>
        <p class="message">{{ $a->message }}</p>
        @if($a->review_note)
            <p class="note">Note: {{ $a->review_note }}</p>
        @endif
        <small>By {{ $a->user->name }} · {{ ucfirst($a->status) }} by {{ $a->reviewer->name }} on {{ $a->reviewed_at->format('M d, Y g:i A') }}</small>
    </div>
@empty
    <p>No reviewed advisories yet.</p>
@endforelse
@endsection
