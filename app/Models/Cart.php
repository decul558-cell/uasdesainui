<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'bundle_id',
        'quantity', 'price',
        'last_activity_at', 'abandoned_at', 'is_reminded',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'abandoned_at'     => 'datetime',
        'is_reminded'      => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function user()    { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function bundle()  { return $this->belongsTo(Bundle::class); }

    // ── Scopes ─────────────────────────────────────────────────────

    /** Keranjang yang sudah dianggap abandon (tidak ada aktivitas > 2 jam) */
    public function scopeAbandoned(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNotNull('abandoned_at')
              ->orWhere('last_activity_at', '<=', now()->subHours(2))
              ->orWhere(function ($q2) {
                  // Cart lama yang belum punya last_activity_at (data existing)
                  $q2->whereNull('last_activity_at')
                     ->where('updated_at', '<=', now()->subHours(2));
              });
        });
    }

    /** Keranjang aktif (masih dalam 2 jam terakhir) */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('last_activity_at', '>', now()->subHours(2))
              ->orWhere(function ($q2) {
                  $q2->whereNull('last_activity_at')
                     ->where('updated_at', '>', now()->subHours(2));
              });
        })->whereNull('abandoned_at');
    }

    // ── Helpers ────────────────────────────────────────────────────

    /** Tandai cart ini sebagai abandon */
    public function markAsAbandoned(): void
    {
        $this->update(['abandoned_at' => now()]);
    }

    /** Update last activity (panggil tiap user ubah keranjang) */
    public static function touchActivity(int $userId): void
    {
        static::where('user_id', $userId)
              ->whereNull('abandoned_at')
              ->update(['last_activity_at' => now()]);
    }
}