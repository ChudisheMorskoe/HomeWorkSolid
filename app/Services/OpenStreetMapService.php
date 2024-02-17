<?php

namespace App\Services;

use App\Contracts\ApiServiceInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class OpenStreetMapService implements ApiServiceInterface
{
    protected GuzzleClient $client;

    public function __construct()
    {
        $this->client = new GuzzleClient();
    }


    /**
     * @throws GuzzleException
     */
    public function searchPlaces($search, $exclude_place_ids = '')
    {
        $url = 'https://nominatim.openstreetmap.org/search.php?format=jsonv2&q=';
        $response = $this->client->request('GET', $url . urlencode($search) . $exclude_place_ids);
        return json_decode($response->getBody()->getContents());
    }
}
