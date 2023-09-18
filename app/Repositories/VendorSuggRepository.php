<?php

namespace App\Repositories;

use App\vendors_suggested;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 10, 2018, 11:44 am UTC
 *
 * @method vendors_suggested findWithoutFail($id, $columns = ['*'])
 * @method vendors_suggested find($id, $columns = ['*'])
 * @method vendors_suggested first($columns = ['*'])
 */
class VendorSuggRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name',
        'email',
        'password',
        'phone',
        'user_id',

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return vendors_suggested::class;
    }
}
