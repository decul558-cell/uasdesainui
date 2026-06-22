<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    public function apply(Request $request) {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['error' => 'Kupon tidak valid atau sudah kadaluarsa.'], 422);
        }

        $total = $request->total ?? 0;

        if ($total < $coupon->min_order) {
            return response()->json(['error' => 'Minimum order Rp ' . number_format($coupon->min_order, 0, ',', '.') . ' untuk kupon ini.'], 422);
        }

        $discount = $coupon->calculateDiscount($total);

        return response()->json([
            'success'  => true,
            'discount' => $discount,
            'message'  => 'Kupon berhasil dipakai! Diskon Rp ' . number_format($discount, 0, ',', '.'),
            'coupon'   => $coupon->code,
        ]);
    }
}