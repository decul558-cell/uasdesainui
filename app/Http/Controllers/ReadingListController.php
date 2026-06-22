<?php
namespace App\Http\Controllers;

use App\Models\ReadingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingListController extends Controller
{
    public function index()
    {
        $wantToRead = ReadingList::with('product.category')
                        ->where('user_id', Auth::id())
                        ->where('status', 'want_to_read')
                        ->latest()->get();

        $reading = ReadingList::with('product.category')
                        ->where('user_id', Auth::id())
                        ->where('status', 'reading')
                        ->latest()->get();

        $finished = ReadingList::with('product.category')
                        ->where('user_id', Auth::id())
                        ->where('status', 'finished')
                        ->latest()->get();

        return view('pages.reading-list', compact('wantToRead', 'reading', 'finished'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'status'     => 'required|in:want_to_read,reading,finished',
        ]);

        ReadingList::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $request->product_id],
            ['status'  => $request->status]
        );

        return back()->with('success', 'Buku ditambahkan ke daftar baca!');
    }

    public function update(Request $request, $id)
    {
        $item = ReadingList::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $item->update(['status' => $request->status]);
        return back()->with('success', 'Status baca diupdate!');
    }

    public function destroy($id)
    {
        ReadingList::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Buku dihapus dari daftar baca.');
    }
}