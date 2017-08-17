<?php

namespace CustomBar;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as CL;


use CustomBar\Task\UpdateHud as UH;


class Main extends PluginBase implements Listener
{
    public $plugin;

    public $prefix = CL::DARK_GRAY . "[" . CL::BLUE . "Custom" . CL::RED . "Hud" . CL::DARK_GRAY . "]" . CL::RESET;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->prefix . CL::GREEN . " by SuperKali Enable");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new UH($this), $this->getConfig()->get("time") * 4);
    }

    public function onDisable()
    {
        $this->saveDefaultConfig();
        $this->getLogger()->info($this->prefix . CL::RED . " by SuperKali Disable");
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
