<?php

namespace App\Repositories\User;

interface IUserRepository 
{
    public function allUsers($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneUser(array $where);
    public function getUserBy(array $where);
    public function saveUser(array $attributes);
    public function updateUser(array $where, array $attributes);
    public function deleteUser($id);
}