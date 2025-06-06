<?php

namespace SimpleAlts;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat as TF;
use SimpleAlts\command\AltsCommand;
use SimpleAlts\storage\{AltsStorage, MySQLStorage};

class SimpleAlts extends PluginBase implements Listener {

    private AltsStorage $storage;
    private array $config;

    protected function onEnable(): void {

        $this->saveResource("config.json");
        $this->config = json_decode(file_get_contents($this->getDataFolder() . "config.json"), true);

        $mysql = $this->config["mysql"];

        if ($mysql["enabled"] === true) {
            $this->storage = new MySQLStorage($mysql, $this);
        }else{
            $this->saveResource("alts.json");
            $this->storage = new AltsStorage($this->getDataFolder() . "alts.json");
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("alts", new AltsCommand($this));
        $this->getLogger()->info("
░██████╗██╗███╗░░░███╗██████╗░██╗░░░░░███████╗░█████╗░██╗░░░░░████████╗░██████╗
██╔════╝██║████╗░████║██╔══██╗██║░░░░░██╔════╝██╔══██╗██║░░░░░╚══██╔══╝██╔════╝
╚█████╗░██║██╔████╔██║██████╔╝██║░░░░░█████╗░░███████║██║░░░░░░░░██║░░░╚█████╗░
░╚═══██╗██║██║╚██╔╝██║██╔═══╝░██║░░░░░██╔══╝░░██╔══██║██║░░░░░░░░██║░░░░╚═══██╗
██████╔╝██║██║░╚═╝░██║██║░░░░░███████╗███████╗██║░░██║███████╗░░░██║░░░██████╔╝
╚═════╝░╚═╝╚═╝░░░░░╚═╝╚═╝░░░░░╚══════╝╚══════╝╚═╝░░╚═╝╚══════╝░░░╚═╝░░░╚═════╝░");
    }

    protected function onDisable(): void {
        $this->getLogger()->info("
░██████╗██╗███╗░░░███╗██████╗░██╗░░░░░███████╗░█████╗░██╗░░░░░████████╗░██████╗
██╔════╝██║████╗░████║██╔══██╗██║░░░░░██╔════╝██╔══██╗██║░░░░░╚══██╔══╝██╔════╝
╚█████╗░██║██╔████╔██║██████╔╝██║░░░░░█████╗░░███████║██║░░░░░░░░██║░░░╚█████╗░
░╚═══██╗██║██║╚██╔╝██║██╔═══╝░██║░░░░░██╔══╝░░██╔══██║██║░░░░░░░░██║░░░░╚═══██╗
██████╔╝██║██║░╚═╝░██║██║░░░░░███████╗███████╗██║░░██║███████╗░░░██║░░░██████╔╝
╚═════╝░╚═╝╚═╝░░░░░╚═╝╚═╝░░░░░╚══════╝╚══════╝╚═╝░░╚═╝╚══════╝░░░╚═╝░░░╚═════╝░");
        $this->storage->save();
    }

    public function onPreLogin(PlayerPreLoginEvent $event): void {
        $ip = $event->getIp();
        $alts = $this->storage->getAltsByIp($ip);

        if ($this->config["block-overlimit"] && count($alts) >= $this->config["alts-limit"]) {
            $event->setKickMessage($this->config["kick-message"]);
            $event->cancel();
        }
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $ip = $player->getNetworkSession()->getIp();
        $name = $player->getName();

        $alts = $this->storage->addAlt($ip, $name);

        if (!empty($alts)) {
            $player->sendMessage(TF::YELLOW . "Accounts from your IP: " . TF::WHITE . implode(", ", $alts));
        }
    }

    public function getStorage(): AltsStorage {
        return $this->storage;
    }

    public function getJsonConfig(): array {
        return $this->config;
    }
}
