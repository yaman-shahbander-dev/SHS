<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{


    protected $fillable = ['from', 'to', 'message', 'is_read', 'fileName', 'type'];

     public function user() {
         return $this->belongsTo(User::class, 'to','device_token');
     }
}
