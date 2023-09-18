<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Days_vendor extends Model
{
    public $table = 'days_vendors';

    public $fillable = [
        'name',
        'day_id',
        'vendor_id',
        'start',
        'end'
    ];
}
