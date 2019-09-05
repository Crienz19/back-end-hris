<?php

namespace App\Repositories\Branch;


interface IBranchRepository
{
    public function allBranches($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneBranch(array $where);
    public function getBranchBy(array $where);
    public function saveBranch(array $attributes);
    public function updateBranch(array $where, array $attributes);
    public function deleteBranch($id);
}