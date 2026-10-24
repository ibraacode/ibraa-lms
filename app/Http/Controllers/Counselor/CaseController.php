<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\CounselingCase;
use App\Models\CounselingNote;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function index()
    {
        $cases = CounselingCase::with(['student', 'counselor'])
            ->latest()
            ->paginate(15);

        return view('counselor.cases.index', compact('cases'));
    }

    public function create()
    {
        $students = User::role('siswa')->get();
        return view('counselor.cases.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'category' => 'required|in:akademik,keluarga,sosial,pelanggaran,karir,lainnya',
            'status' => 'required|in:open,in_progress,closed',
        ]);

        CounselingCase::create([
            'student_id' => $request->student_id,
            'counselor_id' => Auth::id(),
            'title' => $request->title,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        AuditLog::record('COUNSELING_CASE_OPENED', 'Membuka kasus BK baru untuk siswa ID: ' . $request->student_id);
        return redirect()->route('counselor.cases.index')->with('success', 'Kasus berhasil dibuka.');
    }

    public function show(CounselingCase $case)
    {
        // Catat akses ke catatan sensitif untuk keamanan
        AuditLog::record('COUNSELING_CASE_VIEWED', 'Membuka detail kasus BK #' . $case->id . ' siswa: ' . $case->student->name);

        $case->load(['student', 'counselor', 'notes.author']);
        return view('counselor.cases.show', compact('case'));
    }

    public function addNote(Request $request, CounselingCase $case)
    {
        $request->validate([
            'content' => 'required|string',
            'session_date' => 'required|date',
        ]);

        CounselingNote::create([
            'counseling_case_id' => $case->id,
            'author_id' => Auth::id(),
            'content' => $request->content,
            'session_date' => $request->session_date,
        ]);

        AuditLog::record('COUNSELING_NOTE_ADDED', 'Menambah catatan sesi untuk kasus BK #' . $case->id);
        return back()->with('success', 'Catatan sesi berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, CounselingCase $case)
    {
        $request->validate(['status' => 'required|in:open,in_progress,closed']);
        $case->update(['status' => $request->status]);
        AuditLog::record('COUNSELING_CASE_UPDATED', 'Mengubah status kasus BK #' . $case->id . ' menjadi ' . $request->status);
        return back()->with('success', 'Status kasus diperbarui.');
    }
}