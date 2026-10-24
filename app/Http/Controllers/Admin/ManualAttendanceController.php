<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ManualAttendanceController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        $classrooms = Classroom::withCount('students')->get();
        return view('admin.attendance.manual.index', compact('classrooms'));
    }

    // Menampilkan jadwal dan form presensi untuk kelas tertentu
    public function showClassroom(Classroom $classroom)
    {
        $today = Carbon::now()->locale('id')->isoFormat('dddd');
        $schedules = Schedule::with(['subject', 'teacher'])
            ->where('classroom_id', $classroom->id)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();

        // Cek sesi yang sudah ada hari ini
        $existingSessions = AttendanceSession::whereIn('schedule_id', $schedules->pluck('id'))
            ->where('date', Carbon::today())
            ->get()
            ->keyBy('schedule_id');

        return view('admin.attendance.manual.classroom', compact('classroom', 'schedules', 'existingSessions', 'today'));
    }

    // Menyimpan presensi manual
    public function storeSession(Request $request, Classroom $classroom, Schedule $schedule)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,sakit,alpa',
            'note' => 'nullable|array',
        ]);

        $session = AttendanceSession::firstOrCreate(
            ['schedule_id' => $schedule->id, 'date' => Carbon::today()],
            ['opened_by' => Auth::id()]
        );

        foreach ($request->status as $studentId => $status) {
            AttendanceRecord::updateOrCreate(
                ['attendance_session_id' => $session->id, 'student_id' => $studentId],
                ['status' => $status, 'note' => $request->note[$studentId] ?? null]
            );
        }

        AuditLog::record('MANUAL_ATTENDANCE', 'Admin mengisi presensi manual untuk kelas ' . $classroom->name . ' mapel ' . $schedule->subject->name);
        return back()->with('success', 'Presensi berhasil disimpan.');
    }
}