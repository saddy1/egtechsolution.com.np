<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'name','image_path','alt_text','link_url','is_active','sort_order'
    ];

    protected $casts = [
        'is_active' => 'bool',
        'sort_order' => 'int',
    ];

    // Scope for active banners
    public function scopeActive($q) { return $q->where('is_active', true); }
}
