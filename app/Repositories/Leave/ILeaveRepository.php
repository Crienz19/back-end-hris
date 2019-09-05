<?php

namespace App\Repositories\Leave;

interface ILeaveRepository 
{
    public function allLeaves($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneLeave(array $where);
    public function getLeaveBy(array $where);
    public function saveLeave(array $attributes);
    public function updateLeave(array $where, array $attributes);
    public function deleteLeave($id);
    public function approveRecommendingApproval($leaveId):void;
    public function disapproveRecommendingApproval($leaveId):void;
    public function approveFinalApproval($leaveId):void;
    public function disapproveFinalApproval($leaveId):void;
    public function getLeaveById($id);
    public function filterLeaves($dateFrom, $dateTo, $status);
}