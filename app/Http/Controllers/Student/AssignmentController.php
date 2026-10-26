<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AcademicYear;

class AssignmentController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();
        $student = Auth::user();
        $classroomId = $student->studentProfile?->classroom_id;

        if (!$classroomId) {
            return back()->withErrors(['error' => 'Anda belum dimasukkan ke kelas mana pun. Hubungi Admin.']);
        }

        $assignments = Assignment::with(['schedule.subject', 'schedule.teacher', 'submissions' => function($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->whereHas('schedule.classroom', function ($q) use ($classroomId, $currentYear) {
                $q->where('classroom_id', $classroomId)
                  ->where('academic_year', $currentYear);
            })
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        return view('student.assignments.index', compact('assignments'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'answer_text' => 'required|string',
        ]);

        if (now()->greaterThan($assignment->due_date)) {
            return back()->withErrors(['error' => 'Tenggat waktu sudah lewat.']);
        }

        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => Auth::id()],
            [
                'answer_text' => $request->answer_text,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }
}