<?php

namespace ImNotYour5G\KnockFFA\event;

use ImNotYour5G\KnockFFA\Main;
use ImNotYour5G\KnockFFA\task\RettungsplattformTask;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\cheat\PlayerIllegalMoveEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Config;

class EventListener implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }


    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if ($player->getInventory()->getItemInHand()->getCustomName() === "§6Shop") {

            if ($player instanceof Player) {
                if (!InvMenuHandler::isRegistered()) {
                    InvMenuHandler::register($this->plugin);
                }
                $this->plugin->Shop($player);
                $event->setCancelled();
            }

        } elseif ($player->getInventory()->getItemInHand()->getCustomName() === "§7Rettungsplattform") {

            $level = $event->getPlayer()->getLevel();
            $block = Block::get(Block::SLIME_BLOCK);
            $x = $player->getX();
            $y = $player->getY();
            $z = $player->getZ();
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX() + 1;
            $y = $player->getY();
            $z = $player->getZ();
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX() - 1;
            $y = $player->getY();
            $z = $player->getZ();
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX();
            $y = $player->getY();
            $z = $player->getZ() - 1;
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX();
            $y = $player->getY();
            $z = $player->getZ() + 1;
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX() + 1;
            $y = $player->getY();
            $z = $player->getZ() + 1;
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX() - 1;
            $y = $player->getY();
            $z = $player->getZ() - 1;
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX() + 1;
            $y = $player->getY();
            $z = $player->getZ() - 1;
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $x = $player->getX() - 1;
            $y = $player->getY();
            $z = $player->getZ() + 1;
            $y = $y - 6;
            $pos = new Vector3($x, $y, $z);
            $level->setBlock($pos, $block);
            $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $level->getBlock($pos)), 60);
            $player->teleport(new Vector3($pos->x, $pos->y + 2, $pos->z));
            $player->getInventory()->remove(Item::get(Item::BLAZE_ROD, 0, 1));

        }
    }

    public function onDamage(EntityDamageEvent $event)
    {

        if ($event->getEntity() instanceof Player) {
            $entity = $event->getEntity();
            $cause = $event->getCause();

            if ($cause == EntityDamageEvent::CAUSE_FALL) {
                $event->setCancelled(true);
            }

            if ($cause == EntityDamageEvent::CAUSE_PROJECTILE) {

                if ($event instanceof EntityDamageByEntityEvent) {

                    $damager = $event->getDamager();

                    if ($damager instanceof Player) {
                        $damager->teleport($event->getEntity());
                        $event->getEntity()->teleport($damager);
                    }
                }
            }

            if ($cause == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {

                if ($event instanceof EntityDamageByEntityEvent) {

                    $entity->setHealth(20);
                    $entity->setFood(20);

                    $damager = $event->getDamager();

                    if ($damager instanceof Player) {

                        $x = $entity->getX();
                        $y = $entity->getY();
                        $z = $entity->getZ();

                        $xx = $entity->getLevel()->getSafeSpawn()->getX();
                        $yy = $entity->getLevel()->getSafeSpawn()->getY();
                        $zz = $entity->getLevel()->getSafeSpawn()->getZ();
                        $sr = 10;

                        if (abs($xx - $x) < $sr && abs($yy - $y) < $sr && abs($zz - $z) < $sr) {
                            $event->setCancelled();
                            return;
                        }

                        if ($damager->getInventory()->getItemInHand()->getId() == 280) {
                            $this->plugin->lastDmg[strtolower($entity->getName())] = strtolower($damager->getName());
                            return;
                        }
                    }
                }
            }
        }
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();

        $x = $player->getX();
        $y = $player->getY();
        $z = $player->getZ();

        if ($player->getLevel()->getBlock($player->getSide(0))->getID() == 165) {
            $player->setMotion(new Vector3(0, 2.5, 0));
        }

        if ($player->getLevel()->getFolderName() === "KnockFFA-1" || $player->getLevel()->getFolderName() === "KnockFFA-2") {
            if ($player->y < 0) {
                $this->plugin->Respawn($player);
            }
        } else {
            if ($player->y < 50) {
                $this->plugin->Respawn($player);
            }
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $event->setCancelled();
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $event->setCancelled();
    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $event->setCancelled();
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());
        $this->plugin->lastDmg[$name] = "emp ty";
        $this->plugin->lastKillstreak[$name] = 0;

        $this->plugin->giveKit($player);

        $kills = new Config("/home/Datenbank/KnockFFA/kills.yml", Config::YAML);
        if (!$kills->get($player->getName())) {
            $kills->set($player->getName(), 0);
            $kills->save();
        }

        $coins = new Config("/home/Datenbank/KnockFFA/coins.yml", Config::YAML);
        if (!$coins->get($player->getName())) {
            $coins->set($player->getName(), 0);
            $coins->save();
        }

        $config = new Config("/home/Datenbank/KnockFFA/config.yml");
		$this->plugin->getServer()->loadLevel($config->get("Map"));
        $player->teleport($this->plugin->getServer()->getLevelByName($config->get("Map"))->getSafeSpawn());

        $event->setJoinMessage("");
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();

        $event->setQuitMessage("");
    }

    public function onTransaction(Player $player, Item $itemClickedOn, Item $itemClickedWith): bool
    {
        $coins = new Config("/home/Datenbank/KnockFFA/coins.yml", Config::YAML);

        if ($itemClickedOn->getCustomName() == "§7Enderperle §7(§b" . "2 Coins" . "§7)") {
            if (($coins->get($player->getName()) - 2 > -1)) {
                $gcoins = $coins->get($player->getName()) - 2;
                $coins->set($player->getName(), $gcoins);
                $coins->save();

                $item = Item::get($itemClickedOn->getId(), $itemClickedOn->getDamage(), $itemClickedOn->getCount());
                $item->setCustomName("§7Enderperle");
                $player->getInventory()->addItem($item);
                return true;
            } else {
                $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genügend coins.");
            }
        }

        if ($itemClickedOn->getCustomName() == "§7Rettungsplattform §7(§b" . "4 Coins" . "§7)") {
            if (($coins->get($player->getName()) - 4 > -1)) {
                $gcoins = $coins->get($player->getName()) - 4;
                $coins->set($player->getName(), $gcoins);
                $coins->save();

                $item = Item::get($itemClickedOn->getId(), $itemClickedOn->getDamage(), $itemClickedOn->getCount());
                $item->setCustomName("§7Rettungsplattform");
                $player->getInventory()->addItem($item);
                return true;
            } else {
                $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genügend coins.");
            }
        }

        if ($itemClickedOn->getCustomName() == "§7Enterhaken §7(§b" . "6 Coins" . "§7)") {
            if (($coins->get($player->getName()) - 6 > -1)) {
                $gcoins = $coins->get($player->getName()) - 6;
                $coins->set($player->getName(), $gcoins);
                $coins->save();

                $item = Item::get($itemClickedOn->getId(), $itemClickedOn->getDamage(), $itemClickedOn->getCount());
                $item->setCustomName("§2Enterhaken");
                $player->getInventory()->addItem($item);
                return true;
            } else {
                $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genügend coins.");
            }
        }

        if ($itemClickedOn->getCustomName() == "§7Switcher §7(§b" . "8 Coins" . "§7)") {
            $Coins = 8;
            if (($coins->get($player->getName()) - $Coins > -1)) {
                $gcoins = $coins->get($player->getName()) - $Coins;
                $coins->set($player->getName(), $gcoins);
                $coins->save();

                $item = Item::get($itemClickedOn->getId(), $itemClickedOn->getDamage(), $itemClickedOn->getCount());
                $item->setCustomName("§2Switcher");
                $player->getInventory()->addItem($item);
                return true;
            } else {
                $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genügend coins.");
            }
        }
        return false;
    }

    public function onIllegalMove(PlayerIllegalMoveEvent $event)
    {
        $event->setCancelled();
    }

    public function onExhaust(PlayerExhaustEvent $event) {
        $event->getPlayer()->setFood(20);
    }
}