<?php

namespace CustomBar\Task;

use CustomBar\Commands\HudSwitcher;
use CustomBar\Main;
use pocketmine\scheduler\Task;


class TaskHud extends Task
{
    /** @var Main $plugin */
    public $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $tick)
    {
        $pl = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
            if (HudSwitcher::isPlayer($player)){ // CHECK PLAYER ON HUD IF IT'S ON OR OFF
                $text = $this->plugin->formatHUD($player);
                $player->sendPopup($text);
            }
        }
    }
}
