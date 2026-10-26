<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:semua,siswa,guru',
        ]);

        Announcement::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
        ]);
        
        AuditLog::record('CREATE_ANNOUNCEMENT', 'Membuat pengumuman: ' . $request->title);
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:semua,siswa,guru',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
        ]);

        AuditLog::record('UPDATE_ANNOUNCEMENT', 'Memperbarui pengumuman: ' . $request->title);
        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $title = $announcement->title;
        $announcement->delete();
        
        AuditLog::record('DELETE_ANNOUNCEMENT', 'Menghapus pengumuman: ' . $title);
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}