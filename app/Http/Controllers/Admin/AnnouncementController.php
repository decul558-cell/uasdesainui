<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller {
    public function index() {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'   => 'required|string|max:200',
            'message' => 'required|string',
            'type'    => 'required|in:info,warning,success,danger',
        ]);

        $data            = $request->all();
        $data['is_active'] = $request->has('is_active');

        Announcement::create($data);
        return back()->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function update(Request $request, Announcement $announcement) {
        $data              = $request->all();
        $data['is_active'] = $request->has('is_active');
        $announcement->update($data);
        return back()->with('success', 'Pengumuman diupdate!');
    }

    public function destroy(Announcement $announcement) {
        $announcement->delete();
        return back()->with('success', 'Pengumuman dihapus!');
    }

    public function toggle(Announcement $announcement) {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return back()->with('success', 'Status pengumuman diubah!');
    }
}