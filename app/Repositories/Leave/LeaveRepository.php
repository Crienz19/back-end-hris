<?php

namespace App\Repositories\Leave;

use App\Leave;
use App\Repositories\Base\BaseRepository;

class LeaveRepository extends BaseRepository implements ILeaveRepository
{
    private $leave;
    public function __construct(Leave $model)
    {
        parent::__construct($model);
        $this->leave = $model;
    }

    public function allLeaves($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneLeave(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getLeaveBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveLeave(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateLeave(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteLeave($id)
    {
        return parent::delete($id);
    }

    public function approveFinalApproval($leaveId):void
    {
        $this->update(['id' => $leaveId], [
            'final_approval'    =>  'Approved'
        ]);
    }

    public function disapproveFinalApproval($leaveId):void
    {
        $this->update(['id' => $leaveId], [
            'final_approval'    =>  'Disapproved'
        ]);
    }

    public function getLeaveById($id)
    {
        return $this->leave->find($id);
    }

    public function filterLeaves($dateFrom, $dateTo, $status)
    {
        $leave = $this->leave
            ->where('final_approval', $status)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        return $leave;
    }

    public function approveRecommendingApproval($leaveId): void
    {
        $this->update(['id' => $leaveId], [
            'recommending_approval' =>  'Approved'
        ]);
    }

    public function disapproveRecommendingApproval($leaveId): void
    {
        $this->update(['id' => $leaveId], [
            'recommending_approval' =>  'Disapproved'
        ]);
    }
}