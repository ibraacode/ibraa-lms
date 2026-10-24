<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradebookController extends Controller
{
    // Menampilkan daftar kelas yang diampu guru
    public function index()
    {
        $schedules = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', Auth::id())
            ->get();

        return view('teacher.gradebook.index', compact('schedules'));
    }

    // Menampilkan rekap nilai untuk satu jadwal (kelas & mapel)
    public function show(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Ambil semua tugas untuk jadwal ini
        $assignments = Assignment::where('schedule_id', $schedule->id)->orderBy('due_date')->get();

        // Ambil semua siswa di kelas ini beserta nilai tugas mereka
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