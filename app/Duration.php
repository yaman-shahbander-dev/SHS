<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Duration extends Model
{
    public $table = 'durations';
    public $fillable = [
        'duration',
        'discount',
        'duration_in_num'
    ];
    public static $rules = [
        '' => ''
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

    public function vendorDuration() {
        return $this->hasMany(User::class, 'duration_id');
    }
}
