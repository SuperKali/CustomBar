<?php

namespace CustomBar\Utils;

use pocketmine\Player;

interface KillChatInterfaces{

    public function getPlayerKills(Player $player);

    public function getPlayerDeaths(Player $player);
}