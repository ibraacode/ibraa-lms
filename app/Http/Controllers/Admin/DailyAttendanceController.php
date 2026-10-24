<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendanceToken;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DailyAttendanceController extends Controller
{
    // Halaman untuk menampilkan QR Code
    public function qrCodes()
    {
        // Ambil token aktif, atau buat baru jika belum ada
        $tokenIn = DailyAttendanceToken::where('type', 'in')->where('is_active', true)->first();
        if (!$tokenIn) {
            $tokenIn = DailyAttendanceToken::create([
                'token' => Str::random(30),
                'type' => 'in',
                'is_active' => true
            ]);
        }

        $tokenOut = DailyAttendanceToken::where('type', 'out')->where('is_active', true)->first();
        if (!$tokenOut) {
            $tokenOut = DailyAttendanceToken::create([
                'token' => Str::random(30),
                'type' => 'out',
                'is_active' => true
            ]);
        }

        return view('admin.attendance.qr', compact('tokenIn', 'tokenOut'));
    }

    // Halaman untuk melihat rekap kehadiran hari ini
    public function dailyRecap()
    {
        $today = date('Y-m-d');
        $attendances = DailyAttendance::with('student')
            ->where('date', $today)
            ->latest()
            ->get();

        return view('admin.attendance.recap', compact('attendances', 'today'));
    }
}