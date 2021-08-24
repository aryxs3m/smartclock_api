<?php

namespace App\DataSources;

use ICal\ICal;

class CalendarSource
{
    private $ical;

    public function __construct($icsUrl)
    {
        $this->ical = new ICal($icsUrl, array(
            'defaultSpan'                 => 2,     // Default value
            'defaultTimeZone'             => 'UTC',
            'defaultWeekStart'            => 'MO',  // Default value
            'disableCharacterReplacement' => false, // Default value
            'filterDaysAfter'             => null,  // Default value
            'filterDaysBefore'            => null,  // Default value
            'skipRecurrence'              => false, // Default value
        ));
    }

    public function parseDate($date, $format = 'H:i')
    {
        $dateraw = $this->ical->iCalDateToDateTime($date);
        return $dateraw->format($format);
    }

    public function getEvents($interval = '1 day')
    {
        try {
            return $this->ical->eventsFromInterval($interval);
        } catch (\Exception $e) {
            return [];
        }
    }
}
