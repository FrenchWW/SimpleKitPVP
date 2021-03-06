<?php

namespace Overiew;

// commands
use Overiew\Commands\Rekit;

// classes
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use muqsit\invmenu\metadata\MenuMeta;
use muqsit\invmenu\InvMenu;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info("Plugin Is Online");
        $this->Commandloadder();
        $this->database();
    }
    public function Commandloadder(){
        $this->getServer()->getCommandMap()->register("rekit", new Rekit($this));
    }

    public function database(){
        $this->db2=new \SQLite3($this->getDataFolder()."SilexDATA.db");
        $this->db=new \SQLite3($this->getDataFolder()."TempBans.db");
        $this->db->exec("CREATE TABLE IF NOT EXISTS banPlayers(player TEXT PRIMARY KEY, banTime INT, reason TEXT, staff TEXT);");
        $this->db->exec("CREATE TABLE IF NOT EXISTS banPlayers(player TEXT PRIMARY KEY, banTime INT, reason TEXT, staff TEXT);");
        $this->db2->exec("CREATE TABLE IF NOT EXISTS PlayerStats(Player TEXT PRIMARY KEY, Kills INT, Deaths INT, Hits INT, Killstreak INT, Elo INT);");
        $this->db2->exec("CREATE TABLE IF NOT EXISTS Queues(Player TEXT PRIMARY KEY, Ladder TEXT, Type TEXT);");
        $this->db2->exec("CREATE TABLE IF NOT EXISTS PlayerInfo(Player TEXT PRIMARY KEY, TimesReported INT, ReportedCheating INT, ReportedExploiting INT, ReportedHarrassment INT, ReportedDisrespect INT, ReportedRacism INT, ReportedExtensiveToxicity INT, ReportedHacking INT, ReportedDirectTeaming INT, ReportedImpersonation INT, ReportedProvokingPlayers INT, ReportedOther INT, Rank TEXT, CustomTag TEXT, Tag1 TEXT, Tag2 TEXT, Tag3 TEXT);");
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setJoinMessage(TextFormat::GREEN . "[+] " . $name);
        $this->Nodebuff($player);
        $player->sendMessage("§a━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $player->sendMessage(" ");
        $player->sendMessage("§l§7INFORMATION§8:§r");
        $player->sendMessage(" ");
        $player->sendMessage("                §l§bCUBE§9LESS");
        $player->sendMessage(" ");
        $player->sendMessage("§r§6Welcome back to our server§e $name,§6 we hope you enjoy and as always please leave your suggestions in our discord!\n\n");
        $player->sendMessage("       §e§l/info§r§a for a list of commands");
        $player->sendMessage("       §e§l/rekit§r§a to get your kit back");
        $player->sendMessage("       §e§l/hub§r§a to teleport back on the main hub");
        $player->sendMessage("       §e§l/spawn§r§a to get back to spawn");
        $player->sendMessage("");
        $player->sendMessage("§a━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }

    public function onRespawn(PlayerRespawnEvent $event)
    {
        $player = $event->getPlayer();
        $this->Nodebuff($player);

    }

    public function noFallDamage(EntityDamageEvent $event)
    {
        if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
            $event->setCancelled(true);
        }
    }

    public function onPlayerDeath(PlayerDeathEvent $event){
        $player=$event->getPlayer();
        $playername=$event->getPlayer()->getName();
        $cause=$player->getLastDamageCause();
        $pots = 0;
        $pots++;
        if($cause instanceof EntityDamageByEntityEvent){
            $killer= $cause->getDamager();
            $killer->setHealth(20);
            $killername=$killer->getName();
            $messages=["clapped", "oofed", "slammed", "given an L", "demolished", "smashed", "verzided", "dumped", "recked", "killed", "Given The L", "LED"];
            $randomchoicemessages=$messages[array_rand($messages)];
            $event->setDeathMessage("§7".$playername." was ".$randomchoicemessages." by ".$killername);
        }else{
            $event->setDeathMessage("§7".$playername." died" . $pots);
        }
        $event->setDrops([]);
    }

    public function onLeave(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setQuitMessage(TextFormat::RED . "[-] " . $name);
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

    public function playerinventory(PlayerRespawnEvent $event)
    {
        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent and $cause->getDamager() instanceof Player) {
            $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST)
                ->readonly()
                ->setName("Player`s Inventory")
                ->setListener(function(Player $player, Item $itemTakenOut, Item $itemPuttingIn, SlotChangeAction $action) : void{

                });
            $killer = $cause->getDamager()->getPlayer();
            $player = $event->getPlayer();
            $content = $killer->getInventory()->getContents();
            foreach($content as $index => $item){
                $menu->getInventory()->setItem($index, $item);

                /** @var Player $player */
                $menu->send($player);
            }
        }
    }

    public function sendLobbyItem(Player $player)
    {
        $player->getInventory()->clearAll();
        $player->setFood(20);
        $player->setHealth(20);
        $player->setGamemode(2);
        $player->removeAllEffects();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(276)->setCustomName("§eDuels §8[USE]"));
        $player->getInventory()->setItem(1, Item::get(286)->setCustomName("§eFFA §8[USE]"));
        $player->getInventory()->setItem(8, Item::get(397)->setCustomName("§eProfile §8[USE]"));
        $player->getInventory()->setItem(4, Item::get(421)->setCustomName("§eCosemtics §8[USE]"));
    }
    public function generateCapeData(string $name): string
    {
        $path = $this->getDataFolder() . $name . ".png";
        $img = @imagecreatefrompng($path);
        $bytes = '';
        $l = (int)@getimagesize($path)[1];
        for ($y = 0; $y < $l; $y++) {
            for ($x = 0; $x < 64; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }

    /**
     * @param Player $player
     * @param string $cape
     */
    public function setCage(Player $player, string $cape): void
    {
        $player->sendMessage(TextFormat::GREEN . "You've set your cape to " . TextFormat::WHITE . $cape . TextFormat::AQUA . " cape.");
        $oldSkin = $player->getSkin();
        $capeData = $this->generateCapeData($cape);
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $player->setSkin($setCape);
        $player->sendSkin();
    }
}