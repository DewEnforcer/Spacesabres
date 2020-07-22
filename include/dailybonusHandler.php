<?php
session_start();
if (isset($_SESSION["sid"]) && $_SESSION["sid"] != "") {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, claimed, loginBonusDay FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $claimedLogin = mysqli_fetch_assoc($sql);
    "";
  } else {
    $_SESSION["sid"] = "";
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}
if (isset($_POST["action"]) && $_POST["action"] == "claimLogin") {


if ($claimedLogin["claimed"] == 1) {
  header("location: ../index.php");
  exit();
} elseif ($claimedLogin["claimed"] == 0) {

  $sql = mysqli_query($conn, "SELECT * FROM dailybonus WHERE day=$claimedLogin[loginBonusDay]");
  $dayBonusDetails = mysqli_fetch_assoc($sql);


  function updateItemsSimple($conn, $item, $ammount, $userID, $tablename) {
    $sql = mysqli_query($conn,"SELECT $item FROM $tablename WHERE userID=$userID");
    $resultItems = mysqli_fetch_assoc($sql);

    $newItems = $resultItems[$item] + $ammount;

    $sql = "UPDATE $tablename SET $item=? WHERE userID=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $newItems, $userID);
    if (mysqli_stmt_execute($stmt) !== FALSE) {
      $return = "Items have been succesfully updated";
    } else {
      mysqli_error($conn);
      $return = "An database error has occured";
    }
    return $return;
  }
  function updateItemsFleet ($conn, $itemID, $amount, $userID) {
    $typeArray = ["none", "hornet"=>0, "spacefire"=>1, "starhawk"=>2, "peacemaker"=>3, "centurion"=>4, "nathalis"=>5];
    $fleetPointsArray = ["empty",2,3,5,10,15,25];
    $bonusShips = [];
    $bonusShipsNumbers = [0,0,0,0,0,0];
    $getFleet = mysqli_query($conn, "SELECT fleetPoints, dockIncrement FROM userfleet WHERE userID=$userID");
    $Fleet=mysqli_fetch_assoc($getFleet);
    $gainedFP = $Fleet["fleetPoints"]+0;
    $sql = mysqli_query($conn, "SELECT researchHp FROM userresearch WHERE userID=$userID");
    $userResearch = mysqli_fetch_assoc($sql);
    $arrayParams = ["empty", "lasers"=>["placeholder",0,2,4,12,15,25], "rockets"=>["placeholder",4,2,0,0,0,0], "shields"=>["placeholder",0,0,1,6,7,15], "hyperspace"=>["placeholder",1,1,1,1,1,1]];
          $arrayHp = [10000,12500,15000,60000,72500,175000];
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
            $param = ["number"=>$Fleet["dockIncrement"], "lasers"=>$shipLasers, "rockets"=>$shipRockets, "shields"=>$shipShields, "hyperspace"=>$shipHyperspace, "stats"=>[0,0,$arrayHp[$itemID-1],$arrayEvasion[$itemID-1]], "type"=>$itemID-1];
            array_push($bonusShips, $param);
            $Fleet["dockIncrement"]++;
            $bonusShipsNumbers[$itemID-1]++;
            $gainedFP += $fleetPointsArray[$itemID];
          }
          $queryDock = gzencode(serialize($bonusShips),9);
          $queryNumbers = serialize($bonusShipsNumbers);
          $travelTime = date("U")-1000;
          $travelWay = 0;
          $returnWay = 1;
          $missionType = 4;
          $sql = "INSERT INTO usermovement (userID, fleet, fleetNumbers, travelTime, travelWay, returnWay, missionType) VALUES (?,?,?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt, $sql);
          mysqli_stmt_bind_param($stmt, "issiiii", $userID, $queryDock, $queryNumbers, $travelTime, $travelWay, $returnWay, $missionType);
          mysqli_stmt_execute($stmt);

          $sql = mysqli_query($conn, "UPDATE userfleet SET fleetPoints=$gainedFP, dockIncrement=$Fleet[dockIncrement] WHERE userID=$userID");
          return true;
  }
  function updateItemsEquipment ($conn, $itemID, $amount, $userID) {
    $sql = mysqli_query($conn, "SELECT equipment FROM userfleet WHERE userID=$userID");
    $userEquipment = mysqli_fetch_assoc($sql);

    $userEquipment = unserialize($userEquipment["equipment"]);

    if ($itemID >= 19 && $itemID <= 21) {
      $type = "lasers";
    } elseif ($itemID >= 22 && $itemID <= 24) {
      $type = "hyperspace";
    } elseif ($itemID == 25) {
      $type = "shields";
    } elseif ($itemID == 26) {
      $type = "rockets";
    }

    $userEquipment[$type][$itemID] += $amount;

    $userEquipment = serialize($userEquipment);
    $sql = mysqli_query($conn, "UPDATE userfleet SET equipment='$userEquipment' WHERE userID=$userID");
    return true;
  }
  function decideItem ($conn, $itemID, $userID, $ammount) {
    if ($itemID >= 1 && $itemID <= 6) {
       updateItemsFleet($conn, $itemID, $ammount, $userID);
    } elseif($itemID == 7) {
       updateItemsSimple($conn, "inventoryMod1", $ammount, $userID, "userbase");
    } elseif($itemID == 8) {
       updateItemsSimple($conn, "inventoryMod2", $ammount, $userID, "userbase");
    } elseif($itemID == 9) {
       updateItemsSimple($conn, "inventoryMod3", $ammount, $userID, "userbase");
    } elseif($itemID == 10) {
       updateItemsSimple($conn, "inventoryMod4", $ammount, $userID, "userbase");
    } elseif($itemID == 11) {
       updateItemsSimple($conn, "researchDmg", $ammount, $userID, "userresearch");
    } elseif($itemID == 12) {
       updateItemsSimple($conn, "researchHp", $ammount, $userID, "userresearch");
    } elseif($itemID == 13) {
       updateItemsSimple($conn, "researchShd", $ammount, $userID, "userresearch");
    } elseif($itemID == 14) {
       updateItemsSimple($conn, "researchSpeed", $ammount, $userID, "userresearch");
    } elseif($itemID == 15) {
       updateItemsSimple($conn, "researchSubspace", $ammount, $userID, "userresearch");
    } elseif($itemID == 16) {
       updateItemsSimple($conn, "credits", $ammount, $userID, "users");
    } elseif($itemID == 17) {
       updateItemsSimple($conn, "hyperid", $ammount, $userID, "users");
    } elseif($itemID == 18) {
       updateItemsSimple($conn, "natium", $ammount, $userID, "users");
    } elseif($itemID >= 19 && $itemID <= 26) {
       updateItemsEquipment($conn, "$itemID", $ammount, $userID);
    }
}
  $updateItem1 = decideItem($conn, $dayBonusDetails["itemID1"], $claimedLogin["userID"], $dayBonusDetails["item1Ammount"]);

  $newLoginDay = $claimedLogin["loginBonusDay"]+1;
  $sql = mysqli_query($conn, "UPDATE users SET claimed=1,loginBonusDay=$newLoginDay WHERE userID=$claimedLogin[userID]");
  if ($sql !== FALSE) {
    echo "success";
    exit();
  } else {
    echo "error";
    exit();
  }

}
} elseif (isset($_POST["action"]) && $_POST["action"] == "getNewVal") {

  $sql = mysqli_query($conn, "SELECT credits, hyperid, natium FROM users WHERe userID=$claimedLogin[userID]");
  if ($sql !== FALSE) {
  $userValute = mysqli_fetch_assoc($sql);

  $array= [number_format($userValute["credits"], '0', '.',' '),number_format($userValute["hyperid"], '0', '.',' '),number_format($userValute["natium"], '0', '.',' '), $claimedLogin["userID"]];
  print json_encode($array);
  exit();
} else {
  $array= ["error"];
  print json_encode($array);
  exit();
}
} else {
  header("location: ../index.php");
  exit();
}

 ?>
