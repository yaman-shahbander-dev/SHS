<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homeowner_filter extends Model
{
    protected $fillable = ['homeOwner_id', 'vendor_filter','vendor_offer','vendor_working'];
}
