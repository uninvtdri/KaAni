<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Services\RiskAssessmentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficerAdvisoryController extends Controller
{
    public function index(Request $request)
    {
        return view('officer.advisories', [
            'municipalities' => Municipality::orderBy('name')->get(),
            'crops' => RiskAssessmentService::CROPS,
            'advisories' => $request->user()->advisories()->with('municipality', 'reviewer')->latest()->get(),
        ]);
    }

    public function draft(Request $request, RiskAssessmentService $ai)
    {
        $data = $request->validate([
            'municipality_id' => ['required', 'exists:municipalities,id'],
            'crop' => ['required', Rule::in(RiskAssessmentService::CROPS)],
        ], [], ['municipality_id' => 'municipality']);

        $municipality = Municipality::find($data['municipality_id']);

        return back()->withInput($data + [
            'message' => $ai->draft($municipality, $data['crop']),
        ]);
    }

    public function store(Request $request, RiskAssessmentService $ai)
    {
        $data = $request->validate([
            'municipality_id' => ['required', 'exists:municipalities,id'],
            'crop' => ['required', Rule::in(RiskAssessmentService::CROPS)],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
        ], [], ['municipality_id' => 'municipality']);

        $municipality = Municipality::find($data['municipality_id']);

        $request->user()->advisories()->create($data + [
            'risk_level' => $ai->risk($municipality),
            'status' => 'pending',
        ]);

        return redirect()->route('officer.advisories.index')
            ->with('status', 'Advisory submitted. Waiting for admin approval.');
    }
}
