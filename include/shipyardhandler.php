<?php
  session_start();
  if(isset($_GET["send"]) && isset($_GET["shipID"]) && isset($_SESSION["sid"])) {
    require "dbh.inc.php";
    $usernick=mysqli_real_escape_string($conn, $_SESSION['sid']);
    $shipID=mysqli_real_escape_string($conn, $_GET["shipID"]);
    $Ammount=mysqli_real_escape_string($conn, $_GET["ammount"]);

    $typeArray = ["none", "hornet", "spacefire", "starhawk", "peacemaker", "centurion", "nathalis"];

    $fleetPointsArray = ["empty",2,3,5,10,15,25];
    if ($shipID < 7) {
      $fleetPointsPurchase = $Ammount * $fleetPointsArray[$shipID];
    } else {
      $fleetPointsPurchase = 0;
    }

    $getPrice = mysqli_query($conn, "SELECT * FROM shop WHERE shipID='$shipID'");
    $price=mysqli_fetch_assoc($getPrice);
    $getUser = mysqli_query($conn, "SELECT * FROM users WHERE sessionID='$usernick'");
    $user= mysqli_fetch_assoc($getUser);
    if (is_numeric($shipID) == false) {
      header("Location: ../internalShipyard.php?error=params");
      exit();
    }
    if ($shipID<=6){
    $getFleet = mysqli_query($conn, "SELECT * FROM userfleet WHERE userID=$user[userID]");
    $Fleet=mysqli_fetch_assoc($getFleet);
    $newFleetPoints = $fleetPointsPurchase + $Fleet["fleetPoints"];
} else {
  $getFleet = mysqli_query($conn, "SELECT * FROM userbase WHERE userID=$user[userID]");
  $Fleet = mysqli_fetch_assoc($getFleet);
}
    $priceCreds = $Ammount*intval($price["CostCreds"]);
    $priceHyperid = $Ammount*intval($price["CostHyperid"]);
    $priceNatium = $Ammount*intval($price["CostNatium"]);

    $newCreds = intval($user["credits"])-$priceCreds;
    $newHyperid = intval($user["hyperid"])-$priceHyperid;
    $newNatium = intval($user["natium"])-$priceNatium;

    $id = intval($user["userID"]);
    if($newCreds>=0 && $newHyperid>=0 && $newNatium>=0){
      if ($shipID < 7) {
        $sql = mysqli_query($conn, "SELECT dock, dockIncrement FROM userfleet WHERE userID=$user[userID]"); 
        $userDockParams = mysqli_fetch_assoc($sql);

        $userDock = unserialize($userDockParams["dock"]);
        $arrayParams = ["empty", "lasers"=>["placeholder",0,2,4,10,15,25], "rockets"=>["placeholder",4,2,0,0,0,0], "energy"=>["placeholder",1,1,1,2,2,3], "hyperspace"=>["placeholder",1,1,1,1,1,1]];
        for ($i=0; $i < $Ammount; $i++) {
          $shipLasers = [];  //creates ship slots for equipment
          $shipRockets = [];
          $shipEnergy = [];
          $shipHyperspace = [];
          for ($j=0; $j < $arrayParams["lasers"][$shipID]; $j++) {
            array_push($shipLasers, 0);
          }
          for ($j=0; $j < $arrayParams["rockets"][$shipID]; $j++) {
            array_push($shipRockets, 0);
          }
          for ($j=0; $j < $arrayParams["energy"][$shipID]; $j++) {
            array_push($shipEnergy, 0);
          }
          for ($j=0; $j < $arrayParams["hyperspace"][$shipID]; $j++) {
            array_push($shipHyperspace,0);
          } // end of slot creation
          $param = ["number"=> $userDockParams["dockIncrement"], "lasers"=>$shipLasers, "rockets"=>$shipRockets, "energy"=>$shipEnergy, "hyperspace"=>$shipHyperspace];
          array_push($userDock[$typeArray[$shipID]], $param);
          $userDockParams["dockIncrement"]++;
        }
        $queryDock = serialize($userDock);
        $shipBook = mysqli_query($conn, "UPDATE userfleet SET dock='$queryDock', dockIncrement=$userDockParams[dockIncrement] WHERE userID=$user[userID]");
      }
      else {
        if ($shipID == 7) {
        $UpdateCruiser6Ammount=$Fleet["inventoryMod1"]+$Ammount;
        $shipBook = mysqli_query($conn, "UPDATE userbase SET inventoryMod1=$UpdateCruiser6Ammount WHERE userID=$id");
        }
        if ($shipID == 8) {
        $UpdateCruiser6Ammount=$Fleet["inventoryMod2"]+$Ammount;
        $shipBook = mysqli_query($conn, "UPDATE userbase SET inventoryMod2=$UpdateCruiser6Ammount WHERE userID=$id");
        }
        if ($shipID == 9) {
        $UpdateCruiser6Ammount=$Fleet["inventoryMod3"]+$Ammount;
        $shipBook = mysqli_query($conn, "UPDATE userbase SET inventoryMod3=$UpdateCruiser6Ammount WHERE userID=$id");
        }
        if ($shipID == 10) {
        $UpdateCruiser6Ammount=$Fleet["inventoryMod4"]+$Ammount;
        $shipBook = mysqli_query($conn, "UPDATE userbase SET inventoryMod4=$UpdateCruiser6Ammount WHERE userID=$id");
      }
  }
      $sql = mysqli_query($conn, "UPDATE users SET credits=$newCreds, hyperid=$newHyperid, natium=$newNatium WHERE sessionID='$usernick'");
    require "questTaskhandler.php";
  }   else {
      header("Location: ../internalShipyard.php?error=notenoughresources");
      exit();
    }

    if ($sql !== FALSE && $shipBook !== FALSE) {
      header("Location: ../internalShipyard.php?success=shipConstructed");
      exit();
      } else {
        header("location: ../internalShipyard.php?error=sql");
      exit();
   }


} else {
  header("location: ../index.php");
  exit();
}
?>
