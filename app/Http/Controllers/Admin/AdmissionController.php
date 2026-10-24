<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\User;
use App\Models\StudentProfile;
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
        return view('admin.admissions.index', compact('admissions'));
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

    public function convertToStudent(Request $request, Admission $admission)
    {
        if ($admission->converted_user_id) {
            return back()->withErrors(['error' => 'Pendaftar ini sudah dikonversi menjadi siswa.']);
        }

        $request->validate([
            'nis' => 'required|string|max:20|unique:users,nis',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        DB::beginTransaction();
        try {
            // Generate email otomatis
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
                'parent_name' => $admission->parent_name,
                'parent_phone' => $admission->parent_phone,
            ]);

            $admission->update(['converted_user_id' => $user->id]);

            DB::commit();

            AuditLog::record('ADMISSION_CONVERTED', 'Konversi PPDB ' . $admission->registration_number . ' menjadi siswa: ' . $user->name);

            return back()->with('success', 'Pendaftar berhasil dikonversi menjadi siswa dengan NIS: ' . $request->nis);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal konversi: ' . $e->getMessage()]);
        }
    }
}