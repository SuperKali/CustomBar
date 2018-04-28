<?php
namespace CustomBar\Utils\KillChats;

use CustomBar\Main;
use CustomBar\Utils\KillChatInterfaces;
use pocketmine\Player;

class KillChat implements KillChatInterfaces {

    /** @var Main */
    public $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }

    /**
     * @param Player $player
     */
    public function getPlayerKills(Player $player) {
        $player = strtolower($player);
        $this->getMain()->getPlayers()->getNested("$player.kills");
    }

    /**
     * @param Player $player
     */
    public function getPlayerDeaths(Player $player){
        $player = strtolower($player);
        $this->getMain()->getPlayers()->getNested("$player.deaths");
    }

    /**
     * @return Main
     */
    public function getMain(): Main{
        return $this->main;
    }
}

