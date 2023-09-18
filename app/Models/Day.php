<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    public function VendordaysApi() {
        return $this->belongsToMany(User::class,'days_vendors','day_id','vendor_id')->withPivot('start');
    }
    public function Vendordays() {
        return $this->belongsToMany(User::class,'days_vendors','day_id','vendor_id')->withPivot('start','end');
    }
}
