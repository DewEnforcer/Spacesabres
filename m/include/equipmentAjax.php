<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_SESSION["shipEquipmentID"]) && isset($_POST["action"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql) > 0) {
    $userInfo = mysqli_fetch_assoc($sql);
  } else {
    echo "error"; // TODO: add removal of sessions
    exit();
  }
  $action = $_POST["action"];
  if (isset($_POST["newEq"]) && isset($_POST["newInv"])) {
    "";
  } else {
    echo "error1";
    exit();
  }
  $newEquipment = json_decode($_POST["newEq"]);
  $newInventory = json_decode($_POST["newInv"]);
  $type = $_SESSION["shipEquipmentType"];
  $shipID = $_SESSION["shipEquipmentID"];

  if ($action == "equip") {
    $dockNumber = $_SESSION["dockNumber"];
    $dockSelect = "dock$dockNumber";
    $sql = mysqli_query($conn, "SELECT $dockSelect, equipment FROM userfleet WHERE userID=$userInfo[userID]");
    $userFleetParams = mysqli_fetch_assoc($sql);

    $userDock = unserialize(gzdecode($userFleetParams[$dockSelect]));
    $userInventory = unserialize($userFleetParams["equipment"]);

    $i = 0;
    $status;
    foreach ($userDock as $key) {
      if (is_array($key) == false) {
        $i++;
        continue;
      }
      if ($key["number"] == $shipID) {
        $status = true;
        break;
      }
      $i++;
    }
    $shipEquipped = $userDock[$i];
    // now find changes
    $t = 0; //type of item
    $p = 0; // position in the type
    $typeArr = ["lasers", "rockets", "shields", "hyperspace"]; // this convert js array into php version
    $itemParams = ["0"=>0, "19"=>200, "20"=>300, "21"=>400, "25"=>5000, "26"=>250]; //dmg , shd params
    $statsArr = ["lasers"=>0, "shields"=>1, "rockets"=>0];
    foreach ($shipEquipped as $key) {
      if ($t > 3) {
        break;
      }
      if (is_array($key) === true) {
        foreach ($key as $slot) {
          if ($slot == $newEquipment[$t][$p]) {
            $p++;
            continue;
          } else {
            if ($newEquipment[$t][$p] != 0 && $userInventory[$typeArr[$t]][$newEquipment[$t][$p]]-1 < 0) { // check if the new slot isnt empty before checking if user has enough for example lasers
                echo "error";
                exit();
            } else { // remove the item from inventory if the new slot isnt empty
              if ($newEquipment[$t][$p] != 0) {
                $userInventory[$typeArr[$t]][$newEquipment[$t][$p]] -= 1;
              }
              // move the old item into inventory
              if ($slot != 0) {
                $userInventory[$typeArr[$t]][$slot] += 1;
              }
              // now change the slot
              $slotPre = $userDock[$i][$typeArr[$t]][$p];
              $slotPost = $newEquipment[$t][$p];
              if ($typeArr[$t] != "hyperspace") {
                $userDock[$i]["stats"][$statsArr[$typeArr[$t]]] -= $itemParams[$slotPre];
                $userDock[$i]["stats"][$statsArr[$typeArr[$t]]] += $itemParams[$slotPost];
              }
              $userDock[$i][$typeArr[$t]][$p] = $newEquipment[$t][$p];
            }
          }

          $p++;
        }
        $t++;
        $p = 0;
      }
    }
    $serializedDock = gzencode(serialize($userDock));
    $serializedEquipment = serialize($userInventory);
   $sql = "UPDATE userfleet SET equipment=?, $dockSelect=? WHERE userID=?";
    $stmt = mysqli_stmt_init($conn);
    $check = mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $serializedEquipment, $serializedDock, $userInfo["userID"]);
    mysqli_stmt_execute($stmt);
    if ($check !== false) {
      echo "success";
      exit();
    } else {
      echo "error";
      exit();
    }

  } elseif ($action == "template") {

    //hornet, spacefire etc
    // lasers, rockets, shields, hyperspace
    $shipSlotsArr = [[0,4,0,1],[2,2,0,1],[4,0,1,1],[10,0,6,1],[15,0,7,1],[25,0,15,1]];
    foreach ($newEquipment as $typeSlot) {
      if (count($typeSlot) > $shipSlotsArr[$type]) {
        echo "error";
        exit();
      }
    }
    $sql = mysqli_query($conn, "SELECT templates FROM userfleet WHERE userID=$userInfo[userID]");
    $templates = mysqli_fetch_assoc($sql);
    $templates = unserialize(gzdecode($templates["templates"]));

    $templates[$type] = $newEquipment;

    $templates = gzencode(serialize($templates));
    $sql = "UPDATE userfleet SET templates=? WHERE userID=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql) == false) {
      echo "error";
      exit();
    }
    mysqli_stmt_bind_param($stmt, "si", $templates, $userInfo["userID"]);
    mysqli_stmt_execute($stmt);

    echo "success";
    exit();
  }
}

 ?>
