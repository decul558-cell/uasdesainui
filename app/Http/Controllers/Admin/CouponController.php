<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    public function index() {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create() {
        return view('admin.coupons.form');
    }

    public function store(Request $request) {
        $request->validate([
            'code'      => 'required|unique:coupons|max:20',
            'type'      => 'required|in:percent,fixed',
            'value'     => 'required|integer|min:1',
            'min_order' => 'required|integer|min:0',
            'max_uses'  => 'required|integer|min:1',
            'expired_at'=> 'nullable|date',
        ]);

        $data         = $request->all();
        $data['code'] = strtoupper($request->code);

        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dibuat!');
    }

    public function edit(Coupon $coupon) {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon) {
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon diupdate!');
    }

    public function destroy(Coupon $coupon) {
        $coupon->delete();
        return back()->with('success', 'Kupon dihapus!');
    }
}