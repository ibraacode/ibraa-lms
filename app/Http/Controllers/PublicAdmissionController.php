<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PublicAdmissionController extends Controller
{
    public function showForm()
    {
        // Cek fitur PPDB
        if (Setting::get('feature_ppdb', '1') == '0') {
            abort(404, 'Pendaftaran belum dibuka.');
        }

        // Cek Mode PPDB
        $mode = Setting::get('ppdb_mode', 'swasta');
        if ($mode === 'negeri') {
            return view('public.admission.closed', [
                'message' => 'Pendaftaran mandiri hanya untuk sekolah swasta. Untuk sekolah negeri, silakan hubungi pihak sekolah untuk daftar ulang.'
            ]);
        }

        // Cek Tanggal
        $start = Setting::get('ppdb_registration_start');
        $end = Setting::get('ppdb_registration_end');
        $now = Carbon::now()->toDateString();

        if ($start && $now < $start) {
            return view('public.admission.closed', ['message' => 'Pendaftaran belum dibuka. Akan dibuka pada ' . Carbon::parse($start)->isoFormat('D MMMM Y')]);
        }

        if ($end && $now > $end) {
            return view('public.admission.closed', ['message' => 'Pendaftaran telah ditutup pada ' . Carbon::parse($end)->isoFormat('D MMMM Y')]);
        }

        // Cek Kuota
        $quota = (int) Setting::get('ppdb_quota', 0);
        $registeredCount = Admission::where('status', '!=', 'rejected')->count();
        if ($quota > 0 && $registeredCount >= $quota) {
            return view('public.admission.closed', ['message' => 'Kuota pendaftaran telah penuh.']);
        }

              $majors = \App\Models\Major::orderBy('code')->get();
        $occupations = \App\Models\Occupation::orderBy('name')->get();

        return view('public.admission.form', compact('fee', 'majors', 'occupations'));

        $fee = (int) Setting::get('ppdb_fee', 0);

        return view('public.admission.form', compact('fee'));
    }

    public function store(Request $request)
    {
        if (Setting::get('feature_ppdb', '1') == '0') {
            abort(404);
        }

        $data = $request->validate([
            // Data Diri
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'religion' => 'nullable|string',
            'child_order' => 'nullable|integer',
            'siblings_count' => 'nullable|integer',
            'living_with' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            
            // Ortu
            'father_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string',
            'father_education' => 'nullable|string',
            'mother_name' => 'required|string|max:255',
            'mother_occupation' => 'nullable|string',
            'mother_education' => 'nullable|string',
            'parent_phone' => 'required|string|max:20',
            'parent_address' => 'nullable|string',
            
            // Asal Sekolah & Jurusan
            'previous_school' => 'required|string|max:255',
            'chosen_major' => 'required|string|max:50',
            
            // Dokumen
            'document_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'document_akta' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'document_ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'document_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            
            // Biaya
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Generate nomor pendaftaran: PPDB-YYYY-XXXX
        $lastAdmission = Admission::whereYear('created_at', date('Y'))->latest()->first();
        $number = $lastAdmission ? ((int) substr($lastAdmission->registration_number, -4)) + 1 : 1;
        $registrationNumber = 'PPDB-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        // Upload Dokumen
        $documentPaths = [];
        $documentFields = ['document_kk', 'document_akta', 'document_ijazah', 'document_photo', 'payment_proof'];
        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $documentPaths[$field] = $request->file($field)->store('admissions/' . $registrationNumber, 'public');
            }
        }

        $admission = Admission::create(array_merge($data, $documentPaths, [
            'registration_number' => $registrationNumber,
            'status' => 'pending',
            'is_fee_paid' => $request->hasFile('payment_proof'),
        ]));

        return redirect()->route('admission.success', $admission->registration_number);
    }

    public function success($registrationNumber)
    {
        $admission = Admission::where('registration_number', $registrationNumber)->firstOrFail();
        return view('public.admission.success', compact('admission'));
    }
}