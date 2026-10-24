<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\CharacterGrade;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function index()
    {
        // Ambil semua kelas untuk dipilih
        $classrooms = Classroom::all();
        return view('teacher.character.index', compact('classrooms'));
    }

    public function showClassroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $semester = $request->semester;
        $academicYear = Setting::get('active_academic_year', '2026/2027');

        // Ambil semua siswa di kelas ini
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

        $academicYear = Setting::get('active_academic_year', '2026/2027');
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