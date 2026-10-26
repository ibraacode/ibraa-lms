<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Classroom;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Ambil daftar tahun ajaran unik
        $academicYears = Classroom::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        return view('auth.login', compact('academicYears'));
    }

        public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'academic_year' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Akun tidak ditemukan atau tidak aktif.',
            ]);
        }

        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();
        session(['active_academic_year' => $request->academic_year]);
        $user->update(['last_login_at' => now()]);

        // Cek status kelulusan
        if ($user->hasRole('siswa')) {
            $profile = $user->studentProfile;

            if ($profile && in_array($profile->status, ['lulus', 'mutasi', 'dropout'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'email' => 'Akun Anda sudah tidak aktif (Status: ' . ucfirst($profile->status) . ').',
                ]);
            }

            // Redirect ke Daftar Ulang jika belum selesai
            if ($profile && $profile->registration_status === 'pending') {
                return redirect()->route('student.registration');
            }
        }

        if ($user->must_change_password === '1') {
            return redirect()->route('password.change');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}