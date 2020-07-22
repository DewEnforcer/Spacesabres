<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
  } else {
      $_SESSION["sid"] = "";
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}
if (isset($_POST["newmap"]) && isset($_SESSION["sid"]) ) {
  require "dbh.inc.php";
  $sql = mysqli_query($conn, "SELECT fleetPoints FROM userfleet WHERE userID=$userInfo[userID]");
  $userPoints = mysqli_fetch_assoc($sql);
  if ($userPoints["fleetPoints"] == 0) {
    $userPoints["fleetPoints"] = 1;
  }
  $sql = "SELECT * FROM userfleet WHERE mapLocation=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "s", $_POST["newmap"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while ($ResultID = mysqli_fetch_assoc($result)) {
    if ($ResultID["fleetPoints"] == 0) {
      $ResultID["fleetPoints"] = 1;
    }
    $sql = mysqli_query($conn, "SELECT userclan FROM users WHERE userID=$ResultID[userID]");
    $planetClan = mysqli_fetch_assoc($sql);
    if ($ResultID["userID"] == $userInfo["userID"]) {
      $type = "User";
    } elseif ($planetClan["userclan"] == $userInfo["userclan"] && $userInfo["userclan"] != "none") {
      $type = "Ally";
    } else {
      if ($userPoints["fleetPoints"] < $ResultID["fleetPoints"]*10) {
        $type = "Enemy";
      } else {
        $type = "Passive";
      }
    }


    echo '<img src="../image/graphics/galaxybase'.$type.'.png" alt="planet" class="planet" style="cursor:pointer;position:absolute; left:'.$ResultID["pageCoordsX"].'px; top:'.$ResultID["pageCoordsY"].'px" id='.$type.'type'.$ResultID["pageCoordsX"].'y'.$ResultID["pageCoordsY"].'>';
  }
  $system = "systems".ceil($_POST["newmap"]/100);
  $status = 1;
  $sql = "SELECT * FROM $system WHERE map=? AND status=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "si", $_POST["newmap"], $status);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while ($ResultID = mysqli_fetch_assoc($result)) {
    echo '<img src="../image/graphics/npc.png" alt="planet" class="planet" style="cursor:pointer;position:absolute; left:'.$ResultID["coordsX"].'px; top:'.$ResultID["coordsY"].'px" id="npctype'.$ResultID["coordsX"].'y'.$ResultID["coordsY"].'">';
  }
  exit();
} else {
  echo "An error has occured #ID11";
}

 ?>
