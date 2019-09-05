<?php
/**
 * Created by PhpStorm.
 * User: Renz
 * Date: 8/5/2019
 * Time: 9:25 AM
 */

namespace App\Repositories\Role;


interface IRoleRepository
{
    public function allRoles($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneRole(array $where);
    public function getRoleBy(array $where);
    public function saveRole(array $attributes);
    public function updateRole(array $where, array $attributes);
    public function deleteRole($id);
}