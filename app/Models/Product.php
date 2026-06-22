<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = [
        'category_id','title','slug','author','publisher',
        'year','description','price','stock','cover','isbn'
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function wishlists() { return $this->hasMany(Wishlist::class); }
    public function reviews() { return $this->hasMany(Review::class); }

    public function getAverageRatingAttribute() {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function isNew() {
        return $this->created_at->diffInDays(now()) <= 7;
    }
}