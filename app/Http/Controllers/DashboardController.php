<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\OtherIncome;
use App\Models\DailyAttendance;
use App\Models\Invoice;
use App\Models\Submission;
use App\Models\AttendanceRecord;
use App\Models\Schedule;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\AcademicYear;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasAnyRole(['super-admin', 'tu'])) {
            $data = $this->adminStats();
        } elseif ($user->hasRole('guru')) {
            $data = $this->teacherStats($user->id);
        } elseif ($user->hasRole('siswa')) {
            $data = $this->studentStats($user->id);
        }

        return view('dashboard', $data);
    }

    private function adminStats()
    {
        $currentYear = AcademicYear::current();
        $today = Carbon::today();

        // ================= KARTU RINGKASAN =================

        // Siswa aktif di T.A berjalan
        $totalStudents = User::role('siswa')
            ->whereHas('studentProfile.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })->count();
            
        $totalTeachers = User::role('guru')->count();
        
        $attendanceToday = DailyAttendance::where('date', $today)->count();
        
        // Pemasukan Bulan Ini (Disaring per Tahun Ajaran)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $incomeFromStudents = Payment::whereHas('invoice', function($q) use ($currentYear) {
            $q->where('academic_year', $currentYear);
        })->whereBetween('paid_at', [$startOfMonth, $endOfMonth])->sum('amount_paid');
        
        $incomeOther = OtherIncome::where('academic_year', $currentYear)
            ->whereBetween('received_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
            
        $incomeThisMonth = $incomeFromStudents + $incomeOther;
        
        // Tunggakan (Disaring per Tahun Ajaran)
        $totalInvoiced = Invoice::where('academic_year', $currentYear)
            ->where('status', '!=', 'exempt')
            ->sum('amount');
            
        $totalPaid = Payment::whereHas('invoice', function($q) use ($currentYear) {
            $q->where('academic_year', $currentYear);
        })->sum('amount_paid');
        
        $outstanding = $totalInvoiced - $totalPaid;


        // ================= GRAFIK KEUANGAN (6 BULAN) =================
        $financeChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $paid = Payment::whereHas('invoice', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })->whereBetween('paid_at', [$start, $end])->sum('amount_paid');
            
            $other = OtherIncome::where('academic_year', $currentYear)
                ->whereBetween('received_at', [$start, $end])
                ->sum('amount');
            
            $financeChart[] = [
                'label' => $month->isoFormat('MMM'),
                'total' => $paid + $other,
            ];
        }


        // ================= GRAFIK KEHADIRAN (7 HARI) =================
        $attendanceChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = DailyAttendance::where('date', $date)->count();
            $attendanceChart[] = [
                'label' => $date->isoFormat('ddd'),
                'count' => $count,
            ];
        }

        return compact(
            'totalStudents', 'totalTeachers', 'attendanceToday', 'incomeThisMonth', 'outstanding',
            'financeChart', 'attendanceChart'
        );
    }

    private function teacherStats($teacherId)
    {
        $currentYear = AcademicYear::current();
        
        // Ambil ID jadwal mengajar guru di T.A aktif
        $schedules = Schedule::where('teacher_id', $teacherId)
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })->pluck('id');
        
        $totalAssignments = Assignment::whereIn('schedule_id', $schedules)->count();
        
        $ungradedCount = Submission::whereHas('assignment', function($q) use ($schedules) {
            $q->whereIn('schedule_id', $schedules);
        })->whereNull('score')->count();

        return compact('totalAssignments', 'ungradedCount');
    }

    private function studentStats($studentId)
    {
        $currentYear = AcademicYear::current();
        
        // Kehadiran Siswa (Presensi per Mapel)
        $totalSessions = AttendanceRecord::where('student_id', $studentId)->count();
        $presentCount = AttendanceRecord::where('student_id', $studentId)->where('status', 'hadir')->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentCount / $totalSessions) * 100, 1) : 0;

        // Rata-rata Nilai
        $averageScore = Submission::where('student_id', $studentId)->whereNotNull('score')->avg('score');
        $averageScore = $averageScore ? round($averageScore, 1) : 0;

        // Tugas Belum Dikerjakan (Disaring T.A)
        $studentClassroomId = User::find($studentId)->studentProfile?->classroom_id;
        $pendingTasks = 0;
        
        if ($studentClassroomId) {
            $pendingTasks = Assignment::whereHas('schedule.classroom', function($q) use ($studentClassroomId, $currentYear) {
                $q->where('classroom_id', $studentClassroomId)
                  ->where('academic_year', $currentYear);
            })->whereDoesntHave('submissions', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })->where('due_date', '>', now())->count();
        }

        return compact('attendanceRate', 'averageScore', 'pendingTasks');
    }
}