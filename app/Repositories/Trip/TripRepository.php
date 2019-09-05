<?php

namespace App\Repositories\Trip;

use App\Repositories\Base\BaseRepository;
use App\Trip;

class TripRepository extends BaseRepository implements ITripRepository
{
    private $trip;
    public function __construct(Trip $model)
    {
        parent::__construct($model);
        $this->trip = $model;
    }

    public function allTrips($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc')
    {
        return parent::findAll($columns, $orderBy, $sortBy);
    }

    public function getOneTrip(array $where)
    {
        return parent::findOneBy($where);
    }

    public function getTripBy(array $where)
    {
        return parent::findBy($where);
    }

    public function saveTrip(array $attributes)
    {
        return parent::save($attributes);
    }

    public function updateTrip(array $where, array $attributes)
    {
        return parent::update($where, $attributes);
    }

    public function deleteTrip($id)
    {
        return parent::delete($id);
    }

    public function acknowledgeTrip($id)
    {
        $this->update(['id' => $id], [
            'status'    =>  'Acknowledged'
        ]);
    }


    public function filterTrip($dateFrom, $dateTo, $status)
    {
        $trip = $this->trip->where('status', $status)
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->get();

        return $trip;
    }
}