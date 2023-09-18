<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class model_has_permission extends Model
{

    public $table = 'model_has_permissions';
    
    public $fillable = [
        'permission_id',
        'model_type',
        'model_id'
    ];
   
}