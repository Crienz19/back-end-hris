<?php

namespace App\Repositories\Branch;

use App\Branch;
use App\Repositories\Base\BaseRepository;

class BranchRepository extends BaseRepository implements IBranchRepository
{
    public function __construct(Branch $model)
    {
        parent::__construct($model);
    }

    public function allBranches($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneBranch(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getBranchBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveBranch(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateBranch(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteBranch($id)
    {
        return parent::delete($id);
    }

}