<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // Rekap per status
        $summary = [
            'hadir' => AttendanceRecord::where('student_id', $studentId)->where('status', 'hadir')->count(),
            'izin' => AttendanceRecord::where('student_id', $studentId)->where('status', 'izin')->count(),
            'sakit' => AttendanceRecord::where('student_id', $studentId)->where('status', 'sakit')->count(),
            'alpa' => AttendanceRecord::where('student_id', $studentId)->where('status', 'alpa')->count(),
        ];

        // Detail riwayat
        $records = AttendanceRecord::with(['session.schedule.subject', 'session.schedule.classroom'])
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(15);

        return view('student.attendance.index', compact('summary', 'records'));
    }
}