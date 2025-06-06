<?php

namespace SimpleAlts\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;

use SimpleAlts\SimpleAlts;

class AltsCommand extends Command {

    public function __construct(private SimpleAlts $plugin) {
        parent::__construct("alts", "View alternative accounts by IP", "/alts <jugador>");
        $this->setPermission("simplealts.cmdalts");
    }

    public function execute(CommandSender $sender, string $label, array $args) : void {
        if (!$this->testPermission($sender)) return;

        if (count($args) < 1) {
            $sender->sendMessage(TF::RED . "Use: /alts <player>");
            return;
        }

        $target = $this->plugin->getServer()->getPlayerExact($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage(TF::RED . "Player not found online.");
            return;
        }

        $ip = $target->getNetworkSession()->getIp();
        $alts = $this->plugin->getStorage()->getAltsByIp($ip);

        if (empty($alts)) {
            $sender->sendMessage(TF::GRAY . "No alternative accounts found.");
        }else{
            $sender->sendMessage(TF::YELLOW . "Accounts from IP of " . $target->getName() . ": " . TF::WHITE . implode(", ", $alts));
        }
    }
}
