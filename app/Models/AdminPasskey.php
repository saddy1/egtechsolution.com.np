<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPasskey extends Model
{
    protected $fillable = ['admin_id', 'name', 'credential_id', 'public_key', 'sign_count', 'last_used_at'];

    protected $hidden = ['public_key'];

    protected $casts = ['last_used_at' => 'datetime'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
