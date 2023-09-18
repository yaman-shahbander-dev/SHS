<?php

namespace App\Repositories;

use App\Models\reviews;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method reviews findWithoutFail($id, $columns = ['*'])
 * @method reviews find($id, $columns = ['*'])
 * @method reviews first($columns = ['*'])
 */
class ReviewsRepositry extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return reviews::class;
    }

}
