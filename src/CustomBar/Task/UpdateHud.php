<?php

namespace CustomBar\Task;

use pocketmine\scheduler\PluginTask;


class UpdateHud extends PluginTask
{

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($plugin);
    }

    public function onRun($tick)
    {
        $pl = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
            $text = $this->plugin->formatHUD($player);
            $player->sendTip($text);
        }
    }
}
