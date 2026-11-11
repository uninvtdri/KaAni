@extends('layouts.app')

@section('content')
<h1>My Advisories</h1>

<div class="box">
    <h2>New advisory</h2>

    <form method="POST" action="{{ route('officer.advisories.store') }}" novalidate>
        @csrf
        <div class="row">
            <select name="municipality_id">
                <option value="">Municipality</option>
                @foreach($municipalities as $m)
                    <option value="{{ $m->id }}" @selected(old('municipality_id') == $m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
            <select name="crop">
                @foreach($crops as $c)
                    <option @selected(old('crop') === $c)>{{ $c }}</option>
                @endforeach
            </select>
            <button formaction="{{ route('officer.advisories.draft') }}" class="secondary">Generate AI draft</button>
        </div>
        @error('municipality_id') <p class="field-error">{{ $message }}</p> @enderror
        @error('crop') <p class="field-error">{{ $message }}</p> @enderror

        <label>Advisory message</label>
        <textarea name="message" rows="6" placeholder="Click Generate AI draft, or write your own advisory here.">{{ old('message') }}</textarea>
        @error('message') <p class="field-error">{{ $message }}</p> @enderror

        <button>Submit for approval</button>
    </form>
</div>

<h2>Submitted by me</h2>

@forelse($advisories as $a)
    <div class="box">
        <span class="status {{ $a->status }}">{{ ucfirst($a->status) }}</span>
        <span class="risk {{ $a->risk_level }}">{{ $a->risk_level }}</span>
        <strong>{{ $a->crop }} · {{ $a->municipality->name }}</strong>
        <p class="message">{{ $a->message }}</p>
        @if($a->review_note)
            <p class="note">Admin note: {{ $a->review_note }}</p>
        @endif
        <small>
            Submitted {{ $a->created_at->format('M d, Y g:i A') }}
            @if($a->reviewer) · Reviewed by {{ $a->reviewer->name }} @endif
        </small>
    </div>
@empty
    <p>You have not submitted any advisories yet.</p>
@endforelse
@endsection
