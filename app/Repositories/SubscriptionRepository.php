<?php

namespace App\Repositories;

use App\Subscription;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 10, 2018, 11:44 am UTC
 *
 * @method Subscription findWithoutFail($id, $columns = ['*'])
 * @method Subscription find($id, $columns = ['*'])
 * @method Subscription first($columns = ['*'])
 */

    class SubscriptionRepository extends BaseRepository
    {
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'type',
        'duration',
        'discount'

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Subscription::class;
    }
}
