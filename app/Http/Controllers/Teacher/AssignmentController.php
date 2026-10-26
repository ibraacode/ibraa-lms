<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use App\Helpers\AcademicYear;

class AssignmentController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();

        $schedules = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', Auth::id())
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->get();

        $assignments = Assignment::with(['schedule.classroom', 'schedule.subject'])
            ->whereHas('schedule.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->whereHas('schedule', function ($q) {
                $q->where('teacher_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('teacher.assignments.index', compact('assignments', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:now',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        Assignment::create($request->all());

        return back()->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Assignment $assignment)
    {
        if ($assignment->schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $assignment->load('schedule.classroom');
        
        $students = \App\Models\User::role('siswa')
            ->whereHas('studentProfile', function($q) use ($assignment) {
                $q->where('classroom_id', $assignment->schedule->classroom_id);
            })
            ->with(['submissions' => function($q) use ($assignment) {
                $q->where('assignment_id', $assignment->id);
            }])
            ->get();

        return view('teacher.assignments.show', compact('assignment', 'students'));
    }

    public function grade(Request $request, Assignment $assignment)
    {
        if ($assignment->schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'scores' => 'nullable|array',
            'feedbacks' => 'nullable|array',
        ]);

        foreach ($data['scores'] as $studentId => $score) {
            Submission::updateOrCreate(
                ['assignment_id' => $assignment->id, 'student_id' => $studentId],
                [
                    'score' => $score ?: null,
                    'feedback' => $data['feedbacks'][$studentId] ?? null,
                ]
            );
        }
      
        AuditLog::record('UPDATE_GRADE', 'Memperbarui nilai untuk tugas: ' . $assignment->title . ' (' . $assignment->schedule->classroom->name . ')');
        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}