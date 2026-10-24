<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\InternshipPlacement;
use App\Models\InternshipLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipController extends Controller
{
    // Daftar siswa bimbingan
    public function index()
    {
        $placements = InternshipPlacement::with(['student', 'industry'])
            ->where('teacher_id', Auth::id())
            ->get();

        return view('teacher.internship.index', compact('placements'));
    }

    // Detail siswa & penilaian
    public function show($studentId)
    {
        $placement = InternshipPlacement::where('student_id', $studentId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $logs = InternshipLog::where('student_id', $studentId)->orderBy('date')->get();
        
        return view('teacher.internship.show', compact('placement', 'logs'));
    }

    // Simpan penilaian
    public function grade(Request $request, $studentId)
    {
        $placement = InternshipPlacement::where('student_id', $studentId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'final_score' => 'required|integer|min:0|max:100',
            'final_notes' => 'nullable|string',
            'log_scores' => 'nullable|array',
            'log_scores.*' => 'nullable|integer|min:0|max:100',
            'log_notes' => 'nullable|array',
        ]);

        // Update nilai akhir
        $placement->update([
            'final_score' => $request->final_score,
            'final_notes' => $request->final_notes,
        ]);

        // Update nilai per jurnal (jika ada)
        foreach ($request->log_scores ?? [] as $logId => $score) {
            InternshipLog::where('id', $logId)->where('student_id', $studentId)->update([
                'score' => $score ?: null,
                'teacher_notes' => $request->log_notes[$logId] ?? null,
            ]);
        }

        return back()->with('success', 'Penilaian PKL berhasil disimpan.');
    }

    // Cetak Laporan
    public function print($studentId)
    {
        $placement = InternshipPlacement::where('student_id', $studentId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $logs = InternshipLog::where('student_id', $studentId)->orderBy('date')->get();
        
        return view('teacher.internship.print', compact('placement', 'logs'));
    }
}