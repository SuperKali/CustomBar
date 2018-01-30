<?php
namespace CustomBar\Utils;

use CustomBar\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class KillChat implements Listener {
    public $owner;

    public function __construct(Plugin $plugin){
        $this->owner = $plugin;
    }
    public function onPlayerDeath(PlayerDeathEvent $event){
        //Getting Victim
        $victim = $event->getEntity();
        if($victim instanceof Player){
            $vdata = new Config($this->owner->getDataFolder() . "data/" . strtolower($victim->getName()) . ".yml", Config::YAML);
            //Check victim data
            if($vdata->exists("kills") && $vdata->exists("deaths")){
                $vdata->set("deaths", $vdata->get("deaths") + 1);
                $vdata->save();
            }else{
                $vdata->setAll(array("kills" => 0, "deaths" => 1)); //Add first death
                $vdata->save();
            }
            $cause = $event->getEntity()->getLastDamageCause()->getCause();
            if($cause == 1){ //Killer is an entity
                //Get Killer Entity
                $killer = $event->getEntity()->getLastDamageCause()->getDamager();
                //Get if the killer is a player
                if($killer instanceof Player){
                    //Get killer data
                    $kdata = new Config($this->owner->getDataFolder() . "data/" . strtolower($killer->getName()) . ".yml", Config::YAML);
                    //Check killer data
                    if($kdata->exists("kills") && $kdata->exists("deaths")){
                        $kdata->set("kills", $kdata->get("kills") + 1);
                        $kdata->save();
                    }else{
                        $kdata->setAll(array("kills" => 1, "deaths" => 0)); //Add first kill
                        $kdata->save();
                    }
                }
            }
        }
    }


    public static function getKills($player, Main $main){
        $data = new Config($main->getDataFolder() . "data/" . strtolower($player) . ".yml", Config::YAML);
        //Check data
        if($data->exists("kills") && $data->exists("deaths")){
            return $data->get("kills");
        }else{
            $data->setAll(array("kills" => 0, "deaths" => 0));
            $data->save();
        }
        return true;
    }

    public static function getDeaths($player, Main $main){
        $data = new Config($main->getDataFolder() . "data/" . strtolower($player) . ".yml", Config::YAML);
        //Check data
        if($data->exists("kills") && $data->exists("deaths")){
            return $data->get("deaths");
        }else{
            $data->setAll(array("kills" => 0, "deaths" => 0));
            $data->save();
        }
        return true;
    }
}

