<?php
/**
 * Created by PhpStorm.
 * User: Renz
 * Date: 8/8/2019
 * Time: 10:50 AM
 */

namespace App\Repositories\Employee;


use App\Employee;
use App\Repositories\Base\BaseRepository;

class EmployeeRepository extends BaseRepository implements IEmployeeRepository
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }

    public function allEmployeees($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneEmployee(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getEmployeeBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveEmployee(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateEmployee(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteEmployee($id)
    {
        return parent::delete($id);
    }


}