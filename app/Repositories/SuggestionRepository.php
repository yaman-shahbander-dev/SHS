<?php

namespace App\Repositories;

use App\Models\Suggestion;
use InfyOm\Generator\Common\BaseRepository;

class SuggestionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'restaurant_id',
        'msg'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Suggestion::class;
    }
}
