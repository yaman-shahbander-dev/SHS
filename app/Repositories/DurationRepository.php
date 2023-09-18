<?php

namespace App\Repositories;

use App\Duration;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class DurationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'duration',
        'discount',
        'duration_in_num'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Duration::class;
    }

}
