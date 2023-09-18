<?php

namespace App\Repositories;

use App\Models\Chat;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Interface ChatRepository.
 *
 * @package namespace App\Repositories;
 */
class ChatRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'restaurant_id',
        'user_id',
        'created_at',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Chat::class;
    }
}


