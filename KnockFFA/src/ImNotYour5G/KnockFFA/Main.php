<?php

namespace ImNotYour5G\KnockFFA;

use ImNotYour5G\KnockFFA\entity\FishingHook;
use ImNotYour5G\KnockFFA\event\EventListener;
use ImNotYour5G\KnockFFA\item\FishingRod;
use ImNotYour5G\KnockFFA\task\MapTask;
use ImNotYour5G\KnockFFA\task\ScoreboardTask;
use muqsit\invmenu\InvMenu;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\sound\ClickSound;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{

    public $prefix = "§bKnockFFA §r§8» §7";

    public $lastDmg = [];
    public $lastKillstreak = [];

    private static $fishing = [];

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->loadLevel("KnockFFA-2");
        $level = $this->getServer()->getLevelByName("KnockFFA-2");
        $level->setTime(0);
        $level->stopTime();

        $this->getServer()->loadLevel("KnockFFA-1");
        $level = $this->getServer()->getLevelByName("KnockFFA-1");
        $level->setTime(0);
        $level->stopTime();

        $this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this), 20 * 2);
        $this->getScheduler()->scheduleRepeatingTask(new MapTask($this), 20);

        ItemFactory::registerItem(new FishingRod(), true);
        Entity::registerEntity(FishingHook::class, false, ['FishingHook', 'minecraft:fishinghook']);
        @mkdir("/home/Datenbank/KnockFFA");
        $config = new Config("/home/Datenbank/KnockFFA/config.yml");
        $config->set("Status", "Stats");
        $config->set("ScoreboardTime", 5);
        $config->set("Map", "KnockFFA-2");
        $config->set("MapTime", 901);
        $config->set("Spieler", 0);
        $config->save();
    }

    public function Respawn(Player $player)
    {
        $name = strtolower($player->getName());
        $level = $player->getLevel();
        $player->teleport($level->getSafeSpawn());

        $kills = new Config("/home/Datenbank/KnockFFA/kills.yml", Config::YAML);
        $coins = new Config("/home/Datenbank/KnockFFA/coins.yml", Config::YAML);

        if ($this->lastDmg[$name] == "emp ty") {
            $player->addTitle("§c✖", "", 10, 10, 10);

            if ($coins->get($player->getName()) <= 0) {

            } else {
                $gcoins = $coins->get($player->getName()) - 1;
                $coins->set($player->getName(), $gcoins);
                $coins->save();
            }
        } else {
            $dname = $this->lastDmg[$name];

            $damager = $this->getServer()->getPlayer($dname);
            $this->lastKillstreak[$dname] = $this->lastKillstreak[$dname] + 1;
            $ks = [5, 10, 15, 20, 25, 30, 40, 50];
            if (in_array($this->lastKillstreak[$dname], $ks)) {
                foreach ($level->getPlayers() as $p) {
                    //$p->sendMessage($this->prefix . "");
                }
            }
            $player->addTitle("§c✖", $damager->getName(), 10, 10, 10);

            if ($coins->get($player->getName()) <= 0) {

            } else {
                $gcoins = $coins->get($player->getName()) - 1;
                $coins->set($player->getName(), $gcoins);
                $coins->save();
            }

            $damager->addTitle("§a✔", $player->getName(), 10, 10, 10);

            $gkills = $kills->get($damager->getName()) + 1;
            $kills->set($damager->getName(), $gkills);
            $kills->save();

            $gcoins = $coins->get($damager->getName()) + 2;
            $coins->set($damager->getName(), $gcoins);
            $coins->save();
        }

        $this->giveKit($player);
        $this->lastDmg[$name] = "emp ty";
        $this->lastKillstreak[$name] = 0;
    }

    public function giveKit(Player $player)
    {

        $player->setHealth(20);
        $player->setFood(20);

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $stick = Item::get(280, 0, 1);
        $stick->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(12), 1));
        $player->getInventory()->setItem(0, $stick);

        $shop = Item::get(54, 0, 1);
        $shop->setCustomName("§6Shop");
        $player->getInventory()->setItem(8, $shop);

        $player->removeAllEffects();
        $player->addEffect(new EffectInstance(Effect::getEffect(1), 99999, 1, false));

        $player->getLevel()->addSound(new ClickSound($player));
    }

    public function Shop(Player $player)
    {
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readonly();
        $menu->setName(" ");
        $minv = $menu->getInventory();

        $enderperle = Item::get(Item::ENDER_PEARL, 0, 1);
        $enderperle->setCustomName("§7Enderperle §7(§b" . "2 Coins" . "§7)");

        $rettungsplattform = Item::get(Item::BLAZE_ROD, 0, 1);
        $rettungsplattform->setCustomName("§7Rettungsplattform §7(§b" . "4 Coins" . "§7)");

        $enterhaken = Item::get(Item::FISHING_ROD, 0, 1);
        $enterhaken->setCustomName("§7Enterhaken §7(§b" . "6 Coins" . "§7)");

        $switcher = Item::get(Item::SNOWBALL, 0, 1);
        $switcher->setCustomName("§7Switcher §7(§b" . "8 Coins" . "§7)");
        //$minv->setItem(6, $switcher);

        $minv->setItem(3, $enderperle);
        $minv->setItem(4, $rettungsplattform);
        $minv->setItem(5, $enterhaken);

        $menu->send($player);
        $menu->setListener([new EventListener($this), "onTransaction"]);
    }

    public static function getFishingHook(Player $player) : ?FishingHook{
        return self::$fishing[$player->getName()] ?? null;
    }

    public static function setFishingHook(?FishingHook $fish, Player $player){
        self::$fishing[$player->getName()] = $fish;
    }

    public function Scoreboard(Player $player, bool $msg = FALSE)
    {
        $kills = new Config("/home/Datenbank/KnockFFA/kills.yml", Config::YAML);
        $coins = new Config("/home/Datenbank/KnockFFA/coins.yml", Config::YAML);

        $this->createScoreboard($player, "§e§lKNOCKFFA", "SCOREBOARD");
        $this->setScoreboard($player, 1, " ", "SCOREBOARD");
        $this->setScoreboard($player, 2, "§eName", "SCOREBOARD");
        $this->setScoreboard($player, 3, "§7 §8» §7" . $player->getDisplayName(), "SCOREBOARD");
        $this->setScoreboard($player, 4, "  ", "SCOREBOARD");
        $this->setScoreboard($player, 5, "§eKills:", "SCOREBOARD");
        if ($kills->get($player->getName()) <= 0) {
            $this->setScoreboard($player, 6, "§7 §8» §7" . "N/A", "SCOREBOARD");
        } else {
            $this->setScoreboard($player, 6, "§7 §8» §7" . $kills->get($player->getName()), "SCOREBOARD");
        }
        $this->setScoreboard($player, 7, "   ", "SCOREBOARD");
        $this->setScoreboard($player, 8, "§eCoins:", "SCOREBOARD");
        $this->setScoreboard($player, 9, "§7 §8» §7" . $coins->get($player->getName()), "SCOREBOARD");
    }

    public function setScoreboard(Player $player, int $score, string $msg, string $objName)
    {
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $objName;
        $entry->type = 3;
        $entry->customName = " $msg   ";
        $entry->score = $score;
        $entry->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = 0;
        $pk->entries[$score] = $entry;
        $player->sendDataPacket($pk);
    }

    public function createScoreboard(Player $player, string $title, string $objName, string $slot = "sidebar", $order = 0)
    {
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = $slot;
        $pk->objectiveName = $objName;
        $pk->displayName = $title;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = $order;
        $player->sendDataPacket($pk);
    }

    public function removeScoreboard(Player $player, string $objName)
    {
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $objName;
        $player->sendDataPacket($pk);
    }
}