<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements IBaseRepository
{
    private $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findAll($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    public function findBy(array $where)
    {
        return $this->model->where($where)->get();
    }

    public function findOneBy(array $where)
    {
        return $this->model->where($where)->first();
    }

    public function save(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update(array $where, array $attributes)
    {
        return $this->model->where($where)->update($attributes);
    }

    public function delete($id)
    {
        $data = $this->model->findOrFail($id);
        $data->delete();

        return $data;
    }
}
