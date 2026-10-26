<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\AcademicYear;

class AttendanceController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();
        $today = Carbon::now()->locale('id')->isoFormat('dddd');
        $teacherId = Auth::id();

        $schedules = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacherId)
            ->where('day', $today)
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->orderBy('start_time')
            ->get();

        $sessions = AttendanceSession::whereIn('schedule_id', $schedules->pluck('id'))
            ->where('date', Carbon::today())
            ->get()
            ->keyBy('schedule_id');

        return view('teacher.attendance.index', compact('schedules', 'sessions', 'today'));
    }

    public function openSession(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $session = AttendanceSession::firstOrCreate(
            ['schedule_id' => $schedule->id, 'date' => Carbon::today()],
            ['opened_by' => Auth::id()]
        );

        if ($session->wasRecentlyCreated) {
            $students = $schedule->classroom->students;
            foreach ($students as $student) {
                AttendanceRecord::create([
                    'attendance_session_id' => $session->id,
                    'student_id' => $student->user_id,
                    'status' => 'alpa',
                ]);
            }
        }

        return redirect()->route('teacher.attendance.show', $session->id);
    }

    public function show(AttendanceSession $session)
    {
        if ($session->schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $session->load(['schedule.classroom', 'schedule.subject', 'records.student']);
        $records = $session->records;

        return view('teacher.attendance.show', compact('session', 'records'));
    }

    public function update(Request $request, AttendanceSession $session)
    {
        if ($session->schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,sakit,alpa',
            'note' => 'nullable|array',
        ]);

        foreach ($data['status'] as $recordId => $status) {
            AttendanceRecord::where('id', $recordId)
                ->where('attendance_session_id', $session->id)
                ->update([
                    'status' => $status,
                    'note' => $data['note'][$recordId] ?? null
                ]);
        }

        return back()->with('success', 'Presensi berhasil disimpan.');
    }
}