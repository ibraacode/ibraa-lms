<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\AcademicYear;

class AcademicController extends Controller
{
    // ================= MAPEL =================
    public function subjects()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('admin.academic.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:subjects,code|max:20',
            'name' => 'required|string|max:255',
            'major_code' => 'nullable|string|max:20',
        ]);

        Subject::create($request->all());
        return back()->with('success', 'Mata pelajaran ditambahkan.');
    }

    // ================= KELAS =================
    public function classrooms()
    {
        $currentYear = AcademicYear::current();
        
        $classrooms = Classroom::with('homeroomTeacher')
            ->where('academic_year', $currentYear)
            ->orderBy('name')
            ->paginate(10);
            
        $teachers = User::role('guru')->get();
        
        return view('admin.academic.classrooms', compact('classrooms', 'teachers', 'currentYear'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'grade_level' => 'required|string|max:5',
            'major_code' => 'required|string|max:20|exists:majors,code',
            'academic_year' => 'required|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ], [
            'major_code.required' => 'Jurusan wajib dipilih.',
            'major_code.exists' => 'Jurusan tidak valid.',
        ]);

        $gradeLevel = $request->grade_level;
        $majorCode = strtoupper($request->major_code);
        $academicYear = $request->academic_year;

        // Auto-generate nama kelas dengan mencari urutan tertinggi
        $baseName = trim($gradeLevel . ' ' . $majorCode);
        
        // Cari semua kelas dengan prefix sama (misal: "X TKJ")
        $existingClasses = Classroom::where('academic_year', $academicYear)
            ->where('name', 'like', $baseName . ' %')
            ->pluck('name');

        // Ekstrak angka urutan tertinggi
        $maxSequence = 0;
        foreach ($existingClasses as $name) {
            // Ambil angka terakhir dari nama (misal: "X TKJ 2" -> 2)
            if (preg_match('/\s(\d+)$/', $name, $matches)) {
                $seq = (int) $matches[1];
                if ($seq > $maxSequence) {
                    $maxSequence = $seq;
                }
            }
        }

        $nextSequence = $maxSequence + 1;
        $className = $baseName . ' ' . $nextSequence;

        Classroom::create([
            'name' => $className,
            'grade_level' => $gradeLevel,
            'major_code' => $majorCode,
            'academic_year' => $academicYear,
            'homeroom_teacher_id' => $request->homeroom_teacher_id,
        ]);

        return back()->with('success', 'Kelas ' . $className . ' berhasil dibuat.');
    }

    public function updateHomeroom(Request $request, Classroom $classroom)
    {
        $request->validate([
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        $classroom->update([
            'homeroom_teacher_id' => $request->homeroom_teacher_id,
        ]);

        return back()->with('success', 'Wali Kelas untuk ' . $classroom->name . ' berhasil diperbarui.');
    }

    public function destroyClassroom(Classroom $classroom)
    {
        // Cek apakah ada siswa di kelas ini
        if ($classroom->students()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak bisa menghapus kelas yang masih memiliki siswa.']);
        }

        $className = $classroom->name;
        $classroom->delete();
        return back()->with('success', 'Kelas ' . $className . ' berhasil dihapus.');
    }

    // ================= JADWAL =================
    public function schedules()
    {
        $currentYear = AcademicYear::current();

        $schedules = Schedule::with(['classroom', 'subject', 'teacher'])
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->latest()
            ->paginate(15);
            
        $classrooms = Classroom::where('academic_year', $currentYear)->get();
        $subjects = Subject::all();
        $teachers = User::role('guru')->get();

        return view('admin.academic.schedules', compact('schedules', 'classrooms', 'subjects', 'teachers'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($request->all());
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }
}