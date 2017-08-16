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
        //$cfg = $this->plugin->config["text"]; //Thanks Fycarman For Fix
        $pl = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($pl as $p) {
            $p->sendPopup($text);
        }
    }
}
