<?php

namespace App\DataSources;

use Illuminate\Support\Facades\Cache;

class DiscordSource
{
    public function discordOnline($guildNumber)
    {
        return Cache::remember("discord_$guildNumber", 60*60, function() use ($guildNumber){
            $members = json_decode(file_get_contents("https://discordapp.com/api/guilds/{$guildNumber}/widget.json"), true)['members'];
            $membersCount = 1;
            foreach ($members as $member) {
                if ($member['status'] == 'online') {
                    $membersCount++;
                }
            }
            return $membersCount;
        });
    }
}
