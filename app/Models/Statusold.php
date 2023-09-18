<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Status extends Model
{
    function UserStatus() {
        return $this->hasMany(User::class, 'status_id');
    }
}
