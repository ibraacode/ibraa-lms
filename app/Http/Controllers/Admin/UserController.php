<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\AuditLog;

class UserController extends Controller
{
                public function index(Request $request)
    {
        $currentYear = \App\Helpers\AcademicYear::current();

        // 1. Ambil data kelas beserta jumlah siswanya (Untuk Tab Siswa)
        $classrooms = \App\Models\Classroom::where('academic_year', $currentYear)
            ->withCount('students')
            ->orderBy('name')
            ->paginate(10, ['*'], 'classroom_page');
      
        // 2. Ambil data pengguna yang BUKAN siswa (Guru, Admin, TU) (Untuk Tab Staf)
        $staffQuery = \App\Models\User::with('roles')
            ->whereDoesntHave('roles', function($q) {
                $q->where('name', 'siswa');
            })->latest();
      
        if ($request->filled('search_staff')) {
            $search = $request->search_staff;
            $staffQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $staffQuery->paginate(10, ['*'], 'staff_page')->withQueryString();
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users.index', compact('classrooms', 'staff', 'roles'));
    }
  
    public function create()
    {
        $roles = Role::whereNotIn('name', ['super-admin'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
            'nis' => 'nullable|string|max:20|unique:users,nis|required_if:role,siswa',
            'nip' => 'nullable|string|max:30|unique:users,nip|required_if:role,guru',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($data) {
            // Generate Email otomatis
            $email = null;
            if ($data['role'] === 'siswa') {
                $email = $data['nis'] . '@' . config('school.student_domain');
            } elseif ($data['role'] === 'guru') {
                $email = $data['nip'] . '@' . config('school.teacher_domain');
            } else {
                // Untuk role lain (TU, BK, dll) pakai email manual nanti, sementara pakai dummy
                $email = strtolower(str_replace(' ', '.', $data['name'])) . '@' . config('school.teacher_domain');
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make($data['password']),
                'nis' => $data['role'] === 'siswa' ? $data['nis'] : null,
                'nip' => $data['role'] === 'guru' ? $data['nip'] : null,
                'is_active' => true,
                'must_change_password' => '1', // Paksa ganti password saat login pertama
            ]);

            $user->assignRole($data['role']);
            AuditLog::record('CREATE_USER', 'Menambahkan pengguna baru: ' . $user->name . ' (' . $user->email . ') dengan role ' . $data['role']);

            // Buat profil dasar
            if ($data['role'] === 'siswa') {
                StudentProfile::create([
                    'user_id' => $user->id,
                    'full_name' => $data['name'],
                    'gender' => 'L', // Default, bisa diedit siswa nanti
                ]);
            } elseif ($data['role'] === 'guru') {
                TeacherProfile::create([
                    'user_id' => $user->id,
                    'full_name' => $data['name'],
                    'gender' => 'L', // Default
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menonaktifkan akun sendiri.']);
        }
        
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Status pengguna diperbarui.');
    }

      public function showImportForm()
    {
        return view('admin.users.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
    'file' => 'required|mimes:csv,txt,xlsx,xls|max:5120', // Maksimal 5MB
    'role' => 'required|in:siswa,guru',
]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\UsersImport($request->role), 
                $request->file('file')
            );
            return redirect()->route('admin.users.index')->with('success', 'Data berhasil diimpor secara massal.');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal mengimpor: ' . $e->getMessage()]);
        }
    }
}