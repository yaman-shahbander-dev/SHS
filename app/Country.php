<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model 
{
    public $table = 'countries';
    public $fillable = [
        'country_name',
        'name_en',
        'name_ar'
    ];
    public static $rules = [
        'country_name' => 'required',
        'name_en' => 'required',
        'name_en' => 'required'
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

    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }

    function City() {
        return $this->hasMany(City::class, 'country_id');
    }

    function Cities() {
        return $this->hasMany(City::class, 'country_id')->select(['cities.id','cities.city_name', 'cities.country_id']);
    }
}
