<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {
    protected $fillable = ['code','type','value','min_order','max_uses','used_count','is_active','expired_at'];
    protected $casts = ['expired_at' => 'datetime', 'is_active' => 'boolean'];

    public function isValid() {
        if (!$this->is_active) return false;
        if ($this->used_count >= $this->max_uses) return false;
        if ($this->expired_at && $this->expired_at->isPast()) return false;
        return true;
    }

    public function calculateDiscount($total) {
        if ($this->type === 'percent') {
            return round($total * $this->value / 100);
        }
        return min($this->value, $total);
    }
}