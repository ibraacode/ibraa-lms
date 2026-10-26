<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Occupation;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        $majors = Major::latest()->paginate(10, ['*'], 'majors_page');
        $occupations = Occupation::latest()->paginate(10, ['*'], 'occupations_page');
        return view('admin.master-data.index', compact('majors', 'occupations'));
    }

    // ================ JURUSAN ================
    public function storeMajor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:majors,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Major::create($request->all());
        AuditLog::record('CREATE_MAJOR', 'Menambahkan jurusan: ' . $request->name);
        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function destroyMajor(Major $major)
    {
        $major->delete();
        AuditLog::record('DELETE_MAJOR', 'Menghapus jurusan: ' . $major->name);
        return back()->with('success', 'Jurusan berhasil dihapus.');
    }

    // ================ PEKERJAAN ================
    public function storeOccupation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:occupations,name']);
        Occupation::create($request->all());
        return back()->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function destroyOccupation(Occupation $occupation)
    {
        $occupation->delete();
        return back()->with('success', 'Pekerjaan berhasil dihapus.');
    }
}