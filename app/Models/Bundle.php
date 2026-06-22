<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'original_price', 'bundle_price', 'is_active', 'stock',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'original_price' => 'decimal:2',
        'bundle_price'   => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(BundleItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bundle_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->original_price <= 0) return 0;
        return round((($this->original_price - $this->bundle_price) / $this->original_price) * 100);
    }
}