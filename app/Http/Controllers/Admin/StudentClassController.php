<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class StudentClassController extends Controller
{
        public function index()
    {
        $currentYear = \App\Helpers\AcademicYear::current();

        // Ambil HANYA siswa yang terdaftar di kelas tahun ajaran aktif
        $students = User::role('siswa')
            ->whereHas('studentProfile.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->latest()
            ->paginate(20);

        // Ambil HANYA kelas di tahun ajaran aktif
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

        // Update atau buat profil baru langsung ke tabel
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