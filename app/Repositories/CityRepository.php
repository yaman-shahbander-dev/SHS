<?php

namespace App\Repositories;

use App\Models\Country;
use App\City;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method subCategory findWithoutFail($id, $columns = ['*'])
 * @method subCategory find($id, $columns = ['*'])
 * @method subCategory first($columns = ['*'])
 */
class CityRepository extends BaseRepository
{
    /**
     * @var array
     */
    
    protected $fieldSearchable = [
        'city_name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return City::class;
    }

}
