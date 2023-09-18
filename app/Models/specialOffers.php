<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\subCategory;

class specialOffers extends Model
{
    public $table = 'special_offers';

    public $fillable = [
        'description',
        'user_id',
        'image',
        'title'
    ];

    public static $rules = [
        'description' => 'required',
        'user_id' => 'required'
    ];
    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class,setting('custom_field_models',[]));
        if (!$hasCustomField){
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
            ->where('custom_fields.in_table','=',true)
            ->get()->toArray();

        return convertToAssoc($array,'name');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function subcategories() {
        return $this->belongsTo(subCategory::class, 'subcategory_id');
    }

    public function getUser() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
