<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Use App\Forecast;
Use App\Log;

class ForecastController extends Controller
{
    //
    public function show(Request $request, Forecast $forecast)
    {
        // Log every request: IP, request method, user agent, time
        $log = new Log;
        $log->visitor = $request->server('REMOTE_ADDR');
        $log->method = $request->server('REQUEST_METHOD');
        $log->agent = $request->server('HTTP_USER_AGENT');
        $log->save();

        // Latitude & Longitude are passed in as query string parameters
        $lat = $request->query('lat');
        $lon = $request->query('lon');
        
        // Here we round or reduce the decimal precision of the location
        // coordinates so that the cache can be utilized more often for a
        // specific location. We aren't concerned that the user moved
        // a couple blocks away. The weather is still the same.
        $lat = round($lat, 3);
        $lon = round($lon, 3);

        // Calls to the openweathermap API will happen no more than one time every 
        // 10 minutes for one location (lat/lon coordinates).
        // Weather forecast is cached at 10 minute intervals.
        $cached_forecast = Forecast::where([
            ['lat', '=', $lat],
            ['lon', '=', $lon],
            ['updated_at', '>', DB::raw('NOW() - INTERVAL 10 MINUTE')],
        ])->first();
        // If the cache is expired or there's no result in the DB we make 
        // a call to openweathermap for the updated forecast.
        if(!$cached_forecast) {
            $ch = curl_init();
            $endpoint = 'http://api.openweathermap.org/data/2.5/forecast';
            $params = array(
                'units' => 'imperial',
                'lat' => $lat,
                'lon' => $lon,
                'APPID' => $_ENV['OPENWEATHER_API_KEY']
            );
            $url = $endpoint . '?' . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = json_decode(curl_exec($ch), TRUE);
            curl_close($ch);
            $forecast = Forecast::updateOrCreate(
                ['lat' => $lat, 'lon' => $lon],
                ['response' => $data]
            );
            return response()->json($forecast, 200);
        } else {
        // We have a result from the DB/cache so there's no reason to hit 
        // the openweathermap API. We return the cached forecast.
            return response()->json($cached_forecast, 200);
        }
    }
}
