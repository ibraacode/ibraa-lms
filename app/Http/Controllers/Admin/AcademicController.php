<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    // ================= MAPEL =================
    public function subjects()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('admin.academic.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:subjects,code|max:20',
            'name' => 'required|string|max:255',
            'major_code' => 'nullable|string|max:20',
        ]);

        Subject::create($request->all());
        return back()->with('success', 'Mata pelajaran ditambahkan.');
    }

    // ================= KELAS =================
    public function classrooms()
    {
        $classrooms = Classroom::latest()->paginate(10);
        return view('admin.academic.classrooms', compact('classrooms'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:5',
            'major_code' => 'nullable|string|max:20',
            'academic_year' => 'required|string|max:10',
        ]);

        Classroom::create($request->all());
        return back()->with('success', 'Kelas ditambahkan.');
    }

    // ================= JADWAL =================
    public function schedules()
    {
        $schedules = Schedule::with(['classroom', 'subject', 'teacher'])->latest()->paginate(15);
        $classrooms = Classroom::all();
        $subjects = Subject::all();
        $teachers = User::role('guru')->get(); // Ambil user dengan role guru

        return view('admin.academic.schedules', compact('schedules', 'classrooms', 'subjects', 'teachers'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create($request->all());
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }
}