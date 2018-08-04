<?php

namespace CustomBar\Task;

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
            $text = $this->plugin->formatHUD($player);
            $player->sendPopup($text);
        }
    }
}
