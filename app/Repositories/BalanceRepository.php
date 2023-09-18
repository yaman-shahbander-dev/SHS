<?php

namespace App\Repositories;

use App\Balance;
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
class BalanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'balance'
    ];

    public $fillable = [
        'balance'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Balance::class;
    }

}
