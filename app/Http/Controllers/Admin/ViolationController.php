<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use App\Models\StudentViolation;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViolationController extends Controller
{
    // ================= MASTER PELANGGARAN =================
    public function types()
    {
        $types = ViolationType::latest()->paginate(15);
        return view('admin.violations.types', compact('types'));
    }

    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        ViolationType::create($request->all());
        AuditLog::record('CREATE_VIOLATION_TYPE', 'Menambahkan jenis pelanggaran: ' . $request->name);
        return back()->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    public function destroyType(ViolationType $type)
    {
        $type->delete();
        AuditLog::record('DELETE_VIOLATION_TYPE', 'Menghapus jenis pelanggaran: ' . $type->name);
        return back()->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }

    // ================= INPUT PELANGGARAN =================
    public function index(Request $request)
    {
        $currentYear = AcademicYear::current();
        $query = StudentViolation::with(['student', 'violationType', 'reportedBy'])
            ->where('academic_year', $currentYear)
            ->latest('date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $violations = $query->paginate(20)->withQueryString();
        $types = ViolationType::all();
        
        // Hanya tampilkan siswa yang aktif di tahun ajaran ini
        $students = User::role('siswa')
            ->whereHas('studentProfile.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->orderBy('name')
            ->get();

        return view('admin.violations.index', compact('violations', 'types', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'violation_type_id' => 'required|exists:violation_types,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        StudentViolation::create([
            'student_id' => $request->student_id,
            'violation_type_id' => $request->violation_type_id,
            'reported_by' => Auth::id(),
            'date' => $request->date,
            'notes' => $request->notes,
            'academic_year' => AcademicYear::current(),
        ]);

        $student = User::find($request->student_id);
        $type = ViolationType::find($request->violation_type_id);
        
        AuditLog::record('RECORD_VIOLATION', 'Mencatat pelanggaran ' . $type->name . ' (' . $type->points . ' poin) untuk siswa: ' . $student->name);
        return back()->with('success', 'Pelanggaran berhasil dicatat.');
    }

    // ================= REKAP POIN =================
    public function recap()
    {
        $currentYear = AcademicYear::current();

        // Ambil siswa dan total poin pelanggarannya di tahun ajaran ini
        $students = User::role('siswa')
            ->whereHas('studentProfile.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->with(['studentProfile.classroom', 'studentViolations' => function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            }])
            ->get()
            ->map(function($student) {
                $student->total_points = $student->studentViolations->sum(function($v) {
                    return $v->violationType->points ?? 0;
                });
                return $student;
            })
            ->sortByDesc('total_points')
            ->values();

        return view('admin.violations.recap', compact('students', 'currentYear'));
    }
}