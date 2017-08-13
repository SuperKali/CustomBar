<?php

namespace CustomBar;

use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

use CostumBar\Task\UpdateHud as UH;

class Main extends PluginBase implements Listener
{
    public $config;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("CustomBar Enable");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new UH($this), 20);
        $this->reloadConfig();
        /*foreach ($this->getServer()->getPluginManager()->getPlugins() as $p) {
            if (strpos($p->getName(), strcasecmp("text", "TEXT"))) {
                $this->getLogger()->notice("Conflict plugin detected!");
                $this->getLogger()->notice("Please remove plugin '$p' to make CustomBar work!");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }*/
    }

    public function onDisable()
    {
        $this->getLogger()->info("CustomBar Disable");
    }

    public function formatHUD(): string{
        return str_replace(array(
            "&", // 1
            "{tps}", // 2
            "{motd}", // 3
            "{players}", // 4
            "{max_players}", // 5
            "{server_name}", // 6
            "{line}" // 7
        ), array(
            "§", // 1
            $this->getServer()->getTicksPerSecond(), // 2
            $this->getServer()->getMotd(), // 3
            count($this->getServer()->getOnlinePlayers()), // 4
            $this->getServer()->getMaxPlayers(), // 5
            $this->getServer()->getName(), // 6
            "\n" // 7
        ));
    }
}
