<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class city extends Model
{
    public $table = 'cities';

    public $fillable = [
        'city_name',
        'name_en',
        'name_ar',
        'country_id'
    ];

    public static $rules = [
        'name' => 'required',
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

    // 
    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    function users() {
        return $this->hasMany(User::class, 'city_id');
    }

    function vendors() {
        return $this->belongsToMany(User::class, 'vendors_cities', 'city_id', 'vendor_id')->withTimeStamps();
    }
}
