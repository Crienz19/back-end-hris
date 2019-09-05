<?php
/**
 * Created by PhpStorm.
 * User: Renz
 * Date: 8/8/2019
 * Time: 10:50 AM
 */

namespace App\Repositories\Employee;


interface IEmployeeRepository
{
    public function allEmployeees($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneEmployee(array $where);
    public function getEmployeeBy(array $where);
    public function saveEmployee(array $attributes);
    public function updateEmployee(array $where, array $attributes);
    public function deleteEmployee($id);
}