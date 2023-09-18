<?php

namespace App;
use App\Models\User;
use Eloquent as Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class vendors_suggested extends Model
{
    public $table = 'vendor_suggesteds';

    public $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
        'subscription_id',
        'balance',
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
        return $this->belongsTo(User::class,'user_id');
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    /**
     * Add Media to api results
     * @return bool
     */


}
