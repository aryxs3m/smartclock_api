<?php

namespace App\Helpers;

class ClockDashboardLargeText
{
    public $type = 1;
    public $text;

    /**
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }


}
