<?php

namespace App\Repositories;

use App\Models\Message;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Interface ChatRepository.
 *
 * @package namespace App\Repositories;
 */
class MessageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'msg',
        'sender_id',
        'chat_id',
        'date',
        'time'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Message::class;
    }
}


