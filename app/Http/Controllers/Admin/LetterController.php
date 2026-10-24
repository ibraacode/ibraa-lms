<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use App\Models\Letter;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LetterController extends Controller
{
    // ================= TEMPLATE =================
    public function templates()
    {
        $templates = LetterTemplate::latest()->paginate(10);
        return view('admin.letters.templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_templates,code',
            'body' => 'required|string',
        ]);

        LetterTemplate::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'body' => $request->body,
        ]);

        AuditLog::record('CREATE_LETTER_TEMPLATE', 'Menambahkan template surat: ' . $request->name);
        return back()->with('success', 'Template surat berhasil disimpan.');
    }

    public function destroyTemplate(LetterTemplate $template)
    {
        $template->delete();
        AuditLog::record('DELETE_LETTER_TEMPLATE', 'Menghapus template surat: ' . $template->name);
        return back()->with('success', 'Template surat dihapus.');
    }

    // ================= TERBITKAN SURAT =================
    public function index()
    {
        $letters = Letter::with(['template', 'student'])->latest()->paginate(15);
        $templates = LetterTemplate::all();
        $students = User::role('siswa')->get();
        return view('admin.letters.index', compact('letters', 'templates', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'letter_template_id' => 'required|exists:letter_templates,id',
            'student_id' => 'required|exists:users,id',
            'letter_number' => 'required|string|max:100|unique:letters,letter_number',
            'issued_date' => 'required|date',
        ]);

        $template = LetterTemplate::find($request->letter_template_id);
        $student = User::with('studentProfile.classroom')->find($request->student_id);

        // Ganti variabel dinamis di template
        $content = str_replace(
            ['{nama_siswa}', '{nis}', '{kelas}', '{tanggal_surat}'],
            [
                $student->name,
                $student->nis ?? '-',
                $student->studentProfile->classroom->name ?? '-',
                date('d F Y', strtotime($request->issued_date))
            ],
            $template->body
        );

        Letter::create([
            'letter_template_id' => $template->id,
            'student_id' => $student->id,
            'letter_number' => $request->letter_number,
            'content' => $content,
            'issued_date' => $request->issued_date,
            'created_by' => Auth::id(),
        ]);

        AuditLog::record('CREATE_LETTER', 'Menerbitkan surat ' . $template->name . ' untuk ' . $student->name);
        return back()->with('success', 'Surat berhasil diterbitkan.');
    }

    public function show(Letter $letter)
    {
        $letter->load(['template', 'student.studentProfile.classroom', 'creator']);
        return view('admin.letters.show', compact('letter'));
    }

    public function print(Letter $letter)
    {
        $letter->load(['template', 'student.studentProfile.classroom', 'creator']);
        return view('admin.letters.print', compact('letter'));
    }
}