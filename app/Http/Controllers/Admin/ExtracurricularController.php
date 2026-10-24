<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Http\Request;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $extracurriculars = Extracurricular::with('advisor')->withCount('members')->latest()->paginate(10);
        $teachers = User::role('guru')->get();
        return view('admin.extracurriculars.index', compact('extracurriculars', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'advisor_id' => 'nullable|exists:users,id',
            'schedule_day' => 'nullable|string|max:20',
            'schedule_time' => 'nullable|date_format:H:i',
        ]);

        Extracurricular::create($request->all());

        return back()->with('success', 'Ekstrakurikuler berhasil dibuat.');
    }

    public function toggleOpen(Extracurricular $extracurricular)
    {
        $extracurricular->update(['is_open' => !$extracurricular->is_open]);
        return back()->with('success', 'Status pendaftaran diperbarui.');
    }

    public function members(Extracurricular $extracurricular)
    {
        $extracurricular->load(['advisor', 'members.student']);
        return view('admin.extracurriculars.members', compact('extracurricular'));
    }
}