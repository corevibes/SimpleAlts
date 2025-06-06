<?php

namespace SimpleAlts\storage;

class AltsStorage {

    private string $file;
    protected array $data = [];

    public function __construct(string $filePath) {
        $this->file = $filePath;
        if (file_exists($this->file)) {
            $this->data = json_decode(file_get_contents($this->file), true) ?? [];
        }
    }

    public function addAlt(string $ip, string $name) : array {
        if (!isset($this->data[$ip])) {
            $this->data[$ip] = [];
        }

        if (!in_array($name, $this->data[$ip], true)) {
            $this->data[$ip][] = $name;
        }

        return array_filter($this->data[$ip], fn($n) => $n !== $name);
    }

    public function getAltsByIp(string $ip) : array {
        return $this->data[$ip] ?? [];
    }

    public function save() : void {
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
    }
}
