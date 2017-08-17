<?php

namespace CustomBar\Task;

use pocketmine\scheduler\PluginTask;
use pocketmine\Player;


class UpdateHud extends PluginTask
{

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($plugin);
    }

    public function onRun($tick)
    {
        $text = $this->plugin->formatHUD();
        $pl = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
            $player->sendPopup($text);
        }
    }
}
