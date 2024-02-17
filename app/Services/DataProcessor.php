<?php

namespace App\Services;

use App\Contracts\DataProcessorInterface;

class DataProcessor implements DataProcessorInterface
{
    public function calculateDistances($places, $lat, $lon): void
    {
        foreach ($places as $place){
            $res = 2 * asin(sqrt(pow(sin(($lat - $place->lat) / 2), 2) + cos($lat) * cos($place->lat) * pow(sin(($lon - $place->lon) / 2), 2)));
            $place->distance = $res;
        }
    }

    public function sortByDistance($places): void
    {
        usort($places, function($a, $b) {
            return $a->distance - $b->distance;
        });
    }
}