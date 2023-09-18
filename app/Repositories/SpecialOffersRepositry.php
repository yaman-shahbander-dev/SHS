<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\specialOffers;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method specialOffers findWithoutFail($id, $columns = ['*'])
 * @method specialOffers find($id, $columns = ['*'])
 * @method specialOffers first($columns = ['*'])
 */
class SpecialOffersRepositry extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return specialOffers::class;
    }

}
