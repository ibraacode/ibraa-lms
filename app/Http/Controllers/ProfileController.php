<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'] ?? null;

        if (! empty($data['new_password'])) {
            if (! Hash::check($data['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.']);
            }
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ================= PROFIL GURU =================
    public function editTeacher()
    {
        $user = auth()->user();
        return view('profile.edit-teacher', compact('user'));
    }

    public function updateTeacher(Request $request)
    {
        $user = $request->user();
        
        if ($user->teacherProfile?->is_verified) {
            return back()->withErrors(['error' => 'Data Anda sudah difinalisasi oleh Admin.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'religion' => 'nullable|string',
            'gender' => 'nullable|in:L,P',
            'marital_status' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'last_education' => 'nullable|string',
            'university' => 'nullable|string',
            'major' => 'nullable|string',
            'graduation_year' => 'nullable|integer',
            'employment_status' => 'nullable|string',
            'joined_at' => 'nullable|date',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        if ($user->teacherProfile) {
            $user->teacherProfile->update($request->except(['name', 'phone']));
        } else {
            \App\Models\TeacherProfile::create(array_merge(
                $request->except(['name', 'phone']),
                ['user_id' => $user->id, 'full_name' => $request->name]
            ));
        }

        return back()->with('success', 'Biodata guru berhasil diperbarui.');
    }

    // ================= PROFIL SISWA =================
    public function editStudent()
    {
        $user = auth()->user();
        return view('profile.edit-student', compact('user'));
    }

    public function updateStudent(Request $request)
    {
        $user = $request->user();
        
        if ($user->studentProfile?->is_verified) {
            return back()->withErrors(['error' => 'Data Anda sudah difinalisasi oleh Admin.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'kk_number' => 'nullable|string|max:20',
            'religion' => 'nullable|string',
            'gender' => 'nullable|in:L,P',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'child_order' => 'nullable|integer',
            'siblings_count' => 'nullable|integer',
            'living_with' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'address' => 'nullable|string',
            'father_name' => 'nullable|string',
            'father_status' => 'nullable|string',
            'father_job' => 'nullable|string',
            'father_phone' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_status' => 'nullable|string',
            'mother_job' => 'nullable|string',
            'mother_phone' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_relation' => 'nullable|string',
            'guardian_job' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'guardian_address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        if ($user->studentProfile) {
            $user->studentProfile->update($request->except(['name', 'phone']));
        } else {
            \App\Models\StudentProfile::create(array_merge(
                $request->except(['name', 'phone']),
                ['user_id' => $user->id, 'full_name' => $request->name]
            ));
        }

        return back()->with('success', 'Biodata siswa berhasil diperbarui.');
    }

    // ================= FINALISASI (ADMIN) =================
    public function verifyProfile(Request $request, $type, $userId)
    {
        if (!auth()->user()->hasAnyRole(['super-admin', 'tu'])) {
            abort(403);
        }

        $user = \App\Models\User::findOrFail($userId);
        
        if ($type === 'student' && $user->studentProfile) {
            $user->studentProfile->update([
                'is_verified' => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        } elseif ($type === 'teacher' && $user->teacherProfile) {
            $user->teacherProfile->update([
                'is_verified' => true,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        }

        \App\Models\AuditLog::record('VERIFY_PROFILE', 'Finalisasi biodata ' . $user->name);
        return back()->with('success', 'Biodata berhasil difinalisasi.');
    }
}