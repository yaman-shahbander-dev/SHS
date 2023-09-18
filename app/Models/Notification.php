<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'type',
        'country',
        'category',
        'subcategory',
        'city'
    ];
}
