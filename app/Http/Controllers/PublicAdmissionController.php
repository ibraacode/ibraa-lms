<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicAdmissionController extends Controller
{
    public function showForm()
    {
        // Cek apakah PPDB dibuka
        if (Setting::get('feature_ppdb', '1') == '0') {
            abort(404, 'Pendaftaran belum dibuka.');
        }

        return view('public.admission.form');
    }

    public function store(Request $request)
    {
        if (Setting::get('feature_ppdb', '1') == '0') {
            abort(404);
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'previous_school' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'chosen_major' => 'nullable|string|max:50',
        ]);

        // Generate nomor pendaftaran: PPDB-YYYY-XXXX
        $lastAdmission = Admission::whereYear('created_at', date('Y'))->latest()->first();
        $number = $lastAdmission ? ((int) substr($lastAdmission->registration_number, -4)) + 1 : 1;
        $registrationNumber = 'PPDB-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        $admission = Admission::create(array_merge($data, [
            'registration_number' => $registrationNumber,
            'status' => 'pending',
        ]));

        return redirect()->route('admission.success', $admission->registration_number);
    }

    public function success($registrationNumber)
    {
        $admission = Admission::where('registration_number', $registrationNumber)->firstOrFail();
        return view('public.admission.success', compact('admission'));
    }
}