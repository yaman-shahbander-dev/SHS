<?php

namespace App\Repositories;

use App\Models\Category;
use App\subCategory;
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
class SubCategoriesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'name_en',
        'description',
        'category_id',
        'description_en',
        'name_ar'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return subCategory::class;
    }

}
