<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndustryPlacement;
use App\Models\InternshipLog;
use App\Models\User;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index()
    {
        $placements = IndustryPlacement::latest()->paginate(10);
        return view('admin.internship.index', compact('placements'));
    }

    public function storePlacement(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'phone' => 'nullable|string',
            'major_code' => 'nullable|string',
        ]);

        IndustryPlacement::create($request->all());
        return back()->with('success', 'Tempat PKL berhasil ditambahkan.');
    }

    public function assign()
    {
        $students = User::role('siswa')->with('studentProfile.classroom')->get();
        $placements = IndustryPlacement::all();
        $teachers = User::role('guru')->get();
        
        return view('admin.internship.assign', compact('students', 'placements', 'teachers'));
    }

      public function storeAssign(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'industry_placement_id' => 'required|exists:industry_placements,id',
            'teacher_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        \App\Models\InternshipPlacement::updateOrCreate(
            ['student_id' => $request->student_id],
            [
                'industry_placement_id' => $request->industry_placement_id,
                'teacher_id' => $request->teacher_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]
        );

        return back()->with('success', 'Penempatan PKL berhasil disimpan.');
    }
}