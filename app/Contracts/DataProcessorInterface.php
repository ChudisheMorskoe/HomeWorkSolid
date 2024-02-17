<?php

namespace App\Contracts;

interface DataProcessorInterface
{
    public function calculateDistances($places, $lat, $lon);

    public function sortByDistance($places);
}
