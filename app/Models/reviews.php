<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class reviews extends Model implements HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    public $table = 'reviews';



    public $fillable = [
        'price_rating',
        'service_rating',
        'speed_rating',
        'trust_rating',
        'knowledge_rating',
        'vendor_id',
        'description',
        'client_id',
        'approved'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'price_rating' => 'required',
        'service_rating' => 'required',
        'vendor_id' => 'required',
        'description' => 'required'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media'

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
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }

    public function vendors(){
        return $this->belongsTo(User::class,'vendor_id','id');
    }
    public function clients(){
        return $this->belongsTo(User::class,'client_id','id');
    }



}
