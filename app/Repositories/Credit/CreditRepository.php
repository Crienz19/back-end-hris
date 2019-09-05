<?php
/**
 * Created by PhpStorm.
 * User: Renz
 * Date: 8/14/2019
 * Time: 2:21 PM
 */

namespace App\Repositories\Credit;


use App\Credit;
use App\Repositories\Base\BaseRepository;

class CreditRepository extends BaseRepository implements ICreditRepository
{
    private $credit;
    public function __construct(Credit $model)
    {
        parent::__construct($model);
        $this->credit = $model;
    }

    public function updateVacationLeave($userId, $value)
    {
        $current = $this->credit->where('user_id', $userId)->first();
        $this->update(['user_id' => $userId], [
            'VL'        =>  $value - $current->count
        ]);

        return true;
    }

    public function updateSickLeave($userId, $value)
    {
        $this->update(['user_id' => $userId], [
            'SL'        =>  $value
        ]);

        return true;
    }

    public function updatePersonalTimeOff($userId, $value)
    {
        $this->update(['user_id' => $userId], [
            'PTO'       =>  $value
        ]);

        return true;
    }
}