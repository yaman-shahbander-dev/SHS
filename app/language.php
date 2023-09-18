<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class language extends Model
{
    public $table = 'languages';

    public $fillable = [
        'name',
        'shortcut'
    ];
}
