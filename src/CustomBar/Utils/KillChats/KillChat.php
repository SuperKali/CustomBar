<?php
namespace CustomBar\Utils\KillChats;

use CustomBar\Main;
use pocketmine\Player;

class KillChat {

    /**
     * @param Player $player
     */
    public static function getPlayerKills(Player $player) {
        $player = strtolower($player);
        if (Main::getInstance()->getPlayers()->getNested("$player.kills") >= 0){
            return Main::getInstance()->getPlayers()->getNested("$player.kills");
        }
        return "0";
    }

    /**
     * @param Player $player
     */
    public static function getPlayerDeaths(Player $player){
        $player = strtolower($player);
        if (Main::getInstance()->getPlayers()->getNested("$player.deaths") >= 0){
            return Main::getInstance()->getPlayers()->getNested("$player.deaths");
        }
        return "0";
    }
}

