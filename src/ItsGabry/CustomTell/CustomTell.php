<?php
declare(strict_types=1);

namespace ItsGabry\CustomTell;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class CustomTell extends VanillaCommand implements PluginIdentifiableCommand {
    /**
     * @var Main
     */
    private $plugin;
    /**
     * CustomTell constructor.
     * @param string $name
     * @param Main $plugin
     */
    public function __construct(string $name, Main $plugin) {
        $this->plugin = $plugin;
        parent::__construct(
            $name,
            "%pocketmine.command.tell.description",
            "%commands.message.usage",
            ["w", "msg"]
        );
        $this->setPermission("pocketmine.command.tell");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$this->testPermission($sender)) {
            return true;
        }
        if (count($args) < 2) {
            throw new InvalidCommandSyntaxException();
        }
        $player = $sender->getServer()->getPlayer(array_shift($args));
        if ($player === $sender) {
            $sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.message.sameTarget"));
            return true;
        }
        if ($player instanceof Player) {
            $name = $sender instanceof Player ? $sender->getDisplayName() : $sender->getName();
            $message = implode(" ", $args);
            $format1 = str_replace(["{sender}", "{recipient}", "{message}"], [$name, $player->getDisplayName(), $message], $this->plugin->getConfig()->get("SenderMessage"));
            $format2 = str_replace(["{sender}", "{recipient}", "{message}"], [$name, $player->getDisplayName(), $message], $this->plugin->getConfig()->get("RecipientMessage"));
            $sender->sendMessage($format1);
            $player->sendMessage($format2);
        } else {
            $sender->sendMessage(new TranslationContainer("commands.generic.player.notFound"));
        }
        return true;
    }
    public function getPlugin(): Plugin{
        return $this->plugin;
    }

}
