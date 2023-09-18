<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GmapLocation extends Model
{
    protected $fillable = ['user_id', 'latitude', 'longitude', 'icon'];

    public function userDistance() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
