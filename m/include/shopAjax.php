<?php
  session_start();
  if (isset($_POST["item"]) && isset($_POST["amount"]) && isset($_POST["action"]) && isset($_SESSION["sid"])) {
    require "dbh.inc.php";
    $session = $_SESSION["sid"];
    $sql = mysqli_query($conn, "SELECT userID, credits, hyperid, natium FROM users WHERE sessionID='$session'");
    $userInfo = mysqli_fetch_assoc($sql);

    if ($userInfo["userID"] < 1) {
      echo "error";
      exit();
    }

    $action = $_POST["action"];
    $itemID = $_POST["item"];
    $amount = $_POST["amount"];

    if (is_numeric($itemID) == false) {
      echo "error";
      exit();
    }

    $getPrice = "SELECT * FROM shop WHERE shipID=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $getPrice);
    mysqli_stmt_bind_param($stmt, "s", $itemID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $price= mysqli_fetch_assoc($result);

    if ($action == "ship") {
      $_POST["additional"] = json_decode($_POST["additional"]);
      $dockNumber = $_POST["additional"][0];
      $template = $_POST["additional"][1];
      $typeArray = ["none", "hornet"=>0, "spacefire"=>1, "starhawk"=>2, "peacemaker"=>3, "centurion"=>4, "nathalis"=>5];
      $fleetPointsArray = ["empty",2,3,5,10,15,25];

      $getFleet = mysqli_query($conn, "SELECT * FROM userfleet WHERE userID=$userInfo[userID]");
      $Fleet=mysqli_fetch_assoc($getFleet);
      $sql = mysqli_query($conn, "SELECT researchHp FROM userresearch WHERE userID=$userInfo[userID]");
      $userResearch = mysqli_fetch_assoc($sql);
      $newFleetPoints = $fleetPointsArray[$itemID]*$amount + $Fleet["fleetPoints"];

      $priceCreds = $amount*intval($price["CostCreds"]);
      $priceHyperid = $amount*intval($price["CostHyperid"]);
      $priceNatium = $amount*intval($price["CostNatium"]);

      $newCreds = intval($userInfo["credits"])-$priceCreds;
      $newHyperid = intval($userInfo["hyperid"])-$priceHyperid;
      $newNatium = intval($userInfo["natium"])-$priceNatium;


      if($newCreds>=0 && $newHyperid>=0 && $newNatium>=0){
        if (is_numeric($dockNumber) == false || $dockNumber > 10 || $dockNumber < 1) {
          echo "error";
          exit();
        }
        $dock = "dock$dockNumber";
          $sql = mysqli_query($conn, "SELECT dockIncrement, dockAmount, $dock, equipment, templates FROM userfleet WHERE userID=$userInfo[userID]");
          $userDockParams = mysqli_fetch_assoc($sql);
          $equipment = unserialize($userDockParams["equipment"]);
          $dockUn = unserialize(gzdecode($userDockParams[$dock]));
          $count = count($dockUn);
          $templates = unserialize(gzdecode($userDockParams["templates"]));
          if ($count <= 1000 - $amount) {
            $arrayParams = ["empty", "lasers"=>["placeholder",0,2,4,12,15,25], "rockets"=>["placeholder",4,2,0,0,0,0], "shields"=>["placeholder",0,0,1,6,7,15], "hyperspace"=>["placeholder",1,1,1,1,1,1]];
            $arrayHp = [10000,12500,20000,60000,72500,225000];
            $itemParams = ["0"=>0, "19"=>200, "20"=>300, "21"=>400, "25"=>5000, "26"=>250]; //dmg , shd params
            for ($i=0; $i < 6; $i++) {
              $arrayHp[$i] = $arrayHp[$i] * ($userResearch["researchHp"]*0.1+1);
            }
            $arrayEvasion = [0.20,0.20,0.3,0.1,0.1,0.05];
            for ($i=0; $i < $amount; $i++) {
              $shipLasers = [];  //creates ship slots for equipment
              $shipRockets = [];
              $shipShields = [];
              $shipHyperspace = [];
              for ($j=0; $j < $arrayParams["lasers"][$itemID]; $j++) {
                array_push($shipLasers, 0);
              }
              for ($j=0; $j < $arrayParams["rockets"][$itemID]; $j++) {
                array_push($shipRockets, 0);
              }
              for ($j=0; $j < $arrayParams["shields"][$itemID]; $j++) {
                array_push($shipShields, 0);
              }
              for ($j=0; $j < $arrayParams["hyperspace"][$itemID]; $j++) {
                array_push($shipHyperspace,0);
              } // end of slot creation                                                                                                                                             dmg, shd, hp , evasion
              $param = ["number"=>$userDockParams["dockIncrement"], "lasers"=>$shipLasers, "rockets"=>$shipRockets, "shields"=>$shipShields, "hyperspace"=>$shipHyperspace, "stats"=>[0,0,$arrayHp[$itemID-1],$arrayEvasion[$itemID-1]], "type"=>$itemID-1];
              if ($template == "true") { // if user selects template, equips the ship with according to it
                if (empty($templates[$itemID-1]) == false) {
                  $st = 0; //slot type index
                  $i = 0; //slot
                  $slotArr = ["lasers", "rockets", "shields", "hyperspace"];
                  foreach ($templates[$itemID-1] as $slotType) {
                    $i = 0;
                    foreach ($slotType as $slot) {
                      if ($equipment[$slotArr[$st]][$slot] > 0) {
                        $equipment[$slotArr[$st]][$slot]--;
                        $param[$slotArr[$st]][$i] = $slot;
                        if ($slot == 19 || $slot == 20 || $slot == 21 || $slot == 26) {
                          $param["stats"][0] += $itemParams[$slot];
                        } elseif ($slot == 25) {
                          $param["stats"][1] += $itemParams[$slot];
                        }
                      }
                      $i++;
                    }
                    $st++;
                  }
                }
              }
              array_push($dockUn, $param);
              $userDockParams["dockIncrement"]++;
            }
            $queryDock = gzencode(serialize($dockUn),9);
            $queryEquip = serialize($equipment);
            $shipBook = "UPDATE userfleet SET $dock=?, dockIncrement=?, fleetPoints=?, equipment=? WHERE userID=?";
            $stmt = mysqli_stmt_init($conn);
            $check = mysqli_stmt_prepare($stmt, $shipBook);
            mysqli_stmt_bind_param($stmt, "siisi", $queryDock, $userDockParams["dockIncrement"], $newFleetPoints, $queryEquip , $userInfo["userID"]);
            mysqli_stmt_execute($stmt);

         $sql = mysqli_query($conn, "UPDATE users SET credits=$newCreds, hyperid=$newHyperid, natium=$newNatium WHERE userID=$userInfo[userID]");
         require "questTaskhandler.php";
        echo "success";
        exit();
      } else {
        echo "full";
        exit();
      }
    }
  } elseif ($action == "modules") {
    $sql = mysqli_query($conn, "SELECT inventoryMod1, inventoryMod2, inventoryMod3, inventoryMod4 FROM userbase WHERE userID=$userInfo[userID]");
    $userBase = mysqli_fetch_assoc($sql);

    switch ($itemID) {
      case 7:
      $inventory = "inventoryMod1";
      break;
      case 8:
      $inventory = "inventoryMod2";
      break;
      case 9:
      $inventory = "inventoryMod3";
      break;
      case 10:
      $inventory = "inventoryMod4";
      break;
    }

    $priceCreds = $amount*intval($price["CostCreds"]);
    $priceHyperid = $amount*intval($price["CostHyperid"]);
    $priceNatium = $amount*intval($price["CostNatium"]);

    $newCreds = intval($userInfo["credits"])-$priceCreds;
    $newHyperid = intval($userInfo["hyperid"])-$priceHyperid;
    $newNatium = intval($userInfo["natium"])-$priceNatium;

    $newMod = $userBase[$inventory] + $amount;

    if ($newCreds>=0 && $newHyperid>=0 && $newNatium>=0) {
      $sql = mysqli_query($conn, "UPDATE userbase SET $inventory=$newMod WHERE userID=$userInfo[userID]");
      $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newCreds, hyperid=$newHyperid, natium=$newNatium WHERE userID=$userInfo[userID]");

      if ($sql !== FALSE && $sql1 !== FALSE) {
        echo "success";
              exit();
      } else {
        echo "error";
              exit();
      }
    } else {
      echo "error";
      exit();
    }
  } elseif ($action == "equipment") {
    $sql = mysqli_query($conn, "SELECT equipment FROM userfleet WHERE userID=$userInfo[userID]");
    $userEquipment = mysqli_fetch_assoc($sql);

    if ($itemID > 18 && $itemID < 22) {
      $type = "lasers";
    } elseif ($itemID > 21 && $itemID < 25) {
      $type = "hyperspace";
    } elseif ($itemID == 25) {
      $type = "shields";
    } elseif ($itemID == 26) {
      $type = "rockets";
    }else {
      echo "error";
      exit();
    }

    $equipment = unserialize($userEquipment["equipment"]);
    $equipment[$type][$itemID] += $amount;
    $newEquipment = serialize($equipment);
    $priceCreds = $amount*intval($price["CostCreds"]);
    $priceHyperid = $amount*intval($price["CostHyperid"]);
    $priceNatium = $amount*intval($price["CostNatium"]);

    $newCreds = intval($userInfo["credits"])-$priceCreds;
    $newHyperid = intval($userInfo["hyperid"])-$priceHyperid;
    $newNatium = intval($userInfo["natium"])-$priceNatium;

    if ($newCreds>=0 && $newHyperid>=0 && $newNatium>=0) {
      $sql = mysqli_query($conn, "UPDATE userfleet SET equipment='$newEquipment' WHERE userID=$userInfo[userID]");
      $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newCreds, hyperid=$newHyperid, natium=$newNatium WHERE userID=$userInfo[userID]");

      if ($sql !== FALSE && $sql1 !== FALSE) {
        echo "success";
        exit();
      } else {
        echo "error";
        exit();
      }
    } else {
      echo "error";
      exit();
    }

  } elseif ($action == "dock") {
    $sql = mysqli_query($conn, "SELECT dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
    $docks = mysqli_fetch_assoc($sql);

    if ($docks["dockAmount"] == 10) {
      echo "max";
      exit();
    }
    $priceCreds = $amount*intval($price["CostCreds"]);
    $priceHyperid = $amount*intval($price["CostHyperid"]);
    $priceNatium = $amount*intval($price["CostNatium"]);

    $newCreds = intval($userInfo["credits"])-$priceCreds;
    $newHyperid = intval($userInfo["hyperid"])-$priceHyperid;
    $newNatium = intval($userInfo["natium"])-$priceNatium;

    $newDock = $docks["dockAmount"]+1;

    if ($newCreds>=0 && $newHyperid>=0 && $newNatium>=0) {
      $sql = mysqli_query($conn, "UPDATE userfleet SET dockAmount = $newDock WHERE userID = $userInfo[userID]");
      $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newCreds, hyperid=$newHyperid, natium=$newNatium WHERE userID=$userInfo[userID]");
      if ($sql !== FALSE && $sql1 !== false) {
        echo "success";
        exit();
      } else {
        echo "error";
        exit();
      }
    } else {
      echo "error";
      exit();
    }

  } elseif ($action == "fuel") {
    $sql = mysqli_query($conn, "SELECT fuel FROM userfleet WHERE userID=$userInfo[userID]");
    $fuel = mysqli_fetch_assoc($sql);

    $newFuel = $fuel["fuel"]+$amount;

    $priceCreds = $amount*intval($price["CostCreds"]);
    $priceHyperid = $amount*intval($price["CostHyperid"])/2;
    $priceNatium = $amount*intval($price["CostNatium"]);

    $newCreds = intval($userInfo["credits"])-$priceCreds;
    $newHyperid = intval($userInfo["hyperid"])-$priceHyperid;
    $newNatium = intval($userInfo["natium"])-$priceNatium;

    if ($newCreds>=0 && $newHyperid>=0 && $newNatium>=0) {
      $sql = "UPDATE userfleet SET fuel = ? WHERE userID = ?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ii", $newFuel, $userInfo["userID"]);
      mysqli_stmt_execute($stmt);


      $sql1 = mysqli_query($conn, "UPDATE users SET credits=$newCreds, hyperid=$newHyperid, natium=$newNatium WHERE userID=$userInfo[userID]");
      if ($sql !== FALSE) {
        echo "success";
        exit();
      } else {
        echo "error";
        exit();
      }
    } else {
      echo "error";
      exit();
    }
  } else {
    echo "error";
    exit();
  }

  } else {
    echo "error";
    exit();
  }

 ?>
