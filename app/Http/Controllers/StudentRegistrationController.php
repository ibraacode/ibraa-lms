<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage;

class StudentRegistrationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        if (!$profile) {
            abort(404, 'Profil siswa tidak ditemukan.');
        }

        // Jika sudah daftar ulang, redirect ke dashboard
        if ($profile->registration_status === 'completed') {
            return redirect()->route('dashboard')->with('success', 'Anda sudah menyelesaikan daftar ulang.');
        }

        return view('student.registration', compact('user', 'profile'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        if ($profile->registration_status === 'completed') {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'nik' => 'nullable|string|max:20',
            'kk_number' => 'nullable|string|max:20',
            'religion' => 'required|string',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'parent_phone' => 'required|string',
            
            // Upload Dokumen
            'document_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'document_akta' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Update profil
        $profile->update([
            'address' => $request->address,
            'nik' => $request->nik,
            'kk_number' => $request->kk_number,
            'religion' => $request->religion,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'father_name' => $request->father_name,
            'father_phone' => $request->father_phone ?? $request->parent_phone,
            'mother_name' => $request->mother_name,
            'mother_phone' => $request->mother_phone ?? $request->parent_phone,
            'parent_phone' => $request->parent_phone,
            'registration_status' => 'completed',
            'registration_completed_at' => now(),
        ]);

        $user->update(['phone' => $request->phone]);

        AuditLog::record('STUDENT_REGISTRATION', 'Siswa ' . $user->name . ' menyelesaikan daftar ulang PPDB.');

        return redirect()->route('dashboard')->with('success', 'Selamat! Daftar ulang berhasil. Anda sekarang dapat mengakses semua fitur.');
    }
}