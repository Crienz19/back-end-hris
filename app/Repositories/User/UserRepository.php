<?php

namespace App\Repositories\User;

use App\Repositories\Base\BaseRepository;
use App\User;

class UserRepository extends BaseRepository implements IUserRepository 
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function allUsers($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneUser(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getUserBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveUser(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateUser(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteUser($id)
    {
        return parent::delete($id);
    }

}