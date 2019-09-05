<?php
/**
 * Created by PhpStorm.
 * User: Renz
 * Date: 8/14/2019
 * Time: 2:22 PM
 */

namespace App\Repositories\Credit;


interface ICreditRepository
{
    public function updateVacationLeave($userId, $value);
    public function updateSickLeave($userId, $value);
    public function updatePersonalTimeOff($userId, $value);
}