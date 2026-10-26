<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AcademicYear;
use App\Models\CharacterGrade;

class GradebookController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();
        $student = Auth::user();
        $classroomId = $student->studentProfile?->classroom_id;

        if (!$classroomId) {
            return back()->withErrors(['error' => 'Anda belum dimasukkan ke kelas mana pun.']);
        }

        $schedules = Schedule::with('subject')
            ->where('classroom_id', $classroomId)
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->get();

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
        }

        $characterGrade = CharacterGrade::where('student_id', $student->id)
            ->where('academic_year', $currentYear)
            ->latest()
            ->first();

        return view('student.gradebook.index', compact('grades', 'characterGrade'));
    }

    public function print()
    {
        $currentYear = AcademicYear::current();
        $student = Auth::user();
        $classroomId = $student->studentProfile?->classroom_id;

        if (!$classroomId) {
            return back()->withErrors(['error' => 'Anda belum dimasukkan ke kelas mana pun.']);
        }

        $schedules = Schedule::with('subject')
            ->where('classroom_id', $classroomId)
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->get();

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
        }

        $characterGrade = CharacterGrade::where('student_id', $student->id)
            ->where('academic_year', $currentYear)
            ->latest()
            ->first();

        return view('student.gradebook.print', compact('grades', 'characterGrade', 'currentYear'));
    }
}