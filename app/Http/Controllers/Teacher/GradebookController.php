<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AcademicYear;

class GradebookController extends Controller
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

        return view('teacher.gradebook.index', compact('schedules'));
    }

    public function show(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $assignments = Assignment::where('schedule_id', $schedule->id)->orderBy('due_date')->get();

        $students = User::role('siswa')
            ->whereHas('studentProfile', function ($q) use ($schedule) {
                $q->where('classroom_id', $schedule->classroom_id);
            })
            ->with(['submissions' => function ($q) use ($assignments) {
                $q->whereIn('assignment_id', $assignments->pluck('id'));
            }])
            ->get();

        return view('teacher.gradebook.show', compact('schedule', 'assignments', 'students'));
    }
}