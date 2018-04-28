<?php
namespace CustomBar\Utils\KillChats;

use CustomBar\Main;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;

class KillEvents implements Listener
{
    public $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }

    public function onPlayerDeath(PlayerDeathEvent $e)
    {
        $cause = $e->getEntity()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $player = $e->getEntity();
            $killer = $cause->getDamager();
            if ($killer instanceof Player) {
                $kill = strtolower($killer->getName());
                $victim = strtolower($player->getName());
                $this->getMain()->getPlayers()->setNested("$kill.kills", $this->getMain()->getPlayers()->getNested("$kill.kills") + 1);
                $this->getMain()->getPlayers()->setNested("$victim.deaths", $this->getMain()->getPlayers()->getNested("$victim.deaths") + 1);
                $this->getMain()->getPlayers()->save(true);
            }
        }
    }

    /**
     * @return Main
     */
    public function getMain(): Main{
        return $this->main;
    }
}