<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ApiServiceInterface;
use App\Contracts\DataProcessorInterface;

class HomeWorkSolidController extends Controller
{
    protected ApiServiceInterface $apiService;
    protected DataProcessorInterface $dataProcessor;

    public function __construct(ApiServiceInterface $apiService, DataProcessorInterface $dataProcessor)
    {
        $this->apiService = $apiService;
        $this->dataProcessor = $dataProcessor;
    }

    public function index(Request $request)
    {
        $search = 'Продукти Одеса';
        $excludePlaceIds = '';
        $lat = 46.4774700;
        $lon = 30.7326200;
        $properties = ['place_id', 'name', 'display_name', 'distance'];

        while (true) {
            $places = $this->apiService->searchPlaces($search, $excludePlaceIds);

            $this->dataProcessor->calculateDistances($places, $lat, $lon);
            $this->dataProcessor->sortByDistance($places);

            $processedPlaces = [];

            foreach ($places as $place) {
                $processedPlace = [];

                foreach ($place as $prop => $val) {
                    if (in_array($prop, $properties)) {
                        $processedPlace[$prop] = $val;
                    }
                }

                if (isset($place->place_id)) {
                    $processedPlaces[$place->place_id] = $processedPlace;
                }
            }

            if ($excludePlaceIds) {
                dd($processedPlaces);
            }

            $excludePlaceIds = '&exclude_place_ids=' . urlencode(implode(',', array_keys($processedPlaces)));
            dump($processedPlaces);
        }
    }
}