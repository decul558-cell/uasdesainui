<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $fillable = ['title','message','type','bg_color','is_active','start_at','end_at'];
    protected $casts = ['is_active'=>'boolean','start_at'=>'datetime','end_at'=>'datetime'];

    public function isActive() {
        if (!$this->is_active) return false;
        if ($this->start_at && $this->start_at->isFuture()) return false;
        if ($this->end_at && $this->end_at->isPast()) return false;
        return true;
    }
}