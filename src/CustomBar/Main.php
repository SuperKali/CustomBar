<?php

namespace CustomBar;

use CustomBar\Utils\KillChats\KillChat;
use CustomBar\Utils\KillChats\KillEvents;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as CL;
use pocketmine\Player;

use CustomBar\Task\TaskHud as TH;
use pocketmine\utils\TextFormat;


class Main extends PluginBase implements Listener
{
    /** @var string $prefix */
    public $prefix = CL::DARK_GRAY . "[" . CL::BLUE . "Custom" . CL::RED . "Hud" . CL::DARK_GRAY . "]" . CL::RESET;

    public $eco;
    public $pro;
    public $chat;
    public $pure;

    /** @var Config $killchat */
    public $killchat;

    /** @var KillChat $instance */
    public $instance;


    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        if (!$this->eco = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " EconomyAPI not found");
        }
        if (!$this->pro = $this->getServer()->getPluginManager()->getPlugin('FactionsPro')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " FactionPro not found");
        }
        if (!$this->pure = $this->getServer()->getPluginManager()->getPlugin('PurePerms')) {
            $this->getServer()->getLogger()->alert($this->prefix . CL::RED . " PurePerms not found");
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->prefix . CL::GREEN . " by SuperKali Enable");
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new TH($this), $this->getConfig()->get("time") * 4);
        $this->instance = new KillChat($this);
        $this->killchat = new Config($this->getDataFolder() . "players.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new KillEvents($this), $this);
    }

    public function onDisable()
    {
        $this->saveDefaultConfig();
        $this->getLogger()->info($this->prefix . CL::RED . " by SuperKali Disable");
    }

    /**
     * @return false|string
     */
    public function getTime()
    {
        date_default_timezone_set($this->getConfig()->getNested("timezone"));
        return date($this->getConfig()->get("formatime"));
    }

    /**
     * @param Player $player
     * @return string
     */
    public function onFactionCheck(Player $player)
    {
        $name = $player->getName();
        if (!$this->pro) return "NoPlug";
        $faz = $this->pro->getPlayerFaction($name);
        If (!$faz) return "NoFaz";
        return $faz;
    }

    /**
     * @param Player $player
     * @return string
     */
    public function onGroupCheck(Player $player)
    {
        if (!$this->pure) return "NoPlug";
        $pp = $this->pure->getUserDataMgr()->getGroup($player)->getName();
        return $pp;
    }

    /**
     * @return string
     */
    public function getUptime(): string
    {
        $time = microtime(true) - \pocketmine\START_TIME;
        $seconds = floor($time % 60);
        $minutes = null;
        $hours = null;
        $days = null;
        if ($time >= 60) {
            $minutes = floor(($time % 3600) / 60);
            if ($time >= 3600) {
                $hours = floor(($time % (3600 * 24)) / 3600);
                if ($time >= 3600 * 24) {
                    $days = floor($time / (3600 * 24));
                }
            }
        }
        $uptime = ($minutes !== null ?
                ($hours !== null ?
                    ($days !== null ?
                        "$days days "
                        : "") . "$hours hours "
                    : "") . "$minutes minutes "
                : "") . "$seconds seconds";
        return $uptime;
    }

    /**
     * @param Player $player
     * @return string
     */
    public function onEconomyAPICheck(Player $player)
    {
        $name = $player->getName();
        if (!$this->eco) return "NoPlug";
        $eco = $this->eco->myMoney($name);
        return $eco;
    }

    /**
     * @param Player $player
     * @return int
     */
    public function getItemID(Player $player)
    {
        if (!$player->getInventory()->getItemInHand()->getId()) return 0;
        $id = $player->getInventory()->getItemInHand()->getId();
        return $id;
    }

    /**
     * @param Player $player
     * @return int
     */
    public function getItemMeta(Player $player)
    {
        if (!$player->getInventory()->getItemInHand()->getDamage()) return 0;
        $meta = $player->getInventory()->getItemInHand()->getDamage();
        return $meta;
    }

    /**
     * @param Player $player
     * @return bool|string
     */
    public function colorPing(Player $player)
    {
        $ping = $player->getPing();
        if ($ping < 100) {
            return TextFormat::GREEN . $ping;
        } elseif ($ping < 150) {
            return TextFormat::GOLD . $ping;
        } elseif ($ping < 250) {
            return TextFormat::RED . $ping;
        }
        return false;
    }

    /**
     * @param PlayerJoinEvent $e
     */
    public function onJoin(PlayerJoinEvent $e)
    {
        $name = $e->getPlayer()->getLowerCaseName();
        if (!$this->getPlayers()->exists($name)) {
            $this->getPlayers()->set($name, [
                "kills" => 0,
                "deaths" => 0
            ]);
            $this->getPlayers()->save(true);
        }
    }
    /**
     * @param Player $player
     * @return string
     */
    public function formatHUD(Player $player): string
    {
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
            "{itemid}", #19
            "{client-ip}", #20
            "{server-ip}", #21
            "{uptime}", #22
            "{itemeta}" #23
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
            $this->getTime(), #14
            $this->getInstance()->getPlayerKills($player) , #15
            $this->getInstance()->getPlayerDeaths($player), #16
            $this->colorPing($player), #17
            $this->onGroupCheck($player), #18
            $this->getItemID($player), #19
            $player->getAddress(), #20
            $this->getServer()->getIp(), #21
            $this->getUptime(), #22
            $this->getItemMeta($player) #23
        ), $this->getConfig()->getNested("text"));
    }

    /**
     * @return Config
     */
    public function getPlayers(): Config{
        return $this->killchat;
    }

    /**
     * @return KillChat
     */
    public function getInstance(): KillChat{
        return $this->instance;
    }
}
