<?php

namespace App\Models;

use Eloquent as Model;

class Chat extends Model
{
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }
    
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant','restaurant_id','id');
    }
    
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
