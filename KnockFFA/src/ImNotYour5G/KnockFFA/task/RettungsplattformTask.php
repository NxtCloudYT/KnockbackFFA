<?php


namespace ImNotYour5G\KnockFFA\task;

use ImNotYour5G\KnockFFA\Main;
use pocketmine\block\Block;
use pocketmine\level\particle\DustParticle;
use pocketmine\scheduler\Task;

class RettungsplattformTask extends Task
{
    public $plugin;
    public $block;
    public $mode;

    public function __construct(Main $plugin, Block $block, $mode = 0)
    {

        $this->plugin = $plugin;
        $this->block = $block;
        $this->mode = $mode;
    }

    public function onRun(int $tick)
    {
        $pos = $this->block->asPosition();
        if ($this->block->getLevel()->getBlock($pos)->getId() == Block::get(Block::SLIME_BLOCK)->getId()) {
            if ($this->mode == 0) {
                $this->block->getLevel()->setBlock($pos, Block::get(Block::SLIME_BLOCK));
                $this->plugin->getScheduler()->scheduleDelayedTask(new RettungsplattformTask($this->plugin, $this->block, 1), 20);
            } else {
                $this->block->getLevel()->setBlock($pos, Block::get(Block::AIR));
                $this->block->getLevel()->addParticle(new DustParticle($pos, 0, 0, 0));
            }
        }
    }
}