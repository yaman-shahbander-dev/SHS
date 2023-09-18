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
 * @method City findWithoutFail($id, $columns = ['*'])
 * @method City find($id, $columns = ['*'])
 * @method City first($columns = ['*'])
 */
class EarningRepository extends BaseRepository
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