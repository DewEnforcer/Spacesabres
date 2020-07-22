<?php

require "dbh.inc.php";
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["fuel_fleet"])=== FALSE){
$userNick = $_SESSION["sid"];
$sql = "SELECT hyperid, userID FROM users WHERE sessionID='$userNick'";
$getUri = mysqli_query($conn, $sql);
$Uri = mysqli_fetch_assoc($getUri);
$ammount = mysqli_real_escape_string($conn, $_POST["fuel"]);
$countPrice = $ammount * 0.5;
$ID = intval($Uri["userID"]);
if ($countPrice > $Uri["hyperid"]) {
  header("location: ../internalBriefing.php?error=hyperid");
  exit();
} else {
  $newUri = $Uri["hyperid"] - $countPrice;
  $sql = "SELECT fuel FROM userfleet WHERE userID=$ID";
  $getFuel = mysqli_query($conn, $sql);
  $fuel = mysqli_fetch_assoc($getFuel);
  $newFuel = $fuel["fuel"] + $ammount;
  $sql = "UPDATE users SET hyperid=$newUri WHERE sessionID='$userNick'";
  $updateUri = mysqli_query($conn, $sql);
  $sql = "UPDATE userfleet SET fuel=$newFuel WHERE userID=$ID";
  if (mysqli_query($conn, $sql) === TRUE && $updateUri === TRUE) {
   header("location: ../internalBriefing.php?success=1");
   exit();
 } else {
   header("location: ../internalBriefing.php?error=sql");
   exit();
 }
}
} elseif (isset($_SESSION["sid"]) && isset($_POST["fuel_fleet"])) {
  $userNick = $_SESSION["sid"];
  $sql = "SELECT hyperid, userID FROM users WHERE sessionID='$userNick'";
  $getUri = mysqli_query($conn, $sql);
  $Uri = mysqli_fetch_assoc($getUri);
  $ammount = mysqli_real_escape_string($conn, $_POST["fuel"]);
  $countPrice = $ammount * 0.5;
  $ID = intval($Uri["userID"]);
  if ($countPrice > $Uri["hyperid"]) {
    header("location: internalFleets.php?error=uri");
    exit();
  } else {
    $newUri = $Uri["hyperid"] - $countPrice;
    $sql = "SELECT fuel FROM userfleet WHERE userID=$ID";
    $getFuel = mysqli_query($conn, $sql);
    $fuel = mysqli_fetch_assoc($getFuel);
    $newFuel = $fuel["fuel"] + $ammount;
    $sql = "UPDATE users SET hyperid=$newUri WHERE sessionID='$userNick'";
    $updateUri = mysqli_query($conn, $sql);
    $sql = "UPDATE userfleet SET fuel=$newFuel WHERE userID=$ID";
    if (mysqli_query($conn, $sql) === TRUE && $updateUri === TRUE) {
     header("location: internalFleets.php?success=1");
     exit();
   } else {
     header("location: internalFleets.php?error=sql");
     exit();
   }
  }
} else {
  header("location: ../index.php");
  exit();
}
 ?>
