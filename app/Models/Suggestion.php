<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\DB;

class Suggestion extends Model
{

    public $table = 'suggestions';



    public $fillable = [
        'user_id',
        'restaurant_id',
        'msg',

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

        'msg' => 'string',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'restaurant_id' => 'required',
        'msg' => 'required',

    ];



    public function user()
    {

        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function restaurant()
    {

        return $this->belongsTo(\App\Models\Restaurant::class, 'restaurant_id', 'id');
    }





}
