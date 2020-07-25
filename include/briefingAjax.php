<?php
require "accessSecurity.php";
function fuelConsumption($ships, $shipData) {
  return $ships * $shipData["fuel_consumption"];
}
function travelTime($distance, $selectedShips, $shipData) {
  $slowestSpeed = 10000000;
  $distanceIncreasment = 100;
  foreach ($selectedShips as $key => $value) {
    if ($value <= 0) {
      continue;
    }
    $shipSpeed = $shipData[$key+1]["speed"];
    if ($shipSpeed < $slowestSpeed) {
      $slowestSpeed = $shipSpeed;
    }
  }
  return ($distance * $distanceIncreasment / $slowestSpeed) * 60;
}
if (isset($_POST["action"])) {
  $action = $_POST["action"];
  if ($action == "deploy") {
    $data = json_decode($_POST["data"], true);
    $map = $data[1];
    $x = $data[2];
    $y = $data[3];
    $mission = $data[4]; 
    foreach ($data[0] as $key => $value) { //check if user isnt sending negative amount of ships or unwanted data type
      if ($value < 0 || !is_numeric($value)) {
        echo "noships";
        exit();
      }
    }
    if (array_sum($data[0]) <= 0) { //check if user sent at least 1 ship
      echo "noships";
      exit();
    }
    if (!is_numeric($x) || !is_numeric($y) || !is_numeric($map) || !is_numeric($mission)) {
      echo "notfound";
      exit();
    }
    $sql = "SELECT userID, admin FROM userfleet WHERE pageCoordsX=? AND pageCoordsY=? AND mapLocation=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "error";
      exit();
    }
    mysqli_stmt_bind_param($stmt, "iii", $x, $y, $map);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $target = mysqli_fetch_assoc($result);
    if (!isset($target["userID"])) {
      echo "notfound";
      exit();
    }
    if ($target["admin"] == 1) {
      echo "notfound";
      exit();
    }
    $sql = mysqli_query($conn, "SELECT fleet, pageCoordsX, pageCoordsY, mapLocation, fuel FROM userfleet WHERE userID=$userInfo[userID]");
    $userFleet = mysqli_fetch_assoc($sql);
    $fleetShips = json_decode($userFleet["fleet"], true);
    //calc fuel consumption, travel time
    $fuelRequired = 0;
    $shipData = json_decode(file_get_contents("../js/shipInfo.json"), true)["ships"];
    $totalDistance = abs($userFleet["pageCoordsX"] - $x) + abs($userFleet["pageCoordsY"] - $y) + (abs($userFleet["mapLocation"] - $map) * 100);
    foreach ($data[0] as $key => $value) {
      $fuelRequired += fuelConsumption($value, $shipData[$key+1]) * $totalDistance;
    }
    $userFleet["fuel"] -= $fuelRequired;
    if ($userFleet["fuel"] < 0) {
      echo "no_fuel";
      exit();
    }
    $travelTime = travelTime($totalDistance, $data[0], $shipData) + date("U");
    //subtract selected ships from total amount of ships
    foreach ($data[0] as $key => $value) {
      $fleetShips[$key] -= $value;
    }
    $data[0] = json_encode($data[0]);
    $sql = "INSERT INTO usermovement (userID, targetID, X, Y, Map, travelTime, missionType, attackFleet) VALUES (?, ?, ?, ?, ?, ?, ?, ? )";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "error";
      exit();
    }
    mysqli_stmt_bind_param($stmt, "iiiiiiis", $userInfo["userID"], $target["userID"], $x, $y, $map, $travelTime, $mission, $data[0]);
    mysqli_stmt_execute($stmt);
    echo "success";
    exit();
  }
}
 ?>
