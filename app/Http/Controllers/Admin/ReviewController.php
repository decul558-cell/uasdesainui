<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller {
    public function index() {
        $reviews = Review::with('user', 'product')->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review) {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review disetujui!');
    }

    public function reject(Review $review) {
        $review->update(['is_approved' => false]);
        return back()->with('success', 'Review ditolak!');
    }

    public function destroy(Review $review) {
        $review->delete();
        return back()->with('success', 'Review dihapus!');
    }
}