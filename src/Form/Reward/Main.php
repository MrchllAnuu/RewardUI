<?php

namespace Form\Reward;

use pocketmine\plugin\PluginBase;
use pocketmine\events\Listener;
use pocketmine\player\Player;
use pocketmine\Server;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use jojoe77777\FormAPI\SimpleForm;
class Main extends PluginBase
{

    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

        if($command->getName() == "rui"){
            if($sender instanceof Player){
                $this->newSimpleForm($sender);
            } else {
                $sender->sendMessage("Run Command In-game Only");
            }
        }

        return true;
    }

    public function newSimpleForm($player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }

            switch($data){
                case 0:
                    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("cmd1"));
                break;
                case 1:
                    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("cmd2"));
                break;
                case 2:
                    $this->vote($player);
                break;
            }

        });
        $form->setTitle($this->getConfig()->get("title"));
        $form->setContent($this->getConfig()->get("content"));
        $form->addButton($this->getConfig()->get("button1"), 0, "textures/ui/promo_holiday_gift_long");
        $form->addButton($this->getConfig()->get("button2"), 0, "textures/ui/promo_holiday_gift_long");
        $form->addButton($this->getConfig()->get("vote-button"), 0, "textures/ui/promo_holiday_gift_long");
        $form->sendToPlayer($player);
        return $form;
    }
    public function vote($player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }

            switch($data){
                case 0:
                    $this->getServer()->getCommandMap()->dispatch($player, "vote");
                break;
                case 1:
                    $this->newSimpleForm($player);
                break;
            }

        });
        $form->setTitle($this->getConfig()->get("vote-title"));
        $form->setContent($this->getConfig()->get("vote-content"));
        $form->addButton("§l§eVOTE SERVER§\n§r§7§oClick To Claim", 0, "textures/ui/refresh_light");
        $form->addButton("§l§4BACK\n§r§7§oClick To Back", 0, "textures/ui/refresh_light");
        $form->sendToPlayer($player);
        return $form;
    }
}
