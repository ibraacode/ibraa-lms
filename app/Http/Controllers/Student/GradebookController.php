<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class GradebookController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $classroomId = $student->studentProfile?->classroom_id;

        if (!$classroomId) {
            return back()->withErrors(['error' => 'Anda belum dimasukkan ke kelas mana pun.']);
        }

        // Ambil semua jadwal (mata pelajaran) di kelas siswa ini
        $schedules = Schedule::with('subject')->where('classroom_id', $classroomId)->get();

        // Hitung rata-rata nilai per mata pelajaran
        $grades = [];
        foreach ($schedules as $sch) {
            $submissions = Submission::where('student_id', $student->id)
                ->whereHas('assignment', function ($q) use ($sch) {
                    $q->where('schedule_id', $sch->id);
                })
                ->whereNotNull('score')
                ->get();

            $avg = $submissions->avg('score');

            $grades[] = [
                'subject' => $sch->subject->name,
                'teacher' => $sch->teacher->name ?? '-',
                'assignments_count' => $submissions->count(),
                'average' => $avg ? round($avg, 1) : null,
            ];

                  // Ambil Nilai Karakter
        $academicYear = \App\Models\Setting::get('active_academic_year', '2026/2027');
        $characterGrade = \App\Models\CharacterGrade::where('student_id', $student->id)
            ->where('academic_year', $academicYear)
            ->latest()
            ->first();

        return view('student.gradebook.index', compact('grades', 'characterGrade'));
        }
    }
}