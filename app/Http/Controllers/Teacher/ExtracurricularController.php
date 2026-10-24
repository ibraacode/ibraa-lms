<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::where('advisor_id', Auth::id())->withCount('members')->get();
        return view('teacher.extracurriculars.index', compact('extracurriculars'));
    }

    public function show(Extracurricular $extracurricular)
    {
        if ($extracurricular->advisor_id !== Auth::id()) {
            abort(403);
        }
        $extracurricular->load(['members.student']);
        return view('teacher.extracurriculars.show', compact('extracurricular'));
    }

    public function grade(Request $request, Extracurricular $extracurricular)
    {
        if ($extracurricular->advisor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'scores' => 'nullable|array',
            'notes' => 'nullable|array',
        ]);

        foreach ($request->scores ?? [] as $memberId => $score) {
            ExtracurricularMember::where('id', $memberId)
                ->where('extracurricular_id', $extracurricular->id)
                ->update([
                    'score' => $score ?: null,
                    'notes' => $request->notes[$memberId] ?? null,
                ]);
        }

        return back()->with('success', 'Nilai ekskul berhasil disimpan.');
    }
}