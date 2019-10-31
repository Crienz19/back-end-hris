<?php

namespace App\Repositories\Overtime;

use App\Overtime;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;

class OvertimeRepository extends BaseRepository implements IOvertimeRepository
{
    private $overtime;
    public function __construct(Overtime $model)
    {
        parent::__construct($model);
        $this->overtime = $model;
    }

    public function allOvertimes($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneOvertime(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getOvertimeBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveOvertime(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateOvertime(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteOvertime($id)
    {
        return parent::delete($id);
    }

    public function approveOvertime($id)
    {
        $this->update(['id' => $id], [
            'status'    =>  'Approved'
        ]);
    }

    public function disapproveOvertime($id)
    {
        $this->update(['id' => $id], [
            'status'    =>  'Disapproved'
        ]);
    }

    public function filterOvertime($dateFrom, $dateTo, $status)
    {
        $dt = Carbon::createFromDate($dateTo);
        $overtimes = $this->overtime
                        ->where('status', $status)
                        ->whereBetween('created_at',[$dateFrom, $dt->addDay()])
                        ->get();

        return $overtimes;
    }
}