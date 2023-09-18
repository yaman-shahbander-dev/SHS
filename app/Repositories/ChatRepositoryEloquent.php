<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ChatRepository;
use App\Entities\Chat;
use App\Validators\ChatValidator;

/**
 * Class ChatRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ChatRepositoryEloquent extends BaseRepository implements ChatRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Chat::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
