<?php

namespace ImNotYour5G\KnockFFA\task;

use ImNotYour5G\KnockFFA\Main;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use xxAROX\AROXLanguage\api\LanguageAPI;

class MapTask extends Task
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $config = new Config("/home/Datenbank/KnockFFA/config.yml");
        $config->set("MapTime", $config->get("MapTime") - 1);
        $config->save();

        $Time = $config->get("MapTime");

        $MinutenTime = $config->get("MapTime") / 60;

        if ($config->get("Map") === "KnockFFA-1") {
            if ($Time === 900 || $Time === 600 || $Time === 300) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->sendMessage($this->plugin->prefix . "The Map will change in " . $MinutenTime . " Minutes!");
                }
            } elseif ($Time === 60 || $Time === 30 || $Time === 15 || $Time === 10 || $Time === 5 || $Time === 4 || $Time === 3 || $Time === 2) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->sendMessage($this->plugin->prefix . "The Map will change in " . $Time . " Seconds!");
                }
            } elseif ($Time === 1) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->sendMessage($this->plugin->prefix . "The Map will change in " . $Time . " Seconds!");
                }
            } elseif ($Time === 0) {
                $config->set("Map", "KnockFFA-2");
                $config->set("MapTime", 901);
                $config->save();
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->teleport($this->plugin->getServer()->getLevelByName($config->get("Map"))->getSafeSpawn());
                }
            }
        } elseif ($config->get("Map") === "KnockFFA-2") {
            if ($Time === 900 || $Time === 600 || $Time === 300) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->sendMessage($this->plugin->prefix . "The Map will change in " . $MinutenTime . " Minutes!");
                }
            } elseif ($Time === 60 || $Time === 30 || $Time === 15 || $Time === 10 || $Time === 5 || $Time === 4 || $Time === 3 || $Time === 2) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->sendMessage($this->plugin->prefix . "The Map will change in " . $Time . " Seconds!");
                }
            } elseif ($Time === 1) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->sendMessage($this->plugin->prefix . "The Map will change in " . $Time . " Seconds!");
                }
            } elseif ($Time === 0) {
                $config->set("Map", "KnockFFA-1");
                $config->set("MapTime", 901);
                $config->save();
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $player->teleport($this->plugin->getServer()->getLevelByName($config->get("Map"))->getSafeSpawn());
                }
            }
        }

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $player->sendTip("§cTeams not allowed");
        }
    }
}