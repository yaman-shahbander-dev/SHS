<?php

/**
 * File name: User.php
 * Last modified: 2020.06.11 at 16:10:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Models;

use App\City;
use App\Models\Message;
use App\Models\Status;
use App\vendors_suggested;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use App\Models\BannedUsers;
use App\subCategory;
use App\Duration;
use App\Models\specialOffers;
use App\Balance;
use App\Delegate;
use App\Models\Day;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * Class User
 * @package App\Models
 * @version July 10, 2018, 11:44 am UTC
 *
 * @property \App\Models\Cart[] cart
 * @property string name
 * @property string email
 * @property string password
 * @property string api_token
 * @property string device_token
 */
class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use Billable;
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }


    use HasRoles;


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:users',
        'phone' => 'nullable|max:255|unique:users'
    ];

    public $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'device_token',
        'last_name',
        'language',
        'city_id',
        'user_id',
        'phone',
        'duration_id',
        'start_date',
        'expire',
        'balance_id',
        'delegate_id',
        'activation_code_exp_date',
        'payment_id',
        'nickname',
        'caption',
        'showNickname',
        'description',
        'website',
        'address',
        'fcm_token',
        'owner_name',
        'is_verified',
        'country_prefix',
        'instagram',
        'facebook',
        'background_profile'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'api_token' => 'string',
        'device_token' => 'string',
        'remember_token' => 'string'
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Specifies the user's FCM token
     *
     * @return string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(\Spatie\MediaLibrary\Models\Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        if ($url) {
            $array = explode('.', $url);
            $extension = strtolower(end($array));
            if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
                return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
            } else {
                return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
            }
        } else {
            return asset('images/avatar_default.png');
        }
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            //            ->where('custom_fields.in_table', '=', true)
            ->select(['value', 'view', 'name'])
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->hasMedia('avatar') ? true : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/


    // public function messages()
    // {
    //     return $this->hasMany(\App\Models\Message::class, 'sender_id', 'id');
    // }
    public function vendors_sugested()
    {
        return $this->hasMany(vendors_suggested::class, 'user_id', 'id');
    }
    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function bannedusers()
    {
        return $this->hasOne(BannedUsers::class, 'user_id');
    }
    public function clients()
    {
        return $this->belongsToMany(User::class, 'reviews', 'vendor_id', 'client_id')
            ->withPivot('description', 'approved', 'service_rating', 'price_rating', 'speed_rating', 'knowledge_rating', 'trust_rating')
            ->withTimestamps();
    }

    public function vendors()
    {
        return $this->belongsToMany(User::class, 'reviews', 'client_id', 'vendor_id')
            ->withPivot('description', 'service_rating', 'approved', 'price_rating', 'speed_rating', 'knowledge_rating', 'trust_rating')
            ->withTimestamps();
    }

    public function clientsAPI()
    {
        return $this->belongsToMany(User::class, 'reviews', 'vendor_id', 'client_id')
            ->withPivot(['description', 'id'])->select(['users.id', 'users.name', 'users.avatar', 'users.last_name'])->where('approved', 1)
            ->withTimestamps();
    }

    public function vendorsAPI()
    {
        return $this->belongsToMany(User::class, 'reviews', 'client_id', 'vendor_id')
            ->withPivot('description', 'service_rating', 'approved', 'price_rating', 'speed_rating', 'knowledge_rating', 'trust_rating')->select(['users.id', 'users.name', 'users.avatar', 'users.last_name'])->where('approved', 1)
            ->withTimestamps();
    }

    public function homeOwnerFavorite()
    {
        return $this->belongsToMany(User::class, 'favorite_user', 'vendor_id', 'user_id')
            ->withTimestamps();
    }
    public function vendorFavorite()
    {
        return $this->belongsToMany(User::class, 'favorite_user', 'user_id', 'vendor_id')
            ->withTimestamps();
    }

    public function vendorFavoriteAPI()
    {
        return $this->belongsToMany(User::class, 'favorite_user', 'user_id', 'vendor_id')->select(['users.id', 'users.name', 'users.avatar', 'users.last_name', 'users.description'])
            ->withTimestamps();
    }
    //
    public function subcategories()
    {
        return $this->belongsToMany(subCategory::class, 'subcategory_user', 'vendor_id', 'subcategory_id')
            ->withTimestamps();
    }

    public function duration()
    {
        return $this->belongsTo(Duration::class, 'duration_id');
    }

    public function subcategoriesApi() {
         return $this->belongsToMany(subCategory::class,'subcategory_user','vendor_id','subcategory_id')->select(['sub_categories.id', 'sub_categories.name','sub_categories.image', 'sub_categories.description'])
          ->withTimestamps();

    }

    public function specialOffers()
    {
        return $this->hasMany(specialOffers::class, 'user_id', 'id');
    }

    public function homeOwnerHistoryAPI()
    {
        return $this->belongsToMany(User::class, 'homeowners_vendors', 'homeowner_id', 'vendor_id')->select(['users.id', 'users.name', 'users.avatar', 'users.last_name', 'users.description'])->withTimeStamps();
    }

    public function VendorHistoryAPI()
    {
        return $this->belongsToMany(User::class, 'homeowners_vendors', 'vendor_id', 'homeowner_id')->select(['users.id', 'users.name', 'users.avatar', 'users.last_name', 'users.description'])->withTimeStamps();
    }

    public function Balance()
    {
        return $this->belongsTo(Balance::class, 'balance_id')->select(['id', 'balance']);
    }
    public function delegate()
    {
        return $this->belongsTo(Delegate::class, 'delegate_id');
    }

    public function FromUserName()
    {
        return $this->belongsToMany(User::class, 'transfer_transactions',  'to_id', 'from_id')->withPivot('amount', 'type')
            ->withTimestamps();
    }

    public function ToUserName()
    {
        return $this->belongsToMany(User::class, 'transfer_transactions',  'from_id', 'to_id')->withPivot('amount', 'type')
            ->withTimestamps();
    }

    public function coordinates()
    {
        return $this->hasOne(GmapLocation::class, 'user_id');
    }
    public function daysApi()
    {
        return $this->belongsToMany(Day::class, 'days_vendors', 'vendor_id', 'day_id')->select(['days.id', 'name_en', 'name_ar', 'days_vendors.start', 'days_vendors.end'])
            ->withTimestamps();
    }
    public function days()
    {
        return $this->belongsToMany(Day::class, 'days_vendors', 'vendor_id', 'day_id')->withPivot('start', 'end');
    }


    function vendor_city()
    {
        return $this->belongsToMany(City::class, 'vendors_cities', 'vendor_id', 'city_id')->select(['cities.id', 'cities.city_name'])->withTimeStamps();
    }
    public function gallery()
    {
        return $this->hasMany(\App\Models\Gallery::class, 'user_id');
    }

    public function messages_from()
    {
        return $this->belongsToMany(Message::class, 'messages', 'from', 'to', 'users.device_token', 'users.device_token');
    }
    public function messages_to()
    {
        return $this->belongsToMany(Message::class, 'messages', 'to', 'from', 'users.device_token', 'users.device_token');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'to', 'device_token');
    }
    //    public function setGalleryAPI()
    //    {
    //        return $this->gallery->image = asset('storage/gallery') . '/' . $this->gallery->image;
    //    }

    public function vendorViews()
    {
        return $this->belongsToMany(User::class, 'views', 'user_id', 'vendor_id')
            ->withTimestamps();
    }

    public function userViews()
    {
        return $this->belongsToMany(User::class, 'views', 'vendor_id', 'user_id')
            ->withTimestamps();
    }

    public function vendorContacts()
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'vendor_id')
            ->withTimestamps();
    }

    public function userContacts()
    {
        return $this->belongsToMany(User::class, 'contacts', 'vendor_id', 'user_id')
            ->withTimestamps();
    }
}
