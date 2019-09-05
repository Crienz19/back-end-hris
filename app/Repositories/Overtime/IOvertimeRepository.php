<?php

namespace App\Repositories\Overtime;

interface IOvertimeRepository
{
    public function allOvertimes($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneOvertime(array $where);
    public function getOvertimeBy(array $where);
    public function saveOvertime(array $attributes);
    public function updateOvertime(array $where, array $attributes);
    public function deleteOvertime($id);
    public function approveOvertime($id);
    public function disapproveOvertime($id);
    public function filterOvertime($dateFrom, $dateTo, $status);
}