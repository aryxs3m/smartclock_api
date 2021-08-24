<?php

namespace App\Helpers;

class ClockDashboardSmallText
{
    public $type = 0;
    public $text;

    /**
     * @param string $text
     */
    public function __construct(string $text = "")
    {
        $this->text = $text;
    }


}
