<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InternshipLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InternshipController extends Controller
{
    public function index()
    {
        $logs = InternshipLog::where('student_id', Auth::id())->latest('date')->paginate(10);
        $todayLog = InternshipLog::where('student_id', Auth::id())->where('date', Carbon::today())->first();
        
        return view('student.internship.index', compact('logs', 'todayLog'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'photo' => 'required|string', // Base64 image
            'lat' => 'required|string',
            'long' => 'required|string',
            'activity' => 'required|string',
        ]);

        // Simpan foto Base64 ke storage
        $imageData = $request->photo;
        $imageName = 'pkl-in-' . Auth::id() . '-' . time() . '.png';
        $imagePath = 'internship/' . $imageName;
        
        // Decode base64
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        \Storage::disk('public')->put($imagePath, base64_decode($image));

        InternshipLog::updateOrCreate(
            ['student_id' => Auth::id(), 'date' => Carbon::today()],
            [
                'industry_placement_id' => 1, // Sementara hardcode, nanti ambil dari penempatan
                'check_in_time' => Carbon::now()->format('H:i:s'),
                'check_in_photo' => $imagePath,
                'check_in_lat' => $request->lat,
                'check_in_long' => $request->long,
                'activity' => $request->activity,
            ]
        );

        return back()->with('success', 'Presensi masuk PKL berhasil!');
    }

    public function checkOut(Request $request)
    {
        $log = InternshipLog::where('student_id', Auth::id())->where('date', Carbon::today())->first();
        
        if (!$log) {
            return back()->withErrors(['error' => 'Anda belum absen masuk hari ini.']);
        }

        $request->validate([
            'photo' => 'required|string',
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        $imageData = $request->photo;
        $imageName = 'pkl-out-' . Auth::id() . '-' . time() . '.png';
        $imagePath = 'internship/' . $imageName;
        
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        \Storage::disk('public')->put($imagePath, base64_decode($image));

        $log->update([
            'check_out_time' => Carbon::now()->format('H:i:s'),
            'check_out_photo' => $imagePath,
            'check_out_lat' => $request->lat,
            'check_out_long' => $request->long,
        ]);

        return back()->with('success', 'Presensi pulang PKL berhasil!');
    }
}