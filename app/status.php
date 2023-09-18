<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class status extends Model
{
    function UserStatus() {
        return $this->hasMany(User::class, 'status_id');
    }
}
