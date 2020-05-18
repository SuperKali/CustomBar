<?php

namespace CustomBar\Commands;

use CustomBar\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class HudSwitcher extends PluginCommand{

    /** @var Main $main **/
    private $main;

    /** @var array $hudswitch */
    public static $hudswitch = [];

    public function __construct(string $name, Main $main)
    {
        parent::__construct($name, $main);
        $this->setDescription("for switch on/off the bar");
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($this->isPlayer($sender)){
                $this->removePlayer($sender);
                $sender->sendMessage($this->getMain()->getConfig()->get("prefix") . "The hud has been deactivated");
            }else{
                $this->addPlayer($sender);
                $sender->sendMessage($this->getMain()->getConfig()->get("prefix") . "The hud has been activated");
            }
        }
    }
    /**
     * @param Player $player
     */
    public static function addPlayer(Player $player) {
        HudSwitcher::$hudswitch[$player->getLowerCaseName()] = $player->getLowerCaseName();
    }

    /**
     * @param Player $player
     * @return bool
     */
    public static function isPlayer(Player $player) {
        return in_array($player->getLowerCaseName(), HudSwitcher::$hudswitch);
    }

    /**
     * @param Player $player
     */
    public static function removePlayer(Player $player) {
        unset(HudSwitcher::$hudswitch[$player->getLowerCaseName()]);
    }

    /**
     * @return Main
     */
    public function getMain(): Main
    {
        return $this->main;
    }
}