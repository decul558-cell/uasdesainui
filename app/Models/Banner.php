<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model {
    protected $fillable = ['title','subtitle','image','button_text','button_url','bg_color','order','is_active'];
    protected $casts = ['is_active'=>'boolean'];
}