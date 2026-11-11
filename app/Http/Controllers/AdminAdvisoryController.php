<?php

namespace App\Http\Controllers;

use App\Models\Advisory;
use Illuminate\Http\Request;

class AdminAdvisoryController extends Controller
{
    public function index()
    {
        return view('admin.advisories', [
            'pending' => Advisory::with('municipality', 'user')->where('status', 'pending')->oldest()->get(),
            'reviewed' => Advisory::with('municipality', 'user', 'reviewer')->where('status', '!=', 'pending')->latest('reviewed_at')->get(),
        ]);
    }

    public function approve(Request $request, Advisory $advisory)
    {
        return $this->review($request, $advisory, 'approved');
    }

    public function reject(Request $request, Advisory $advisory)
    {
        $request->validate([
            'review_note' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'review_note.required' => 'Please write a reason for rejecting the advisory.',
        ]);

        return $this->review($request, $advisory, 'rejected');
    }

    private function review(Request $request, Advisory $advisory, string $status)
    {
        if ($advisory->status !== 'pending') {
            return back()->withErrors(['review_note' => 'This advisory was already reviewed.']);
        }

        $advisory->update([
            'status' => $status,
            'review_note' => $request->input('review_note'),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('status', "Advisory {$status}.");
    }
}
