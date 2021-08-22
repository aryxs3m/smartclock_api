<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
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

    private function getWeather()
    {
        $request = $this->client->get("weather?q=".urlencode(env('OWM_CITY'))."&units=metric&appid=" . env("OWM_API_KEY"));
        return json_decode($request->getBody()->getContents());
    }

    public function getWeatherFiltered()
    {
        return Cache::remember("weather", 300, function(){
            $data = $this->getWeather();
            return response()->json([
                'temp' => $data->main->temp,
                'pressure' => $data->main->pressure,
                'humidity' => $data->main->humidity,
                'feels_like' => $data->main->feels_like,
                'wind_speed' => $data->wind->speed,
            ]);
        });
    }
}
