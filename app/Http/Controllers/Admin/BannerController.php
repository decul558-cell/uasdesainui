<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller {
    public function index() {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'    => 'required|string|max:200',
            'bg_color' => 'required|string',
        ]);

        $data            = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);
        return back()->with('success', 'Banner berhasil ditambahkan!');
    }

    public function update(Request $request, Banner $banner) {
        $data              = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        return back()->with('success', 'Banner diupdate!');
    }

    public function destroy(Banner $banner) {
        $banner->delete();
        return back()->with('success', 'Banner dihapus!');
    }
}