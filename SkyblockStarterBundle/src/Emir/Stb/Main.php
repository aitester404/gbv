<?php

declare(strict_types=1);

namespace Emir\Stb;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\item\StringToItemParser;
use pocketmine\inventory\Inventory;

final class Main extends PluginBase {

    /** @var Config */
    private Config $cfg;

    protected function onEnable() : void{
        @mkdir($this->getDataFolder());
        // Save default config.yml from resources
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if($command->getName() !== "stb"){
            return false;
        }

        // Must be a player
        if(!$sender instanceof Player){
            $sender->sendMessage("This command can only be used in-game.");
            return true;
        }

        // Name whitelist check
        $allowed = array_map("strtolower", (array)$this->cfg->get("allowed-players", []));
        if(!in_array(strtolower($sender->getName()), $allowed, true)){
            $sender->sendMessage("You are not allowed to use /stb.");
            return true;
        }

        // Optional: also require permission if you like
        if(!$sender->hasPermission("stb.use")){
            $sender->sendMessage("You need the stb.use permission to use /stb.");
            return true;
        }

        $inv = $sender->getInventory();
        $this->giveBundle($inv);

        $sender->sendMessage("Skyblock starter bundle delivered. Good luck!");
        return true;
    }

    private function giveBundle(Inventory $inv) : void{
        $p = StringToItemParser::getInstance();

        // Items:
        // wooden_pickaxe x1
        $inv->addItem($p->parse("wooden_pickaxe")->setCount(1));
        // wooden_axe x1
        $inv->addItem($p->parse("wooden_axe")->setCount(1));
        // lava_bucket x1
        $inv->addItem($p->parse("lava_bucket")->setCount(1));
        // ice x2
        $inv->addItem($p->parse("ice")->setCount(2));
        // melon_seeds x4
        $inv->addItem($p->parse("melon_seeds")->setCount(4));
        // wheat_seeds x4
        $inv->addItem($p->parse("wheat_seeds")->setCount(4));
        // pumpkin_seeds x4
        $inv->addItem($p->parse("pumpkin_seeds")->setCount(4));
        // bread x4
        $inv->addItem($p->parse("bread")->setCount(4));
        // oak_sapling x2
        $inv->addItem($p->parse("oak_sapling")->setCount(2));
        // sand x4
        $inv->addItem($p->parse("sand")->setCount(4));
        // sugarcane x4
        $inv->addItem($p->parse("sugarcane")->setCount(4));
        // cactus x4
        $inv->addItem($p->parse("cactus")->setCount(4));
    }
}
