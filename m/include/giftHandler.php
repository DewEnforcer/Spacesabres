<?php
session_start();
if ($_SESSION["sid"]) {
  $sql = mysqli_query($conn, "SELECT userID, tutorial FROM users WHERE sessionID='$_SESSION[sid]'");
  $userInfo = mysqli_fetch_assoc($sql);
  if ($userInfo["tutorial"] == 0) {
    $sql = mysqli_query($conn, "SELECT hornet, spacefire, fuel FROM userFleet WHERE userID=$userInfo[userID]");
    $userFleet = mysqli_fetch_assoc($sql);

    $newHornet = $userFleet["hornet"] + 25;
    $newSpacefire = $userFleet["spacefire"] + 15;
    $newFuel = $userFleetf["fuel"] + 500;
    $sql = mysqli_query($conn, "UPDATE userfleet SET hornet=$newHornet, spacefire=$newSpacefire, fuel=$newFuel WHERe userID=$userInfo[userID]");
    if ($sql !== FALSE) {
      header("location: ../internalStart.php?claim=success");
      exit();
    } else {
      header("location: ../internalStart.php?claim=error");
      exit();
    }
  } else {
    header("location: ../index.php");
    exit();
  }
} else {
  header("location: ../index.php");
  exit();
}

 ?>
