<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class FeatureToggleController extends Controller
{
    public function index()
    {
        $features = [
            'feature_qr_attendance' => [
                'label' => 'Absensi Harian (QR Code)',
                'description' => 'Mengaktifkan fitur scan QR untuk kehadiran siswa di gerbang sekolah.',
                'active' => Setting::get('feature_qr_attendance', '1') == '1',
            ],
            'feature_finance' => [
                'label' => 'Modul Keuangan (SPP, BOS, Hibah)',
                'description' => 'Mengaktifkan fitur pencatatan tagihan siswa dan pemasukan non-siswa.',
                'active' => Setting::get('feature_finance', '1') == '1',
            ],
        ];

        return view('admin.settings.features', compact('features'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'feature_qr_attendance' => 'nullable|in:0,1',
            'feature_finance' => 'nullable|in:0,1',
        ]);

        $newQr = $request->input('feature_qr_attendance', '0');
        $newFinance = $request->input('feature_finance', '0');

        Setting::set('feature_qr_attendance', $newQr);
        Setting::set('feature_finance', $newFinance);

        AuditLog::record('UPDATE_FEATURES', 'Mengubah status modul: QR=' . $newQr . ', Finance=' . $newFinance);

        return back()->with('success', 'Status modul berhasil diperbarui.');
    }
}