<?php

namespace Clarence2810\Teleport;

use Clarence2810\Teleport\teleport;
use pocketmine\{
    Player,
    scheduler\Task,
    utils\TextFormat as C,
    plugin\PluginBase,
};

;

class PvPTask extends Task
{
    public $timer = 4;
    public function __construct(teleport $main, $playerName){
        $this->main = $main;
        $this->playerName = $playerName;
    }

    public function onRun(int $currentTick)
    {
        $player = $this->main->getServer()->getPlayerExact($this->playerName);
        $level = $this->main->getServer()->getLevelByName("Combo");
        if ($player instanceof Player) {
			if(in_array($player->getName(), teleport::$cancel)) $this->main->getScheduler()->cancelTask($this->getTaskId());
            $this->main->getServer()->loadLevel("Combo");
            if (!$this->main->getServer()->isLevelLoaded("Combo")){
                $player->sendMessage("Lol xD, Something went wrong...");
                $this->main->getLogger()->alert("World 'Combo' not Loaded properly, load it using /mw load Combo");
                $this->main->getScheduler()->cancelTask($this->getTaskId());
                return false;
            }
            $player->addTitle(C::YELLOW . "Going to PvP...");
			$this->timer--;
            $player->sendSubtitle(C::WHITE . "in " . $this->timer);
            if ($this->timer <= 0) {
                $player->teleport($level->getSafeSpawn());
				$player->setGamemode(0);
				$player->setGamemode(0);
                $this->main->getScheduler()->cancelTask($this->getTaskId());
                $player->addTitle(C::BOLD . C::WHITE . "Pot". C::RED . "PvP " . C::WHITE . "Arena");
                $player->sendTip(C::WHITE . "You have been teleported to PvP!");
			}
		}
        else {
            $this->main->getScheduler()->cancelTask($this->getTaskId());
            return false;
			}
	}
}