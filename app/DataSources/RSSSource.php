<?php

namespace App\DataSources;

use App\Helpers\ClockDashboardSmallText;
use Feed;
use Illuminate\Support\Facades\Cache;

class RSSSource
{
    private $rss;

    public function __construct(string $feed_url)
    {
        $this->rss = Feed::loadRss($feed_url);
    }

    public function getItems()
    {
        return $this->rss->item;
    }

    public function getItemsAsLines($max = 3)
    {
        $lines = [];
        $i = 0;
        foreach ($this->getItems() as $item)
        {
            $lines[] = new ClockDashboardSmallText($item->title);
            $i++;

            if ($i > $max)
            {
                break;
            }
        }

        return $lines;
    }

}
