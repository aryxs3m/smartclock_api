<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;

class TimeController extends Controller
{

    public function getTime()
    {
        $now = Carbon::now();
        return response()->json([
            'time' => $now->toTimeString(),
            'date' => $now->toDateString()
        ]);
    }
}
