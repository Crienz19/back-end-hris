<?php

namespace App\Repositories\Trip;

interface ITripRepository
{
    public function allTrips($columns = ['*'], string $orderBy = 'created_at', string $sortBy = 'asc');
    public function getOneTrip(array $where);
    public function getTripBy(array $where);
    public function saveTrip(array $attributes);
    public function updateTrip(array $where, array $attributes);
    public function deleteTrip($id);
    public function acknowledgeTrip($id);
    public function filterTrip($dateFrom, $dateTo, $status);
}