<?php

namespace CustomBar;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;


use CustomBar\Task\UpdateHud as UH;

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("CustomBar by SuperKali Enable");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new UH($this), $this->getConfig()->get("time"));
    }

    public function onDisable()
    {
        $this->getLogger()->info("CustomBar by SuperKali Disable");
    }

    public function formatHUD(): string
    {
        return str_replace(array(
            "&",
            "{tps}",
            "{load}",
            "{players}",
            "{max_players}",
            "{line}",
        ), array(
            "§",
            $this->getServer()->getTicksPerSecond(),
            $this->getServer()->getTickUsage(),
            count($this->getServer()->getOnlinePlayers()),
            $this->getServer()->getMaxPlayers(),
            "\n"
        ), $this->getConfig()->getNested("text"));
    }
}