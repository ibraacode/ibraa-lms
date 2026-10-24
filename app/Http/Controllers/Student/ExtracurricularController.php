<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::with('advisor')->withCount('members')->get();
        $myMemberships = ExtracurricularMember::where('student_id', Auth::id())->pluck('extracurricular_id')->toArray();
        return view('student.extracurriculars.index', compact('extracurriculars', 'myMemberships'));
    }

    public function join(Extracurricular $extracurricular)
    {
        if (!$extracurricular->is_open) {
            return back()->withErrors(['error' => 'Pendaftaran ekskul ini sudah ditutup.']);
        }

        ExtracurricularMember::firstOrCreate([
            'extracurricular_id' => $extracurricular->id,
            'student_id' => Auth::id(),
        ]);

        return back()->with('success', 'Berhasil mendaftar ke ' . $extracurricular->name);
    }
}