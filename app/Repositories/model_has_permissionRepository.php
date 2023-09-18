<?php

namespace App\Repositories;


use App\Models\model_has_permission;
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
class model_has_permissionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'permission_id',
        'model_type',
        'model_id'

        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return model_has_permission::class;
    }

}
