<?php

namespace App\Contracts;

interface ApiServiceInterface
{
    public function searchPlaces($search, $exclude_place_ids = '');
}
