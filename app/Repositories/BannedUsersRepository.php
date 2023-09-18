<?php

namespace App\Repositories;

use App\Models\BannedUsers;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method BannedUsers findWithoutFail($id, $columns = ['*'])
 * @method BannedUsers find($id, $columns = ['*'])
 * @method BannedUsers first($columns = ['*'])
*/
class BannedUsersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'description',
        'temporary_ban',
        'forever_ban'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BannedUsers::class;
    }

}
