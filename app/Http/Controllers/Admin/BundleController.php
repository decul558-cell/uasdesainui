<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::withCount('items')->latest()->paginate(10);
        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        $products = Product::orderBy('title')->get(); // FIX: hapus where('is_active')
        return view('admin.bundles.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'bundle_price' => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'is_active'    => 'boolean',
            'image'        => 'nullable|image|max:2048',
            'products'     => 'required|array|min:2',
            'products.*'   => 'exists:products,id',
            'quantities'   => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        // Upload image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bundles', 'public');
        }

        // Hitung original_price dari produk yang dipilih
        $selectedProducts = Product::whereIn('id', $request->products)->get()->keyBy('id');
        $originalPrice = 0;
        foreach ($request->products as $i => $productId) {
            $qty = $request->quantities[$i] ?? 1;
            $originalPrice += $selectedProducts[$productId]->price * $qty;
        }

        $bundle = Bundle::create([
            'name'           => $request->name,
            'slug'           => Str::slug($request->name) . '-' . Str::random(5),
            'description'    => $request->description,
            'image'          => $imagePath,
            'original_price' => $originalPrice,
            'bundle_price'   => $request->bundle_price,
            'stock'          => $request->stock,
            'is_active'      => $request->boolean('is_active'),
        ]);

        foreach ($request->products as $i => $productId) {
            BundleItem::create([
                'bundle_id'  => $bundle->id,
                'product_id' => $productId,
                'quantity'   => $request->quantities[$i] ?? 1,
            ]);
        }

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle berhasil dibuat!');
    }

    public function edit(Bundle $bundle)
    {
        $products = Product::orderBy('title')->get(); // FIX: hapus where('is_active')
        $bundle->load('items.product');
        return view('admin.bundles.edit', compact('bundle', 'products'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'bundle_price' => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|max:2048',
            'products'     => 'required|array|min:2',
            'products.*'   => 'exists:products,id',
            'quantities'   => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $bundle->image = $request->file('image')->store('bundles', 'public');
        }

        $selectedProducts = Product::whereIn('id', $request->products)->get()->keyBy('id');
        $originalPrice = 0;
        foreach ($request->products as $i => $productId) {
            $qty = $request->quantities[$i] ?? 1;
            $originalPrice += $selectedProducts[$productId]->price * $qty;
        }

        $bundle->update([
            'name'           => $request->name,
            'description'    => $request->description,
            'image'          => $bundle->image,
            'original_price' => $originalPrice,
            'bundle_price'   => $request->bundle_price,
            'stock'          => $request->stock,
            'is_active'      => $request->boolean('is_active'),
        ]);

        // Sync items
        $bundle->items()->delete();
        foreach ($request->products as $i => $productId) {
            BundleItem::create([
                'bundle_id'  => $bundle->id,
                'product_id' => $productId,
                'quantity'   => $request->quantities[$i] ?? 1,
            ]);
        }

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle berhasil diupdate!');
    }

    public function destroy(Bundle $bundle)
    {
        $bundle->items()->delete();
        $bundle->delete();
        return back()->with('success', 'Bundle berhasil dihapus.');
    }
}