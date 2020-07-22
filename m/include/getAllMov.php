<?php
session_start();
require "dbh.inc.php";
  // ↓ gets ID and users base coords
$session = $_SESSION["sid"];
$sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
if (mysqli_num_rows($sql) == 0) {
  $_SESSION["sid"] = "";
  header("Location: ../index.php");
  exit();
}
$ID = mysqli_fetch_assoc($sql);

$sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE userID=$ID[userID]");
$userCoords = mysqli_fetch_assoc($sql);
if (isset($_SESSION["sid"]) && isset($_POST["index"]) && $_POST["index"] == "get_all_mov") {
  $timeArray = [[], [], []];
  //↓checks for all attacks started by user
  $sql = mysqli_query($conn, "SELECT travelTime FROM usermovement WHERE userID=$ID[userID] AND travelWay=1 ORDER BY setAttack ASC");
  if(mysqli_num_rows($sql) > 0) {
    $index = 0;
    while ($userMovementTime = mysqli_fetch_assoc($sql)) {
      $timeArray[0][$index] = date('Y-m-d G:i:s', ($userMovementTime["travelTime"] )); //7200 for time zone on live server
      $index++;
    }
  } else {
    $timeArray[0][0] = "empty";
  }
  //↓ checks for all users returning fleet
  $sql = mysqli_query($conn, "SELECT travelTime FROM usermovement WHERE userID=$ID[userID] AND returnWay=1 AND missionType=2 ORDER BY setAttack ASC");
  if(mysqli_num_rows($sql) > 0) {
    $index = 0;
    while ($userMovementTime = mysqli_fetch_assoc($sql)) {
      $timeArray[1][$index] = date('Y-m-d G:i:s', ($userMovementTime["travelTime"] ));
      $index++;
    }
  } else {
    $timeArray[1][0] = "empty";
  }
  //↓ checks for any attacks heading to user
  $sql = mysqli_query($conn, "SELECT travelTime FROM usermovement WHERE attackedUserX=$userCoords[pageCoordsX] AND attackedUserY=$userCoords[pageCoordsY] AND targetMapLocation=$userCoords[mapLocation]");
  if (mysqli_num_rows($sql)>0) {
    $index = 0;
    while ($enemyAttacks = mysqli_fetch_assoc($sql)) {
      $timeArray[2][$index] = date('Y-m-d G:i:s',($enemyAttacks["travelTime"] ));
      $index++;
    }
  } else {
    $timeArray[2][0] = "empty";
  }

  print json_encode($timeArray);

} elseif (isset($_SESSION["sid"]) && isset($_POST["index"]) && $_POST["index"] == "getspecificdetails" && isset($_POST["id"]) && isset($_POST["type"])) {

  $recordSelect = $_POST["id"];
  $travelType = $_POST["type"];
  if ($travelType == "attack") {
  $sql = mysqli_query($conn, "SELECT fleetNumbers FROM usermovement WHERE userID=$ID[userID] AND travelWay=1 LIMIT $recordSelect,1");
  $movementDetails = mysqli_fetch_assoc($sql);
} elseif ($travelType == "return") {
  $sql = mysqli_query($conn, "SELECT fleetNumbers FROM usermovement WHERE userID=$ID[userID] AND returnWay=1 LIMIT $recordSelect,1");
  $movementDetails = mysqli_fetch_assoc($sql);
} elseif ($travelType == "enemy_attack") {
  $sql = mysqli_query($conn, "SELECT fleetNumbers FROM usermovement WHERE attackedUserX=$userCoords[pageCoordsX] AND attackedUserY=$userCoords[pageCoordsY] AND targetMapLocation=$userCoords[mapLocation] LIMIT $recordSelect,1");
  $movementDetails = mysqli_fetch_assoc($sql);
} else {
  echo "error";
  exit();
}
$movementDetails["fleetNumbers"] = unserialize($movementDetails["fleetNumbers"]);
$movementDetails["attack1"] = $movementDetails["fleetNumbers"][0]." Hornet(s)";
$movementDetails["attack2"] = $movementDetails["fleetNumbers"][1]." Spacefire(s)";
$movementDetails["attack3"] = $movementDetails["fleetNumbers"][2]." Starhawk(s)";
$movementDetails["attack4"] = $movementDetails["fleetNumbers"][3]." Peacemaker(s)";
$movementDetails["attack5"] = $movementDetails["fleetNumbers"][4]." Centurion(s)";
$movementDetails["attack6"] = $movementDetails["fleetNumbers"][5]." Na-Thalis destroyer(s)";

print json_encode($movementDetails);
exit();
} else {
  echo "An error has occured ID#11";
  exit();
}
 ?>
