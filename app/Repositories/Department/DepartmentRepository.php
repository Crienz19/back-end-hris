<?php

namespace App\Repositories\Department;

use App\Department;
use App\Repositories\Base\BaseRepository;

class DepartmentRepository extends BaseRepository implements IDepartmentRepository
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    public function allDepartments($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneDepartment(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getDepartmentBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveDepartment(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateDepartment(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteDepartment($id)
    {
        return parent::delete($id);
    }

}