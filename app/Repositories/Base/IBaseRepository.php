<?php

namespace App\Repositories\Base;

interface IBaseRepository
{
    public function findAll($columns, string $orderBy, string $sortBy);
    public function findBy(array $where);
    public function findOneBy(array $where);
    public function save(array $attributes);
    public function update(array $where, array $attributes);
    public function delete($id);

}