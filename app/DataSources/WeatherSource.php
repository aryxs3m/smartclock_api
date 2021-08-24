<?php

namespace App\DataSources;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class WeatherSource
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.openweathermap.org/data/2.5/',
            // You can set any number of default request options.
            'timeout'  => 10.0,
        ]);
    }

    public function getWeather()
    {
        return Cache::remember("weather_".env('OWM_CITY'), 60*15, function(){
            $request = $this->client->get("weather?q=".urlencode(env('OWM_CITY'))."&units=metric&appid=" . env("OWM_API_KEY"));
            return json_decode($request->getBody()->getContents());
        });
    }
}
