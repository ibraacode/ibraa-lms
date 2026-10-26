<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicAnnouncementController extends Controller
{
    public function index()
    {
        // Cek fitur PPDB
        if (Setting::get('feature_ppdb', '1') == '0') {
            abort(404);
        }

        // Cek tanggal pengumuman
        $announcementDate = Setting::get('ppdb_announcement_date');
        $isOpen = true;

        if ($announcementDate) {
            $now = Carbon::now()->toDateString();
            if ($now < $announcementDate) {
                $isOpen = false;
            }
        }

        return view('public.admission.announcement', compact('isOpen', 'announcementDate'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'full_name' => 'required|string',
        ]);

        $admission = Admission::where('nisn', $request->nisn)
            ->where('full_name', 'like', '%' . $request->full_name . '%')
            ->first();

        if (!$admission) {
            return back()->withInput()->withErrors(['error' => 'Data tidak ditemukan. Pastikan NISN dan Nama Lengkap sesuai dengan yang didaftarkan.']);
        }

        return view('public.admission.result', compact('admission'));
    }

    public function printSkl(Admission $admission)
    {
        if ($admission->status !== 'accepted') {
            abort(403, 'Anda tidak berhak mencetak surat ini.');
        }

        return view('public.admission.print-skl', compact('admission'));
    }
}