<?php

namespace App\Helpers;

class ClockDashboard
{
    private $elements = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function add($element)
    {
        if (is_array($element))
        {
            $this->elements = array_merge($this->elements, $element);
        }
        else
        {
            $this->elements[] = $element;
        }
    }

    public function get()
    {
        return $this->elements;
    }
}
