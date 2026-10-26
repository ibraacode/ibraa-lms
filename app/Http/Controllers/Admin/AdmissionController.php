<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }
        $admissions = $query->paginate(20)->withQueryString();
        
        // Statistik
        $stats = [
            'total' => Admission::count(),
            'pending' => Admission::where('status', 'pending')->count(),
            'accepted' => Admission::where('status', 'accepted')->count(),
            'rejected' => Admission::where('status', 'rejected')->count(),
            'quota' => (int) Setting::get('ppdb_quota', 0),
        ];

        return view('admin.admissions.index', compact('admissions', 'stats'));
    }

    public function show(Admission $admission)
    {
        return view('admin.admissions.show', compact('admission'));
    }

    public function verify(Request $request, Admission $admission)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
            'notes' => 'nullable|string',
        ]);

        $admission->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        AuditLog::record('ADMISSION_VERIFIED', 'Verifikasi PPDB ' . $admission->registration_number . ' → ' . $request->status);
        return back()->with('success', 'Status pendaftaran diperbarui.');
    }

    // Verifikasi Dokumen
    public function verifyDocuments(Admission $admission)
    {
        $admission->update([
            'is_documents_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);
        AuditLog::record('ADMISSION_DOC_VERIFIED', 'Verifikasi dokumen PPDB ' . $admission->registration_number);
        return back()->with('success', 'Dokumen berhasil diverifikasi.');
    }

    // Simpan Nilai
    public function storeScore(Request $request, Admission $admission)
    {
        $request->validate([
            'report_score' => 'nullable|numeric|min:0|max:100',
            'test_score' => 'nullable|integer|min:0|max:100',
        ]);

        $report = $request->report_score ?? 0;
        $test = $request->test_score ?? 0;

        // Formula: 60% rapor + 40% tes (bisa disesuaikan)
        $finalScore = ($report * 0.6) + ($test * 0.4);

        $admission->update([
            'report_score' => $request->report_score,
            'test_score' => $request->test_score,
            'final_score' => $finalScore,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    // AUTO-SELEKSI
    public function autoSelect()
    {
        $quota = (int) Setting::get('ppdb_quota', 0);

        if ($quota <= 0) {
            return back()->withErrors(['error' => 'Kuota PPDB belum diatur di menu Pengaturan.']);
        }

        // Ambil semua pendaftar yang dokumennya sudah diverifikasi
        $admissions = Admission::where('is_documents_verified', true)
            ->whereNotNull('final_score')
            ->orderBy('final_score', 'desc')
            ->get();

        if ($admissions->isEmpty()) {
            return back()->withErrors(['error' => 'Belum ada pendaftar dengan dokumen terverifikasi dan nilai lengkap.']);
        }

        $accepted = 0;
        $rejected = 0;

        foreach ($admissions as $index => $admission) {
            if ($index < $quota) {
                $admission->update(['status' => 'accepted']);
                $accepted++;
            } else {
                $admission->update(['status' => 'rejected']);
                $rejected++;
            }
        }

        AuditLog::record('AUTO_SELECT_PPDB', "Seleksi otomatis: $accepted diterima, $rejected ditolak (kuota: $quota)");

        return back()->with('success', "Auto-seleksi selesai: $accepted diterima, $rejected tidak lulus.");
    }

    // Konversi ke Siswa (Setelah Lulus Final)
    public function convertToStudent(Request $request, Admission $admission)
    {
        if ($admission->converted_user_id) {
            return back()->withErrors(['error' => 'Pendaftar ini sudah dikonversi menjadi siswa.']);
        }

        if ($admission->status !== 'accepted') {
            return back()->withErrors(['error' => 'Hanya pendaftar yang sudah diterima yang bisa dikonversi.']);
        }

        $request->validate([
            'nis' => 'required|string|max:20|unique:users,nis',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        DB::beginTransaction();
        try {
            $email = $request->nis . '@' . config('school.student_domain');

            $user = User::create([
                'name' => $admission->full_name,
                'email' => $email,
                'nis' => $request->nis,
                'password' => Hash::make('IbraLMS123!'),
                'is_active' => true,
                'must_change_password' => '1',
            ]);

            $user->assignRole('siswa');

            StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $admission->full_name,
                'gender' => $admission->gender,
                'birth_place' => $admission->birth_place,
                'birth_date' => $admission->birth_date,
                'address' => $admission->address,
                'classroom_id' => $request->classroom_id,
                'parent_name' => $admission->father_name ?? $admission->parent_name,
                'parent_phone' => $admission->parent_phone,
                'religion' => $admission->religion,
                'nik' => null,
                'is_verified' => false,
            ]);

            $admission->update(['converted_user_id' => $user->id]);

            DB::commit();
            AuditLog::record('ADMISSION_CONVERTED', 'Konversi PPDB ' . $admission->registration_number . ' menjadi siswa: ' . $user->name);

            return back()->with('success', 'Pendaftar berhasil dikonversi menjadi siswa. Password default: IbraLMS123!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal konversi: ' . $e->getMessage()]);
        }
    }
}