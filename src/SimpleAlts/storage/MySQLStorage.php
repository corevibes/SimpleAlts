<?php

namespace SimpleAlts\storage;

use mysqli;
use pocketmine\plugin\Plugin;

class MySQLStorage extends AltsStorage {

    private mysqli $db;

    public function __construct(array $config, Plugin $plugin) {
        $this->db = new mysqli(
            $config["host"],
            $config["user"],
            $config["password"],
            $config["database"],
            $config["port"]
        );

        if ($this->db->connect_error) {
            $plugin->getLogger()->error("Error connecting to MySQL: " . $this->db->connect_error);
            return;
        }

        $this->db->query("CREATE TABLE IF NOT EXISTS alts (ip VARCHAR(45), name VARCHAR(50))");
    }

    public function addAlt(string $ip, string $name) : array {
        $stmt = $this->db->prepare("SELECT name FROM alts WHERE ip = ?");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $res = $stmt->get_result();
        $names = [];

        while ($row = $res->fetch_assoc()) {
            $names[] = $row["name"];
        }

        if (!in_array($name, $names, true)) {
            $insert = $this->db->prepare("INSERT INTO alts (ip, name) VALUES (?, ?)");
            $insert->bind_param("ss", $ip, $name);
            $insert->execute();
        }

        return array_filter($names, fn($n) => $n !== $name);
    }

    public function getAltsByIp(string $ip) : array {
        $stmt = $this->db->prepare("SELECT name FROM alts WHERE ip = ?");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $res = $stmt->get_result();

        $names = [];
        while ($row = $res->fetch_assoc()) {
            $names[] = $row["name"];
        }

        return $names;
    }

    public function save() : void {
        // MySQL storage does not require saving to a file, but you can implement any cleanup or finalization logic here.
    }
}
