<?php

namespace Overiew\Commands;

use Overiew\Main;
use pocketmine\command\Command;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\CommandSender;

class Rekit extends PluginCommand{

    public function __construct (Main $main)
    {
        parent::__construct("spawn",$main);
        $this->main = $main;
        $this->setDescription("Teleport back to spawn !");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if ($sender instanceof Player) {
            $this->Nodebuff($sender);
        }
    }
    public function Nodebuff(Player $p)
    {
        $p->getArmorInventory()->clearAll();
        $p->getInventory()->clearAll();
        $sword = Item::get(Item::DIAMOND_SWORD, 0, 1);
        $pearl = Item::get(Item::ENDER_PEARL, 0, 16);
        $potions = Item::get(Item::SPLASH_POTION, 22, 64);
        $helmet = Item::get(Item::DIAMOND_HELMET, 0, 1);
        $chest = Item::get(Item::DIAMOND_CHESTPLATE, 0, 1);
        $thicklegs = Item::get(Item::DIAMOND_LEGGINGS, 0, 1);
        $footfungus = Item::get(Item::DIAMOND_BOOTS, 0, 1);
        $tool = Enchantment::getEnchantment(Enchantment::UNBREAKING);
        $toolenchant = new EnchantmentInstance($tool, 5);
        $armorenchant = Enchantment::getEnchantment(Enchantment::UNBREAKING);
        $enchantarmor = new EnchantmentInstance($armorenchant, 5);
        $helmet->addEnchantment($enchantarmor);
        $chest->addEnchantment($enchantarmor);
        $thicklegs->addEnchantment($enchantarmor);
        $footfungus->addEnchantment($enchantarmor);
        $sword->addEnchantment($toolenchant);
        $p->getInventory()->addItem($sword);
        $p->getArmorInventory()->setHelmet($helmet);
        $p->getArmorInventory()->setChestplate($chest);
        $p->getArmorInventory()->setLeggings($thicklegs);
        $p->getArmorInventory()->setBoots($footfungus);
        $p->getInventory()->addItem($pearl);
        $p->getInventory()->addItem($potions);
        $p->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 999999, 0));
    }
}