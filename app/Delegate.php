<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    public $table = 'delegates';

    public $fillable = [
        'name',
        'phone',
        'balance_id'
    ];

    public static $rules = [
        'name' => 'required',
        'phone'=>'required'
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

    public function Userdelegate() {
        return $this->hasOne(User::class, 'delegate_id');
      }

      public function Balance() {
        return $this->belongsTo(Balance::class, 'balance_id');
    }
}
