<?php

namespace CustomBar;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as CL;
use pocketmine\Player;

use CustomBar\Task\TaskHud as TH;


class Main extends PluginBase implements Listener
{
    public $plugin;

    public $prefix = CL::DARK_GRAY . "[" . CL::BLUE . "Custom" . CL::RED . "Hud" . CL::DARK_GRAY . "]" . CL::RESET;

    public $eco;
    public $pro;
    public $chat;
    public $pure;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        if(!$this->eco = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " EconomyAPI not found");
        }
        if(!$this->pro = $this->getServer()->getPluginManager()->getPlugin('FactionsPro')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " FactionPro not found");
        }
        if(!$this->chat = $this->getServer()->getPluginManager()->getPlugin('KillChat')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " KillChat not found");
        }
        if(!$this->pure = $this->getServer()->getPluginManager()->getPlugin('PurePerms')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " PurePerms not found");
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->prefix . CL::GREEN . " by SuperKali Enable");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new TH($this), $this->getConfig()->get("time") * 4);
    }

    public function onDisable()
    {
        $this->saveDefaultConfig();
        $this->getLogger()->info($this->prefix . CL::RED . " by SuperKali Disable");
    }
    public function getTime()
    {
        date_default_timezone_set($this->getConfig()->getNested("timezone"));
        return date($this->getConfig()->get("formatime"));
    }
    public function formatHUD(Player $player): string
    {
        $name = $player->getName();
        return str_replace(array(
            "&", #1
            "{tps}", #2
            "{x}", #3
            "{y}", #4
            "{z}", #5
            "{coins}", #6
            "{load}", #7
            "{players}", #8
            "{max_players}", #9
            "{line}", #10
            "{MOTD}", #11
            "{faction}", #12
            "{name}", #13
            "{time}", #14
            "{kills}", #15
            "{deaths}", #16
            "{ping}", #17
            "{group}", #18
        ), array(
            "§", #1
            $this->getServer()->getTicksPerSecond(), #2
            (int)$player->getX(), #3
            (int)$player->getY(), #4
            (int)$player->getZ(), #5
            $this->eco ?  $this->eco->myMoney($name) : "", #6
            $this->getServer()->getTickUsage(), #7
            count($this->getServer()->getOnlinePlayers()), #8
            $this->getServer()->getMaxPlayers(), #9
            "\n", #10
            $this->getServer()->getMotd(), #11
            $this->pro ? $this->pro->getPlayerFaction($name) : "", #12
            $player->getName(), #13
            $this->getTime($player), #14
            $this->chat ? $this->chat->getKills($name) : "", #15
            $this->chat ? $this->chat->getDeaths($name) : "", #16
            $player->getPing($name), #17
            $this->pure ? $this->pure->getUserDataMgr()->getGroup($player)->getName() : "" #18
        ), $this->getConfig()->getNested("text"));
    }
}
