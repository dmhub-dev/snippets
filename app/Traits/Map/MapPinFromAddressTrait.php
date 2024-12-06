<?php

namespace App\Traits\Map;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait MapPinFromAddressTrait
{
    /**
     * Retrieves the latitude and longitude for a given address using the Google Maps Geocoding API.
     *
     * @param string $address The address to geocode.
     * @return array|bool Returns an array with 'lat', 'lng', and 'formatted_address' on success, or false on failure.
     */
    function getLatLong($address)
    {
        if (empty($address)) {
            Log::warning('Empty address provided to getLatLong().');
            return false;
        }

        $apiKey = config('services.google.maps_api_key');

        if (empty($apiKey)) {
            Log::error('Google Maps API key is missing in configuration.');
            return false;
        }

        $apiUrl = "https://maps.googleapis.com/maps/api/geocode/json?address="
            . urlencode($address) . "&key=" . $apiKey;

        try {
            $client = new Client();
            $response = $client->get($apiUrl);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody());
                return $this->parseGeocodeResponse($data);
            }

            Log::error('Google Maps API request failed with status code: ' . $response->getStatusCode());
            return false;
        } catch (Exception $e) {
            Log::error("Google Maps API error for address: $address, URL: $apiUrl. Message: " . $e->getMessage());
            return false;
        }
    }

    private function parseGeocodeResponse($data)
    {
        if ($data->status === "OK") {
            return [
                'lat' => $data->results[0]->geometry->location->lat,
                'lng' => $data->results[0]->geometry->location->lng,
                'formatted_address' => $data->results[0]->formatted_address
            ];
        }

        Log::error('Google Maps API returned status: ' . $data->status);
        return false;
    }
}
