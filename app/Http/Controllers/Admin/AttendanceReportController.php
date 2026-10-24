<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();
        
        $classroomId = $request->input('classroom_id');
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $report = null;

        if ($classroomId) {
            $classroom = Classroom::find($classroomId);
            
            // Ambil semua siswa di kelas ini
            $students = User::role('siswa')
                ->whereHas('studentProfile', function($q) use ($classroomId) {
                    $q->where('classroom_id', $classroomId);
                })
                ->orderBy('name')
                ->get();

            // Hitung rekap kehadiran per siswa untuk bulan & tahun yang dipilih
            $report = [];
            foreach ($students as $student) {
                $counts = [
                    'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0,
                ];

                $records = AttendanceRecord::whereHas('session', function($q) use ($month, $year) {
                    $q->whereMonth('date', $month)->whereYear('date', $year);
                })->where('student_id', $student->id)->get();

                foreach ($records as $r) {
                    $counts[$r->status] = ($counts[$r->status] ?? 0) + 1;
                }

                $report[] = [
                    'student' => $student,
                    'counts' => $counts,
                    'total' => array_sum($counts),
                ];
            }
        }

        return view('admin.attendance.report.index', compact('classrooms', 'classroomId', 'month', 'year', 'report'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ]);

        $classroom = Classroom::find($request->classroom_id);
        $month = (int) $request->month;   // <--- TAMBAHKAN INI
        $year = (int) $request->year;     // <--- TAMBAHKAN INI

        $students = User::role('siswa')
            ->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('classroom_id', $request->classroom_id);
            })
            ->orderBy('name')
            ->get();

        $report = [];
        foreach ($students as $student) {
            $counts = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0];

            $records = AttendanceRecord::whereHas('session', function ($q) use ($month, $year) {
                $q->whereMonth('date', $month)->whereYear('date', $year);
            })->where('student_id', $student->id)->get();

            foreach ($records as $r) {
                $counts[$r->status]++;
            }

            $report[] = [
                'student' => $student,
                'counts' => $counts,
                'total' => array_sum($counts),
            ];
        }

        return view('admin.attendance.report.print', compact('classroom', 'month', 'year', 'report'));
    }
}