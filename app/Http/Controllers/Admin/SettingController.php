<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            // Identitas Sekolah
            'school_name' => Setting::get('school_name'),
            'school_address' => Setting::get('school_address'),
            'school_phone' => Setting::get('school_phone'),
            'school_logo' => Setting::get('school_logo'),
            'active_academic_year' => Setting::get('active_academic_year'),
            'school_type' => Setting::get('school_type', 'negeri'),

            // PPDB
            'ppdb_mode' => Setting::get('ppdb_mode', 'swasta'),
            'ppdb_quota' => Setting::get('ppdb_quota', '350'),
            'ppdb_fee' => Setting::get('ppdb_fee', '0'),
            'ppdb_registration_start' => Setting::get('ppdb_registration_start'),
            'ppdb_registration_end' => Setting::get('ppdb_registration_end'),
            'ppdb_announcement_date' => Setting::get('ppdb_announcement_date'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // Identitas
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:30',
            'active_academic_year' => 'required|string|max:10',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'school_type' => 'required|in:negeri,swasta',

            // PPDB
            'ppdb_mode' => 'required|in:swasta,negeri',
            'ppdb_quota' => 'required|integer|min:0',
            'ppdb_fee' => 'required|integer|min:0',
            'ppdb_registration_start' => 'nullable|date',
            'ppdb_registration_end' => 'nullable|date',
            'ppdb_announcement_date' => 'nullable|date',
        ]);

        // Simpan Identitas
        Setting::set('school_name', $request->school_name);
        Setting::set('school_address', $request->school_address);
        Setting::set('school_phone', $request->school_phone);
        Setting::set('school_type', $request->school_type);
        Setting::set('active_academic_year', $request->active_academic_year);
        
        // Simpan PPDB
        Setting::set('ppdb_mode', $request->ppdb_mode);
        Setting::set('ppdb_quota', $request->ppdb_quota);
        Setting::set('ppdb_fee', $request->ppdb_fee);
        Setting::set('ppdb_registration_start', $request->ppdb_registration_start);
        Setting::set('ppdb_registration_end', $request->ppdb_registration_end);
        Setting::set('ppdb_announcement_date', $request->ppdb_announcement_date);

        // Sinkronisasi Session T.A
        session(['active_academic_year' => $request->active_academic_year]);

        if ($request->hasFile('school_logo')) {
            $oldLogo = Setting::get('school_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('school_logo')->store('school', 'public');
            Setting::set('school_logo', $path);
        }
      
        AuditLog::record('UPDATE_SETTINGS', 'Memperbarui identitas sekolah & pengaturan PPDB.');
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}