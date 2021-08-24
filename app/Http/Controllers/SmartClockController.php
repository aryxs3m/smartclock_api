<?php

namespace App\Http\Controllers;

use App\DataSources\CalendarSource;
use App\DataSources\DiscordSource;
use App\DataSources\RSSSource;
use App\DataSources\SystemSource;
use App\DataSources\WeatherSource;
use App\Helpers\ClockDashboard;
use App\Helpers\ClockDashboardLargeText;
use App\Helpers\ClockDashboardSmallText;
use Carbon\Carbon;
use GuzzleHttp\Client;
use http\Env\Response;
use ICal\ICal;
use Illuminate\Support\Facades\Cache;

class SmartClockController extends Controller
{
    private $dashboards;

    public function __construct()
    {
        $weather = (new WeatherSource())->getWeather();
        //$discord = (new DiscordSource())->discordOnline(env('DISCORD_GUILD_ID'));
        $discord = "N/A";
        $now = Carbon::now();

        $icsDashboard = Cache::remember('calendar_dsb', 60*30, function(){
            $calendarSource = new CalendarSource(env("ICALENDAR_URL"));
            $calendarEvents = $calendarSource->getEvents();

            $icsDashboard = new ClockDashboard([
                new ClockDashboardSmallText("Mai naptáresemények"),
                new ClockDashboardSmallText()
            ]);
            foreach($calendarEvents as $event)
            {
                $icsDashboard->add(new ClockDashboardSmallText(
                    $calendarSource->parseDate($event->dtstart_array[3]) . " " . $event->summary
                ));
            }
            return $icsDashboard;
        });

        $hupDashboard = Cache::remember('hup_dsb', 60*15, function(){
            $hupDashboard = new ClockDashboard([
                new ClockDashboardSmallText("hup.hu hírek"),
                new ClockDashboardSmallText(),
            ]);
            $hupDashboard->add((new RSSSource(env('RSS_URL')))->getItemsAsLines());
            return $hupDashboard;
        });

        $this->dashboards = [
            new ClockDashboard([
                new ClockDashboardSmallText(env('OWM_CITY') . " idöjárás"),
                new ClockDashboardLargeText($weather->main->temp . " °C"),
                new ClockDashboardLargeText($weather->main->humidity . "%")
            ]),
            new ClockDashboard([
                new ClockDashboardSmallText(env('APP_TIMEZONE')),
                new ClockDashboardSmallText(),
                new ClockDashboardSmallText($now->toDateString()),
                new ClockDashboardLargeText($now->toTimeString())
            ]),
            new ClockDashboard([
                new ClockDashboardSmallText("Szerver terhelés"),
                new ClockDashboardSmallText(),
                new ClockDashboardSmallText(" 1 min: " . sys_getloadavg()[0]),
                new ClockDashboardSmallText(" 5 min: " . sys_getloadavg()[1]),
                new ClockDashboardSmallText("15 min: " . sys_getloadavg()[2]),
            ]),
            new ClockDashboard([
                new ClockDashboardSmallText("PVGA Discord"),
                new ClockDashboardSmallText(),
                new ClockDashboardLargeText($discord . " online")
            ]),
            $icsDashboard,
            $hupDashboard
        ];
    }

    public function getInitSettings()
    {
        return response()->json([
            'progressbar_delay' => 25,
            'max_dashboard_index' => count($this->dashboards)-1
        ]);
    }

    public function getDashboard($index)
    {
        if ($index < count($this->dashboards)) {
            return response()->json($this->dashboards[$index]->get());
        }
        else
        {
            return response()->json(['error' => 'No such dashboard']);
        }
    }
}
