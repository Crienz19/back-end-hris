<?php

namespace App\Repositories\Department;

interface IDepartmentRepository 
{
    public function allDepartments($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneDepartment(array $where);
    public function getDepartmentBy(array $where);
    public function saveDepartment(array $attributes);
    public function updateDepartment(array $where, array $attributes);
    public function deleteDepartment($id);
}