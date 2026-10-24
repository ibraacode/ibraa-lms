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
            'school_name' => Setting::get('school_name'),
            'school_address' => Setting::get('school_address'),
            'school_phone' => Setting::get('school_phone'),
            'school_logo' => Setting::get('school_logo'),
            'active_academic_year' => Setting::get('active_academic_year'),
            'school_type' => Setting::get('school_type', 'negeri'), // TAMBAHAN
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:30',
            'active_academic_year' => 'required|string|max:10',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'school_type' => 'required|in:negeri,swasta',
        ]);

        Setting::set('school_name', $request->school_name);
        Setting::set('school_address', $request->school_address);
        Setting::set('school_phone', $request->school_phone);
        Setting::set('active_academic_year', $request->active_academic_year);
        Setting::set('school_type', $request->school_type);

        if ($request->hasFile('school_logo')) {
            $oldLogo = Setting::get('school_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('school_logo')->store('school', 'public');
            Setting::set('school_logo', $path);
        }
      
        AuditLog::record('UPDATE_SETTINGS', 'Memperbarui identitas sekolah.');
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}