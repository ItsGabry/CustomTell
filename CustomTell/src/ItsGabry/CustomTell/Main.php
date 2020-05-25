<?php

declare(strict_types=1);

namespace ItsGabry\CustomTell;

use pocketmine\command\Command;
use pocketmine\command\defaults\TellCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{
    /**
     * @var Config
     */
    private $nanoconfig;

    public function onEnable() {
        $this->nanoconfig = new Config($this->getDataFolder() . "config.yml", Config::YAML, ["SenderMessage" => "[{sender} -> {recipient}] {message}", "RecipientMessage" =>"[{sender} -> {recipient}] {message}"]);
        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("tell"));
        $this->getServer()->getCommandMap()->register($this->getDescription()->getName(), new CustomTell("tell",$this));
    }
}