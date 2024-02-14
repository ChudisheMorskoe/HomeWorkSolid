<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;

class HomeWorkSolidController extends Controller
{
    public function index(Request $request)
    {
        $url = 'https://nominatim.openstreetmap.org/search.php?format=jsonv2&q=';
        $search = 'Продукти Одеса';
        $exclude_place_ids = '';

        // init coordinates
        $lat = 46.4774700;
        $lon = 30.7326200;

        // necessary properties
        $properties = ['place_id', 'name', 'display_name', 'distance'];

        start:

        // start parse api
        $guzzleClient = new GuzzleClient();
        $response = $guzzleClient->request('GET', $url . urlencode($search) . $exclude_place_ids);
        $places = json_decode($response->getBody()->getContents());
        // end parse api

        //distance calculation
        foreach ($places as $place){
            $res = 2 * asin(sqrt(pow(sin(($lat - $place->lat) / 2), 2) + cos($lat) * cos($place->lat) * pow(sin(($lon - $place->lon) / 2), 2)));
            $place->distance = $res;
        }

        // sort by distance
        usort($places, function($a, $b){
            return ($a->distance < $b->distance) ? -1 : 1;
        });

        //filter output array and add keys by place_id
        foreach ($places as $key=>$place){
            foreach ($place as $prop=>$val) {
                if (!in_array($prop, $properties)){
                    unset($place->$prop);
                }
            }
            $places[$place->place_id] = $place;
            unset($places[$key]);
        }

        // for exit
        if ($exclude_place_ids){
            dd($places);
        }

        $exclude_place_ids = '&exclude_place_ids=' . urlencode(implode(',', array_keys($places)));
        dump($places);

        // for new search without this places
        goto start;
    }
}
