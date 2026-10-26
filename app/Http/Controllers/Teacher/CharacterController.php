<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\CharacterGrade;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AcademicYear;

class CharacterController extends Controller
{
        public function index()
    {
        $currentYear = \App\Helpers\AcademicYear::current();
        
        // Cek apakah user adalah Super Admin / TU (bisa lihat semua)
        if (auth()->user()->hasAnyRole(['super-admin', 'tu'])) {
            $classrooms = Classroom::where('academic_year', $currentYear)->get();
        } else {
            // Jika Guru, HANYA kelas yang dia wali-kan
            $classrooms = Classroom::where('academic_year', $currentYear)
                ->where('homeroom_teacher_id', auth()->id())
                ->get();
        }
        
        return view('teacher.character.index', compact('classrooms'));
    }

    public function showClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $semester = $request->semester;
        $academicYear = AcademicYear::current();

        $students = User::role('siswa')
            ->whereHas('studentProfile', function ($q) use ($classroom) {
                $q->where('classroom_id', $classroom->id);
            })
            ->with(['characterGrades' => function ($q) use ($academicYear, $semester) {
                $q->where('academic_year', $academicYear)->where('semester', $semester);
            }])
            ->orderBy('name')
            ->get();

        return view('teacher.character.show', compact('classroom', 'students', 'semester', 'academicYear'));
    }

    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap',
            'spiritual_grade' => 'nullable|array',
            'social_grade' => 'nullable|array',
        ]);

        $academicYear = AcademicYear::current();
        $semester = $request->semester;

        foreach ($request->spiritual_grade as $studentId => $spiritual) {
            CharacterGrade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                ],
                [
                    'spiritual_grade' => $spiritual ?: null,
                    'spiritual_notes' => $request->spiritual_notes[$studentId] ?? null,
                    'social_grade' => $request->social_grade[$studentId] ?? null,
                    'social_notes' => $request->social_notes[$studentId] ?? null,
                    'graded_by' => Auth::id(),
                ]
            );
        }

        AuditLog::record('CHARACTER_GRADE', 'Menyimpan nilai karakter untuk kelas ' . $classroom->name . ' semester ' . $semester);
        return back()->with('success', 'Nilai karakter berhasil disimpan.');
    }
}