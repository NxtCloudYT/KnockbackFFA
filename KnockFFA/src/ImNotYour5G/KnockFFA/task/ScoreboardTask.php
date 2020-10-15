<?php

namespace ImNotYour5G\KnockFFA\task;

use ImNotYour5G\KnockFFA\Main;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class ScoreboardTask extends Task
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $this->plugin->removeScoreboard($player, "SCOREBOARD");
            $this->plugin->Scoreboard($player, true);
        }

        $config = new Config("/home/Datenbank/KnockFFA/config.yml");
        $config->set("Spieler", count($this->plugin->getServer()->getOnlinePlayers()));
        $config->save();

        /*$config = new Config($this->plugin->getDataFolder() . "config.yml");
        $config->set("ScoreboardTime", $config->get("ScoreboardTime") - 1);
        $config->save();

        $Time = $config->get("ScoreboardTime");

        if ($config->get("Status") === "Stats") {
            if ($Time === 5 || $Time === 4 || $Time === 3 || $Time === 1) {

                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {

                    $this->plugin->removeScoreboard($player, "SCOREBOARD");
                    $this->plugin->Scoreboard($player, true);
                }

            } elseif ($Time === 0) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $this->plugin->removeScoreboard($player, "SCOREBOARD");
                    $this->plugin->Scoreboard1($player, true);
                }
                $config->set("Status", "Werbung");
                $config->set("ScoreboardTime", 5);
                $config->save();
            }
        } elseif ($config->get("Status") === "Werbung") {
            if ($Time === 5 || $Time === 4 || $Time === 3 || $Time === 1) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $this->plugin->removeScoreboard($player, "SCOREBOARD1");
                    $this->plugin->Scoreboard1($player, true);
                }
            } elseif ($Time === 0) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                    $this->plugin->removeScoreboard($player, "SCOREBOARD1");
                    $this->plugin->Scoreboard($player, true);
                }
                $config->set("Status", "Stats");
                $config->set("ScoreboardTime", 5);
                $config->save();
            }
        }*/

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $player->sendTip("§cTeams not allowed");
        }
    }
}