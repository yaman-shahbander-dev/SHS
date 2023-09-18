<?php

namespace App\Repositories;

use App\Models\TransferTransaction;
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
class TransferTransactionRepository extends BaseRepository
{
     public $table = 'transfer_transactions';
          /**
           * @var array
           */

      public $fillable = [
          'from_id',
          'to_id',
          'amount'
          ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TransferTransaction::class;
    }
}
