<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    // Halaman manajemen tahun ajaran
    public function index()
    {
        $academicYears = Classroom::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->get();
        $currentYear = \App\Helpers\AcademicYear::current();
        
        // Hitung siswa aktif (yang kelasnya di tahun ini)
        $activeStudents = User::role('siswa')->whereHas('studentProfile', function($q) use ($currentYear) {
            $q->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })->where('status', 'aktif');
        })->count();
        
        return view('admin.academic-year.index', compact('academicYears', 'currentYear', 'activeStudents'));
    }

    // Proses naik kelas massal (X -> XI, XI -> XII)
    public function promote(Request $request)
    {
        $request->validate([
            'from_year' => 'required|string',
            'to_year' => 'required|string',
        ]);

        $fromYear = $request->from_year;
        $toYear = $request->to_year;

        // Logika: Ambil siswa aktif di tahun $fromYear, lalu buat kelas baru di $toYear
        // Ini adalah versi sederhana. Di produksi, Anda mungkin ingin membuat form pemetaan per kelas.
        
        DB::beginTransaction();
        try {
            // Ambil semua kelas lama di tahun $fromYear
            $oldClassrooms = Classroom::where('academic_year', $fromYear)->get();

            foreach ($oldClassrooms as $oldClass) {
                // Jika kelas XII, biarkan saja atau tandai lulus (di handle di method graduate)
                if ($oldClass->grade_level == 'XII') continue;

                // Buat kelas baru untuk tahun berikutnya (X -> XI, XI -> XII)
                $newGradeLevel = $oldClass->grade_level == 'X' ? 'XI' : 'XII';
                $newClassName = str_replace($oldClass->grade_level, $newGradeLevel, $oldClass->name); // Contoh: X IPA 1 -> XI IPA 1

                $newClass = Classroom::firstOrCreate([
                    'name' => $newClassName,
                    'academic_year' => $toYear,
                ], [
                    'grade_level' => $newGradeLevel,
                    'major_code' => $oldClass->major_code,
                ]);

                // Pindahkan siswa dari kelas lama ke kelas baru
                StudentProfile::where('classroom_id', $oldClass->id)
                    ->where('status', 'aktif')
                    ->update([
                        'classroom_id' => $newClass->id,
                        // Status tetap aktif
                    ]);
            }

            DB::commit();
            return back()->with('success', 'Kenaikan kelas berhasil! Siswa telah dipindahkan ke kelas baru di tahun ajaran ' . $toYear);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal naik kelas: ' . $e->getMessage()]);
        }
    }

    // Meluluskan siswa kelas XII
    public function graduate(Request $request)
    {
        $request->validate([
            'graduation_year' => 'required|string',
        ]);

        $currentYear = $request->graduation_year;

        // Ambil semua kelas XII di tahun yang dipilih
        $classXII = Classroom::where('academic_year', $currentYear)
            ->where('grade_level', 'XII')
            ->pluck('id');

        // Tandai siswa di kelas XII menjadi 'lulus'
        StudentProfile::whereIn('classroom_id', $classXII)->update([
            'status' => 'lulus',
            'graduation_year' => $currentYear,
        ]);

        return back()->with('success', 'Siswa kelas XII berhasil diluluskan. Data mereka akan diarsipkan.');
    }
}