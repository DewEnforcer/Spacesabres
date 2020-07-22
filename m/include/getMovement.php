<?php
session_start();
require "dbh.inc.php";
  $Nickname = $_SESSION["sid"];
  $sql = "SELECT userID, playTime, lastUpdate FROM users WHERE sessionID='$Nickname'";
  $getID = mysqli_query($conn, $sql);
  if (mysqli_num_rows($getID) == 0) {
        $_SESSION["sid"] = "";
    header("Location: ../index.php");
    exit();
  }
  $ID = mysqli_fetch_assoc($getID);

if (isset($_SESSION["sid"]) && isset($_POST["index"]) && $_POST["index"]=="get_mov") {
$timeCurrent = date("Y-m-d G:i:s");
$time = mysqli_query($conn, "SELECT attackedUserX,	attackedUserY,	targetMapLocation,	attack1,	attack2,	attack3,	attack4,	attack5,	attack6, travelTime FROM usermovement WHERE userID=$ID[userID] AND travelWay=1 ORDER BY travelTime ASC");
$array = [[],[]];
if (mysqli_num_rows($time) > 0) {
$getTravel = mysqli_fetch_assoc($time);
$index = 0;
foreach ($getTravel as $Travel) {
  if ($index == 9) {
    $array[0][$index] = date('Y-m-d G:i:s',$Travel);
    $index++;
  } else {
  $array[0][$index] = $Travel;
  $index++;
}
}
} else {
  $array[0][0] = "empty";
}

$sql = mysqli_query($conn, "SELECT attackedUserX,	attackedUserY,	targetMapLocation,	attack1,	attack2,	attack3,	attack4,	attack5,	attack6,	travelTime FROM usermovement WHERE userID=$ID[userID] AND returnWay=1 ORDER BY travelTime ASC");
if (mysqli_num_rows($sql) > 0) {
  $getReturn = mysqli_fetch_assoc($sql);
  $index = 0;
  foreach ($getReturn as $Return) {
    if ($index == 9) {
      $array[1][$index] = date('Y-m-d G:i:s',$Return);
      $index++;
    } else {
    $array[1][$index] = $Return;
    $index++;
  }
  }
} else {
  $array[1][0] = "empty";
}

$sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE userID=$ID[userID]");
$userCoords = mysqli_fetch_assoc($sql);
$sql = mysqli_query($conn, "SELECT userID , attack1,	attack2,	attack3,	attack4,	attack5,	attack6 FROM usermovement WHERE attackedUserX=$userCoords[pageCoordsX] AND attackedUserY=$userCoords[pageCoordsY] AND targetMapLocation=$userCoords[mapLocation]");
if (mysqli_num_rows($sql)> 0) {
  $array[2][0] = "You are under attack";
} else {
  $array[2][0] = "no attacks";
}

print json_encode($array);
} elseif (isset($_SESSION["sid"]) && isset($_POST["index"])  && $_POST["index"]=="checkAttacks") {
  $sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE userID=$ID[userID]");
  $userCoords = mysqli_fetch_assoc($sql);
  $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE attackedUserX=$userCoords[pageCoordsX] AND attackedUserY=$userCoords[pageCoordsY] AND targetMapLocation=$userCoords[mapLocation] AND travelWay=1");
  if (mysqli_num_rows($sql) > 0) {
    echo "1";
  } else {
    echo "0";
  }
  $timeNow = date("U");
  $timeToAddReal = $timeNow - $ID["lastUpdate"];
  $timeNewReal = $ID["playTime"] + $timeToAddReal;

  $sql = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$ID[userID]");
  $userMissions = mysqli_fetch_assoc($sql);

  $unserializeUser = unserialize($userMissions["userObjectives"]);
  if ($userMissions["currentQuest"] > 0) {
    $sql = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$userMissions[currentQuest]");
    $missions = mysqli_fetch_assoc($sql);

    $unserializeTemplate = unserialize($missions["objectives"]);
    if ($unserializeTemplate[23] > 0) {
      $unserializeUser[23] += $timeToAddReal;
      $serialize = serialize($unserializeUser);

      $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$ID[userID]");
    } else {
      "";
    }
  }
  $sql = mysqli_query($conn, "UPDATE users SET playTime=$timeNewReal, lastUpdate=$timeNow WHERE userID=$ID[userID]");
  exit();
} elseif (isset($_SESSION["sid"]) && isset($_POST["index"]) && isset($_POST["id"]) && $_POST["index"]=="getspecificdetailsuser") {
  $movementID = mysqli_real_escape_string($conn, $_POST["id"]);
  $sql = mysqli_query($conn, "SELECT attack1, attack2, attack3, attack4, attack5, attack6 FROM usermovement WHERE userID=$ID[userID] LIMIT $movementID,0 ");
  $movementDetails = mysqli_fetch_assoc($sql);
  print json_encode($movementDetails);
  exit();
} else {
  echo "An error has occured ID#11";
  exit();
}
 ?>
