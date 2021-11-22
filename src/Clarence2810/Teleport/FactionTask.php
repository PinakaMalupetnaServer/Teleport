<?php

namespace Clarence2810\Teleport;

use Clarence2810\Teleport\teleport;
use pocketmine\{
    Player,
    scheduler\Task,
    utils\TextFormat as C,
    plugin\PluginBase,
	level\Position,
};

;

class FactionTask extends Task
{
    public $timer = 4;
    public function __construct(teleport $main, $playerName){
        $this->main = $main;
        $this->playerName = $playerName;
    }

    public function onRun(int $currentTick)
    {
        $player = $this->main->getServer()->getPlayerExact($this->playerName);
        $level = $this->main->getServer()->getLevelByName("server-spawn");
        if ($player instanceof Player) {
			if(in_array($player->getName(), teleport::$cancel)) $this->main->getScheduler()->cancelTask($this->getTaskId());
            $this->main->getServer()->loadLevel("server-spawn");
            if (!$this->main->getServer()->isLevelLoaded("server-spawn")){
                $player->sendMessage("Lol xD, Something went wrong...");
                $this->main->getLogger()->alert("World 'server-spawn' not Loaded properly, load it using /mw load POTPVP");
                $this->main->getScheduler()->cancelTask($this->getTaskId());
                return false;
            }
            $player->addTitle(C::YELLOW . "Going to Factions...");
			$this->timer--;
            $player->sendSubtitle(C::WHITE . "in " . $this->timer);
            if ($this->timer <= 0) {
				// $loc = new Position(-1736, 86, -202, $level);
                $player->teleport($level->getSafeSpawn());
				$player->setGamemode(0);
				$player->setGamemode(0);
                $this->main->getScheduler()->cancelTask($this->getTaskId());
                $player->addTitle(C::BOLD . C::RED . "FACTIONS ");
                $player->sendTip(C::WHITE . "You have been teleported to Factions!");
			}
		}
        else {
            $this->main->getScheduler()->cancelTask($this->getTaskId());
            return false;
			}
	}
	
	public function onDamage(EntityDamageEvent $event) {
    $victim = $event->getEntity();
	if($victim->getLevelByName() == "server-spawn") {
    if($event->getFinalDamage() >= $victim->getHealth()) {
        $event->setCancelled();
        // Now the player can't die!
        // I would advise you set the players health back to full and handle the respawn in a way that won't confuse players (TP to spawn, clear inv, etc)
		}
	}
}
}