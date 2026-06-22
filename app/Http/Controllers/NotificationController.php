<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    public function index() {
        $notifications = Notification::where('user_id', Auth::id())
                                    ->latest()->paginate(15);
        return view('pages.notifications', compact('notifications'));
    }

    public function markRead($id) {
        Notification::where('id', $id)->where('user_id', Auth::id())
                    ->update(['is_read' => true]);
        return back();
    }

    public function markAllRead() {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}