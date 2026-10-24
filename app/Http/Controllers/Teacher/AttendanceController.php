<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Menampilkan daftar jadwal guru hari ini
    public function index()
    {
        $today = Carbon::now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dll
        $teacherId = Auth::id();

        // Ambil jadwal guru hari ini
        $schedules = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacherId)
            ->where('day', $today)
            ->orderBy('start_time')
            ->get();

        // Cek apakah sesi sudah dibuka untuk masing-masing jadwal hari ini
        $sessions = AttendanceSession::whereIn('schedule_id', $schedules->pluck('id'))
            ->where('date', Carbon::today())
            ->get()
            ->keyBy('schedule_id');

        return view('teacher.attendance.index', compact('schedules', 'sessions', 'today'));
    }

    // Membuka sesi presensi
    public function openSession(Schedule $schedule)
    {
        // Pastikan guru hanya bisa buka jadwalnya sendiri
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $session = AttendanceSession::firstOrCreate(
            ['schedule_id' => $schedule->id, 'date' => Carbon::today()],
            ['opened_by' => Auth::id()]
        );

        // Otomatis isi semua siswa dengan status 'alpa' jika sesi baru dibuat
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

    // Menampilkan form presensi untuk sesi tertentu
    public function show(AttendanceSession $session)
    {
        if ($session->schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        $session->load(['schedule.classroom', 'schedule.subject', 'records.student']);
        $records = $session->records;

        return view('teacher.attendance.show', compact('session', 'records'));
    }

    // Menyimpan perubahan presensi
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