<?php
namespace CustomBar\Utils\KillChats;

use CustomBar\Main;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class KillChat implements Listener {
    public $owner;

    public function __construct(Plugin $plugin)
    {
        $this->owner = $plugin;
    }

    public static function getKills($player, Main $main){
        $data = new Config($main->getDataFolder() . "data/" . $player->getLowerCaseName() . ".yml", Config::YAML);
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
        $data = new Config($main->getDataFolder() . "data/" . $player->getLowerCaseName() . ".yml", Config::YAML);
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

