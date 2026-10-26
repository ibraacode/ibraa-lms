<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeachingJournal;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AcademicYear;

class JournalController extends Controller
{
    public function index()
    {
        $currentYear = AcademicYear::current();

        $journals = TeachingJournal::with(['schedule.classroom', 'schedule.subject'])
            ->where('teacher_id', Auth::id())
            ->whereHas('schedule.classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->latest('date')
            ->paginate(10);

        $schedules = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', Auth::id())
            ->whereHas('classroom', function($q) use ($currentYear) {
                $q->where('academic_year', $currentYear);
            })
            ->get();

        return view('teacher.journals.index', compact('journals', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'material_covered' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        if ($schedule->teacher_id !== Auth::id()) {
            abort(403);
        }

        TeachingJournal::create([
            'schedule_id' => $request->schedule_id,
            'teacher_id' => Auth::id(),
            'date' => $request->date,
            'material_covered' => $request->material_covered,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Jurnal mengajar berhasil disimpan.');
    }
}