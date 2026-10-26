<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Helpers\AcademicYear;

class StudentClassController extends Controller
{
        public function index()
    {
        $currentYear = \App\Helpers\AcademicYear::current();

        // Tampilkan siswa yang:
        // 1. Statusnya 'aktif' (belum lulus)
        // 2. Punya kelas di tahun ini, ATAU belum punya kelas sama sekali (null)
        $students = User::role('siswa')
            ->whereHas('studentProfile', function($q) use ($currentYear) {
                $q->where(function($subQ) use ($currentYear) {
                    // Belum punya kelas
                    $subQ->whereNull('classroom_id')
                         // ATAU punya kelas di tahun ajaran ini
                         ->orWhereHas('classroom', function($q2) use ($currentYear) {
                             $q2->where('academic_year', $currentYear);
                         });
                })
                ->where('status', 'aktif'); // Hanya yang aktif
            })
            ->latest()
            ->paginate(20);

        // Ambil kelas hanya di tahun ajaran aktif
        $classrooms = Classroom::where('academic_year', $currentYear)->orderBy('name')->get();

        $profiles = StudentProfile::whereIn('user_id', $students->pluck('id'))
                                  ->get()
                                  ->keyBy('user_id');

        return view('admin.students.assign', compact('students', 'classrooms', 'profiles'));
    }

    public function update(Request $request, User $student)
    {
        $request->validate([
            'classroom_id' => 'nullable|exists:classrooms,id'
        ]);

        $profile = StudentProfile::firstOrNew(['user_id' => $student->id]);
        
        if (!$profile->exists) {
            $profile->full_name = $student->name;
            $profile->gender = 'L';
        }

        $profile->classroom_id = $request->input('classroom_id') ?: null;
        $profile->save();
      
        AuditLog::record('MOVE_STUDENT', 'Memindahkan siswa ' . $student->name . ' ke kelas ID: ' . ($request->classroom_id ?: 'Tanpa Kelas'));
        return back()->with('success', 'Kelas berhasil diperbarui.');
    }
}