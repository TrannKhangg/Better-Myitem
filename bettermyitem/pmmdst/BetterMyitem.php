<?php
/*
____       _   _              __  __       _ _                 
 | __ )  ___| |_| |_ ___ _ __  |  \/  |_   _(_) |_ ___ _ __ ___  
 |  _ \ / _ \ __| __/ _ \ '__| | |\/| | | | | | __/ _ \ '_ ` _ \ 
 | |_) |  __/ |_| ||  __/ |    | |  | | |_| | | ||  __/ | | | | |
 |____/ \___|\__|\__\___|_|    |_|  |_|\__, |_|\__\___|_| |_| |_|
                                       |___/
      Made By PMMDST                                 
*/

namespace bettermyitem\pmmdst;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\level\sound\Sound;
use pocketmine\level\sound\AnvilUseSound; //Enchantment
use pocketmine\level\sound\AnvilBreakSound; //Can't
use pocketmine\level\sound\BlazeShootSound; //Yes

class BetterMyitem extends PluginBase implements Listener {
  
  public $prefix = "§a[§e Better Myitem §a]";

  public function onEnable(){
    $this->getLogger()->info("
   ____       _   _              __  __       _ _                 
 | __ )  ___| |_| |_ ___ _ __  |  \/  |_   _(_) |_ ___ _ __ ___  
 |  _ \ / _ \ __| __/ _ \ '__| | |\/| | | | | | __/ _ \ '_ ` _ \ 
 | |_) |  __/ |_| ||  __/ |    | |  | | |_| | | ||  __/ | | | | |
 |____/ \___|\__|\__\___|_|    |_|  |_|\__, |_|\__\___|_| |_| |_|
                                       |___/\nEnable :3");
                                       $this->kho = new Config($this->getDataFolder() . "kho.yml", Config::YAML);
  }
  
  public function onDisable(){
    $this->getLogger()->info("
    ____       _   _              __  __       _ _                 
 | __ )  ___| |_| |_ ___ _ __  |  \/  |_   _(_) |_ ___ _ __ ___  
 |  _ \ / _ \ __| __/ _ \ '__| | |\/| | | | | | __/ _ \ '_ ` _ \ 
 | |_) |  __/ |_| ||  __/ |    | |  | | |_| | | ||  __/ | | | | |
 |____/ \___|\__|\__\___|_|    |_|  |_|\__, |_|\__\___|_| |_| |_|
                                       |___/\nDisable :((");
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool{
    switch($cmd->getName()){
      case "mi":
        if($sender instanceof Player){
          if($sender->hasPermission("bettermyitem.command")){
          $this->MyitemUi($sender);
          }else{
            $sender->getLevel()->addSound(new AnvilBreakSound(new Position($sender->getX(), $sender->getY(), $sender->getZ(), $sender->getLevel())));
            $sender->sendMessage($this->prefix . "§c Bạn không có quyền để sử dụng lệnh !");
          }
        }else{
          $sender->sendMessage($this->prefix . "§c Please use this command in game !");
        }
        break;
    }
    return true;
  }
  
  public function MyitemUi($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        return true;
      }
      switch($data){
        case 0:
          $this->ChinhSuaItemForm($player);
          break;
          
          case 1:
            $this->KhoForm($player);
           break;
           
           case 2:
           $this->TinhNangKhacForm($player);
           break;
      }
    });
    $form->setTitle("§e【 §aMENU BETTER-MYITEM §e】");
    $form->addButton("§9• CHỈNH SỬA ĐỒ •");
    $form->addButton("§9• KHO •");
    $form->addButton("§9• CÁC TÍNH NĂNG KHÁC •");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function ChinhSuaItemForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        $this->MyitemUi($player);
        return true;
      }
      switch($data){
        case 0:
          $this->DoiTenForm($player);
          break;
            
            case 1:
              $this->ThemPhuPhepForm($player);
              break;
              
              case 2:
                $this->ThemLoreForm($player);
                break;
      }
    });
    $form->setTitle("§e【 §aCHỈNH SỬA ITEM §e】");
    $form->setContent("§7» Lưu ý: Các item được chỉnh sửa phải cầm trên tay !");
    $form->addButton("§9• ĐỔI TÊN •");
    $form->addButton("§9• THÊM PHÙ PHÉP •");
    $form->addButton("§9• THÊM LORE •");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function ThemPhuPhepForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        $this->ChinhSuaItemForm($player);
        return true;
      }
      if($data[0] === null){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập một thứ gì đó !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if(!is_numeric($data[0])){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập số id bằng chứ số !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if($data[1] === null){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập một thứ gì đó !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if(!is_numeric($data[1])){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập số level bằng chứ số !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if($data[0] < 0){
        $player->sendMessage($this->prefix . "§c Hãy ghi số id của enchantment lớn hơn 0 !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if($data[1] <= 0){
        $player->sendMessage($this->prefix . "§c Hãy ghi số level của enchantment lớn hơn 0 !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if($data[1] > 5000){
        $player->sendMessage($this->prefix . "§c Số level của enchantment này tối đa là 5000 !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if(Enchantment::getEnchantment($data[0])){
        $ec = Enchantment::getEnchantment($data[0]);
          $hand = $player->getInventory()->getItemInHand();
          if($hand->getId() != 0){
            $id = $hand->getId();
            $meta = $hand->getDamage();
            $count = $hand->getCount();
            $item = Item::get($id, $meta, $count);
            $item->addEnchantment(new EnchantmentInstance($ec, $data[1]));
            if($hand->hasEnchantments()){
              foreach($hand->getEnchantments() as $ecs){
                $item->addEnchantment($ecs);
              }
            }
            if($hand->hasCustomName()){
              $item->setCustomName($hand->getCustomName());
            }
            if($hand->getLore() != []){
              $item->setLore($hand->getLore());
            }
            $player->getInventory()->removeItem($hand);
            $player->getInventory()->setItemInHand($item);
            $player->sendMessage($this->prefix . "§a Đã phù phép thành công !");
            $player->getLevel()->addSound(new AnvilUseSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
          }else{
            $player->sendMessage($this->prefix . "§c Hãy cầm một item trên tay để phù phép !"); 
            $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
          }
      }else{
        $player->sendMessage($this->prefix . "§c Enchantment có id là §e" . $data[0] . "§c không tồn tại !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }
    });
    $form->setTitle("§e【 §aTHÊM PHÙ PHÉP §e】");
    $form->addInput("§9• Nhập id enchantment muốn phù phép:");
    $form->addInput("§9• Nhập số level của enchantment muốn phù phép:");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function DoiTenForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        $this->ChinhSuaItemForm($player);
        return true;
      }
      if($data[0] === null){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập một thứ gì đó !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      $hand = $player->getInventory()->getItemInHand();
      if($hand->getId() != 0){
        $id = $hand->getId();
        $meta = $hand->getDamage();
        $count = $hand->getCount();
        $item = Item::get($id, $meta, $count);
        $item->setCustomName($data[0]);
        if($hand->hasEnchantments()){
          foreach($hand->getEnchantments() as $ecs){
            $item->addEnchantment($ecs);
          }
        }
        if($hand->getLore() != []){
          $item->setLore($hand->getLore());
        }
        $player->getInventory()->removeItem($hand);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage($this->prefix . "§a Đã đổi tên item thành công !");
        $player->getLevel()->addSound(new BlazeShootSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }else{
        $player->sendMessage($this->prefix . "§c Hãy cầm một item trên tay để đổi tên !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }
    });
    $form->setTitle("§e【 §aĐỔI TÊN §e】");
    $form->addInput("§9• Nhập tên muốn đổi:");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function ThemLoreForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        $this->ChinhSuaItemForm($player);
        return true;
      }
      if($data[0] === null){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập một thứ gì đó !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      $hand = $player->getInventory()->getItemInHand();
      if($hand->getId() != 0){
        $id = $hand->getId();
        $meta = $hand->getDamage();
        $count = $hand->getCount();
        $item = Item::get($id, $meta, $count);
        $lore = explode("{line}", $data[0]);
        $item->setLore($lore);
        if($hand->hasEnchantments()){
              foreach($hand->getEnchantments() as $ecs){
                $item->addEnchantment($ecs);
              }
            }
            if($hand->hasCustomName()){
              $item->setCustomName($hand->getCustomName());
            }
            $player->getInventory()->removeItem($hand);
            $player->getInventory()->setItemInHand($item);
            $player->getLevel()->addSound(new BlazeShootSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
            $player->sendMessage($this->prefix . "§a Đã thêm lore !");
      }else{
        $player->sendMessage($this->prefix . "§c Hãy cầm một item trên tay để thêm lore !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }
    });
    $form->setTitle("§e【 §aTHÊM LORE §e】");
    $form->addInput("§9• Nhập lore muốn thêm, {line} để xuống dòng:");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function TinhNangKhacForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        $this->MyitemUi($player);
        return true;
      }
      switch($data){
        case 0:
          $this->IdEnchantment($player);
          break;
          
          case 1:
            $hand = $player->getInventory()->getItemInHand();
            if($hand->getId() != 0){
              $player->sendMessage($this->prefix . "§a Id là: §e" . $hand->getId() . ":" . $hand->getDamage());
              $player->getLevel()->addSound(new BlazeShootSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
            }else{
              $player->sendMessage($this->prefix . "§c Hãy cầm một item trên tay để xem id !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
            }
            break;
      }
    });
    $form->setTitle("§e【 §aTÍNH NĂNG BETTER-ITEM §e】");
    $form->addButton("§9• XEM ID CÁC ENCHANTMENT •");
    $form->addButton("§9• XEM ID ITEM TRÊN TAY •");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function IdEnchantment($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        $this->TinhNangKhacForm($player);
        return true;
      }
    });
    $form->setTitle("§e【 §aID CÁC ENCHANTMENT §e】");
    $form->addLabel("§aPROTECTION: 0\n
§aFIRE_PROTECTION: 1\n
§aFEATHER_FALLING: 2\n
§aBLAST_PROTECTION: 3\n
§aPROJECTILE_PROTECTION: 4\n
§aTHORNS: 5\n
§aRESPIRATION: 6\n
§aDEPTH_STRIDER: 7\n
§aAQUA_AFFINITY: 8\n
§aSHARPNESS: 9\n
§aSMITE: 10\n
§aBANE_OF_ARTHROPODS: 11\n
§aKNOCKBACK: 12\n
§aFIRE_ASPECT: 13\n
§aLOOTING: 14\n
§aEFFICIENCY: 15\n
§aSILK_TOUCH: 16\n
§aUNBREAKING: 17\n
§aFORTUNE: 18\n
§aPOWER: 19\n
§aPUNCH: 20\n
§aFLAME: 21\n
§aINFINITY: 22\n
§aLUCK_OF_THE_SEA: 23\n
§aLURE: 24\n
§aFROST_WALKER: 25\n
§aMENDING: 26\n
§aBINDING: 27\n
§aVANISHING: 28\n
§aIMPALING: 29\n
§aRIPTIDE: 30\n
§aLOYALTY: 31\n
§aCHANNELING: 32\n
§aMULTISHOT: 33\n
§a§aPIERCING: 34\n
§aQUICK_CHARGE: 35\n
§aSOUL_SPEED: 36");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function KhoForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        $this->MyitemUi($player);
        return true;
      }
      switch($data){
        case 0:
          $this->ChoDo($player);
          break;
          
          case 1:
            $this->LayDo($player);
            break;
      }
    });
    $form->setTitle("§e【 §aKHO §e】");
    $form->addButton("§9• CHO ĐỒ VÀO KHO §9•");
    $form->addButton("§9• LẤY ĐỒ KHỎI KHO §9•");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function ChoDo($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        $this->KhoForm($player);
        return true;
      }
      if($data[1] === null){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập một thứ gì đó !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      $hand = $player->getInventory()->getItemInHand();
      if($hand->getId() != 0){
        if(!$this->kho->exists($data[1])){
        $this->kho->setNested($data[1] . ".id", $hand->getId());
        $this->kho->setNested($data[1] . ".meta", $hand->getDamage());
        $this->kho->setNested($data[1] . ".count", $hand->getCount());
        $this->kho->save();
        if($hand->hasCustomName()){
          $this->kho->setNested($data[1] . ".name", $hand->getCustomName());
          $this->kho->save();
        }
        if($hand->hasEnchantments()){
          foreach($hand->getEnchantments() as $ecs){
            $this->kho->setNested($data[1] . ".enchantments." . $ecs->getId(), $ecs->getLevel());
            $this->kho->save();
          }
        }
        if($hand->getLore() != []){
          $this->kho->setNested($data[1] . ".lore", $hand->getLore());
          $this->kho->save();
        }
        $player->sendMessage($this->prefix . "§a Đã add đồ vô kho thành công với tên là: §e" . $data[1]);
        $player->getLevel()->addSound(new BlazeShootSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        }else{
          $player->sendMessage($this->prefix . "§c Tên §e" . $data[1] . "§c đã tồn tại !");
        }
      }else{
        $player->sendMessage($this->prefix . "§c Hãy cầm một item trên tay để cho vô kho !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }
    });
    $form->setTitle("§e【 §aCHO ĐỒ VÔ KHO §e】");
    $form->addLabel("§7• Đồ vô kho sẽ là đồ trên tay");
    $form->addInput("§9• Nhập tên muốn đặt cho item:");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function LayDo($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function(Player $player, array $data = null){
      
      if($data === null){
        return true;
      }
      if($data[0] === null){
        $player->sendMessage($this->prefix . "§c Vui lòng nhập một thứ gì đó !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
        return true;
      }
      if($this->kho->exists($data[0])){
        $id = $this->kho->getNested($data[0] . ".id");
        $meta = $this->kho->getNested($data[0] . ".meta");
        $count = $this->kho->getNested($data[0] . ".count");
        $item = Item::get($id, $meta, $count);
        if($this->kho->getNested($data[0] . ".name")){
          $item->setCustomName($this->kho->getNested($data[0] . ".name"));
        }
        if($this->kho->getNested($data[0] . ".enchantments")){
          foreach($this->kho->getNested($data[0] . ".enchantments") as $ecid => $eclevel){
            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($ecid), $eclevel));
          }
        }
        if($this->kho->getNested($data[0] . ".lore")){
          $item->setLore($this->kho->getNested($data[0] . ".lore"));
        }
        $player->getInventory()->addItem($item);
        $player->sendMessage($this->prefix . "§a Đã lấy thành công item §e" . $data[0] . "§a từ kho !");
        $player->getLevel()->addSound(new BlazeShootSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }else{
        $player->sendMessage($this->prefix . "§c Đồ tên là §e" . $data[0] . "§c không tồn tại !");
        $player->getLevel()->addSound(new AnvilBreakSound(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel())));
      }
    });
    $form->setTitle("§e【 §aLẤY ĐỒ §e】");
    $form->addInput("§9• Ghi tên đồ muốn lấy:");
    $form->sendToPlayer($player);
    return $form;
  }
}
