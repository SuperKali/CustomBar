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

class Main extends PluginBase implements Listener
{
    public $config;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("CustomBar Enable");
        $this->config = (new Config($this->getDataFolder() . "config.yml", Config::YAML));//Thanks Fycarman For Fix
        $this->config = $this->config->getAll();//Thanks Fycarman For Fix
        $this->saveResource("config.yml");
        $this->saveDefaultConfig();
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new Task\UpdateHud($this), 20);
        $this->reloadConfig();
        foreach ($this->getServer()->getPluginManager()->getPlugins() as $p) {
            if (strpos($p->getName(), strcasecmp("text", "TEXT"))) {
                $this->getLogger()->notice("Conflict plugin detected!");
                $this->getLogger()->notice("Please remove plugin '$p' to make CustomBar work!");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("CustomBar Disable");
        $this->saveDefaultConfig();
    }
}
    /*public function formatHUD(): string{
        return str_replace(array(
            "&",
            "{tps}",
            "{motd}",
            "{players}",
            "{max_players}",
            "{server_name}",
            "{line}",
        ), array(
            "§",
            $this->getServer()->getTicksPerSecond(),
            $this->getServer()->getMotd(),
            count($this->getServer()->getOnlinePlayers()),
            $this->getServer()->getMaxPlayers(),
            "\n",
        ), $this->plugin->config["text"];
    }
}*/
