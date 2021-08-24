<?php

namespace App\DataSources;

class SystemSource
{
    public function getRam()
    {
        exec("free -mtl", $output);
        return explode(" ", $output[1]);
    }
}
