<?php

namespace CustomBar;

use CustomBar\Utils\KillChat;
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
        if(!$this->pure = $this->getServer()->getPluginManager()->getPlugin('PurePerms')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " PurePerms not found");
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->prefix . CL::GREEN . " by SuperKali Enable");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new TH($this), $this->getConfig()->get("time") * 4);
        if ($this->getConfig()->get("Allow.KillChat" == true)){
            $this->getServer()->getPluginManager()->registerEvents(new KillChat($this), $this);
        }
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
    public function onFactionCheck(Player $player){
        $name = $player->getName();
        if(!$this->pro) return "NoPlug";
        $faz = $this->pro->getPlayerFaction($name);
        If(!$faz) return "NoFaz";
        return $faz;
    }
    public function onGroupCheck(Player $player){
        if(!$this->pure) return "NoPlug";
        $pp = $this->pure->getUserDataMgr()->getGroup($player)->getName();
        return $pp;
    }
    public function onEconomyAPICheck(Player $player){
        $name = $player->getName();
        if(!$this->eco) return "NoPlug";
        $eco = $this->eco->myMoney($name);
        return $eco;
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
            $this->onEconomyAPICheck($player), #6
            $this->getServer()->getTickUsage(), #7
            count($this->getServer()->getOnlinePlayers()), #8
            $this->getServer()->getMaxPlayers(), #9
            "\n", #10
            $this->getServer()->getMotd(), #11
            $this->onFactionCheck($player), #12
            $player->getName(), #13
            $this->getTime($player), #14
            KillChat::class ? KillChat::getKills($player, $this): "", #15
            KillChat::class ? KillChat::getDeaths($player, $this): "", #16
            $player->getPing($name), #17
            $this->onGroupCheck($player) #18
        ), $this->getConfig()->getNested("text"));
    }
}
