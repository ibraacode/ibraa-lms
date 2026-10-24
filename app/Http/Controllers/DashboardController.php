<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\OtherIncome;
use App\Models\DailyAttendance;
use App\Models\Invoice;
use App\Models\Submission;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    // ================= STATISTIK ADMIN =================
    private function adminStats()
    {

      $currentYear = \App\Helpers\AcademicYear::current();
        $today = Carbon::today();

        $totalStudents = User::role('siswa')
            ->whereHas('studentProfile.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })->count();

      
        $today = Carbon::today();

        // Kartu Ringkasan
        $totalStudents = User::role('siswa')->count();
        $totalTeachers = User::role('guru')->count();
        
        // Kehadiran hari ini
        $attendanceToday = DailyAttendance::where('date', $today)->count();
        
        // Pemasukan Bulan Ini (Siswa + Lainnya)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $incomeFromStudents = Payment::whereBetween('paid_at', [$startOfMonth, $endOfMonth])->sum('amount_paid');
        $incomeOther = OtherIncome::whereBetween('received_at', [$startOfMonth, $endOfMonth])->sum('amount');
        $incomeThisMonth = $incomeFromStudents + $incomeOther;
        
        // Tunggakan
        $totalInvoiced = Invoice::where('status', '!=', 'exempt')->sum('amount');
        $totalPaid = Payment::sum('amount_paid');
        $outstanding = $totalInvoiced - $totalPaid;

        // Grafik Keuangan 6 Bulan Terakhir
        $financeChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $paid = Payment::whereBetween('paid_at', [$start, $end])->sum('amount_paid');
            $other = OtherIncome::whereBetween('received_at', [$start, $end])->sum('amount');
            
            $financeChart[] = [
                'label' => $month->isoFormat('MMM'),
                'total' => $paid + $other,
            ];
        }

        // Grafik Kehadiran 7 Hari Terakhir
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

    // ================= STATISTIK GURU =================
    private function teacherStats($teacherId)
    {
        $schedules = \App\Models\Schedule::where('teacher_id', $teacherId)->pluck('id');
        
        // Total tugas dan berapa yang sudah dinilai
        $totalAssignments = \App\Models\Assignment::whereIn('schedule_id', $schedules)->count();
        
        // Tugas yang belum dinilai (ada submission tapi score null)
        $ungradedCount = Submission::whereHas('assignment', function($q) use ($schedules) {
            $q->whereIn('schedule_id', $schedules);
        })->whereNull('score')->count();

        return compact('totalAssignments', 'ungradedCount');
    }

    // ================= STATISTIK SISWA =================
    private function studentStats($studentId)
    {
        // Persentase Kehadiran (dari total sesi yang pernah ada)
        $totalSessions = AttendanceRecord::where('student_id', $studentId)->count();
        $presentCount = AttendanceRecord::where('student_id', $studentId)->where('status', 'hadir')->count();
        
        $attendanceRate = $totalSessions > 0 ? round(($presentCount / $totalSessions) * 100, 1) : 0;

        // Rata-rata Nilai
        $averageScore = Submission::where('student_id', $studentId)->whereNotNull('score')->avg('score');
        $averageScore = $averageScore ? round($averageScore, 1) : 0;

        // Tugas belum dikerjakan
        $studentClassroomId = \App\Models\User::find($studentId)->studentProfile?->classroom_id;
        $pendingTasks = 0;
        
        if ($studentClassroomId) {
            $pendingTasks = \App\Models\Assignment::whereHas('schedule', function($q) use ($studentClassroomId) {
                $q->where('classroom_id', $studentClassroomId);
            })->whereDoesntHave('submissions', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })->where('due_date', '>', now())->count();
        }

        return compact('attendanceRate', 'averageScore', 'pendingTasks');
    }
}