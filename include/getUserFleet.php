<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["index"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID from users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($sql);

  $getShips = mysqli_query($conn, "SELECT hornet, spacefire, starhawk, peacemaker, centurion, nathalis FROM userfleet WHERE userID=$ID[userID]");
  if ($getShips !== FALSE) {
  $ships = mysqli_fetch_assoc($getShips);

  $shipAmmount = [];
  $shipAmmount[0] = $ships["hornet"];
  $shipAmmount[1] = $ships["spacefire"];
  $shipAmmount[2] = $ships["starhawk"];
  $shipAmmount[3] = $ships["peacemaker"];
  $shipAmmount[4] = $ships["centurion"];
  $shipAmmount[5] = $ships["nathalis"];

  print json_encode($shipAmmount);
  exit();
}
echo "An error has occured ID#12";
exit();
} elseif (isset($_SESSION["sid"]) && isset($_POST["get"]) && $_POST["get"] == "stats") {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID from users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($sql);

  $sql = mysqli_query($conn, "SELECT destroyedPoints, destroyedShips, destroyedHornets, destroyedSpacefires, destroyedStarhawks, destroyedPeacemakers, destroyedCenturions, destroyedNathalis, battlesWon, battlesLost, battlesDraw, battlesTotal FROM userfleet WHERE userID=$ID[userID]");
  if ($sql !== FALSE) {
    $userStats = mysqli_fetch_assoc($sql);
    $index = 0;
    $array = [[], []];
    foreach ($userStats as $userStatistics) {
      $array[0][$index] = $userStatistics;
      $index++;
    }
    $array[1][0] = "Points of destruction in total:";
    $array[1][1] = "Destroyed ships in total:";
    $array[1][2] = "Destroyed hornets in total:";
    $array[1][3] = "Destroyed spacefires in total:";
    $array[1][4] = "Destroyed starhawks in total:";
    $array[1][5] = "Destroyed peacemakers in total:";
    $array[1][6] = "Destroyed centurions in total:";
    $array[1][7] = "Destroyed nathalis destroyers in total:";
    $array[1][8] = "Battles won in total:";
    $array[1][9] = "Battles lost in total:";
    $array[1][10] = "Draw battles in total:";
    $array[1][11] = "Battles in total:";

    print json_encode($array);
  } else {
    echo "An error has occured ID#11";
    exit();
  }
} else {
  echo "An error has occured ID#11";
  exit();
}
 ?>
