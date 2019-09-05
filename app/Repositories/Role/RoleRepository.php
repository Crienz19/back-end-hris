<?php

namespace App\Repositories\Role;

use App\Repositories\Base\BaseRepository;
use App\Role;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function allRoles($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneRole(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getRoleBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveRole(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateRole(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteRole($id)
    {
        return parent::delete($id);
    }

}