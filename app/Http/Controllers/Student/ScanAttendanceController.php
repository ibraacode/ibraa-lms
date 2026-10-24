<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DailyAttendanceToken;
use App\Models\DailyAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanAttendanceController extends Controller
{
    public function index()
    {
        return view('student.scan.index');
    }

    public function process(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $token = DailyAttendanceToken::where('token', $request->token)->where('is_active', true)->first();

        if (!$token) {
            return back()->withErrors(['error' => 'QR Code tidak valid atau sudah kedaluwarsa.']);
        }

        $studentId = Auth::id();
        $today = date('Y-m-d');
        $now = date('H:i:s');

        $attendance = DailyAttendance::firstOrCreate(
            ['student_id' => $studentId, 'date' => $today],
            ['status' => 'hadir']
        );

        if ($token->type === 'in') {
            if ($attendance->check_in_time) {
                return back()->withErrors(['error' => 'Anda sudah absen masuk hari ini pada jam ' . $attendance->check_in_time]);
            }
            $attendance->update(['check_in_time' => $now]);
            return back()->with('success', 'Absen masuk berhasil pada jam ' . $now);
        } 
        
        if ($token->type === 'out') {
            if (!$attendance->check_in_time) {
                return back()->withErrors(['error' => 'Anda belum absen masuk hari ini.']);
            }
            if ($attendance->check_out_time) {
                return back()->withErrors(['error' => 'Anda sudah absen pulang hari ini.']);
            }
            $attendance->update(['check_out_time' => $now]);
            return back()->with('success', 'Absen pulang berhasil pada jam ' . $now);
        }

        return back()->withErrors(['error' => 'Terjadi kesalahan pada sistem QR.']);
    }
}