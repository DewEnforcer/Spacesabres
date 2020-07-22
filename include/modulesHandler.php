<?php
session_start();
if (isset($_POST["setModules"]) && isset($_SESSION["sid"])) {
  require "dbh.inc.php";

  if (isset($_POST["device"])) {
    if ($_POST["device"] == "mobile") {
      $header = "m/";
    } else {
      $header = "";
    }
  } else {
    $header = "";
  }

  $userNick = $_SESSION["sid"];
  $sql = "SELECT * FROM users WHERE sessionID='$userNick'";
        $getID = mysqli_query($conn, $sql);
        $ID = mysqli_fetch_assoc($getID);

$module1 = $_POST["module1"];
$module2 = $_POST["module2"];
$module3 = $_POST["module3"];
$module4 = $_POST["module4"];
$module5 = $_POST["module5"];
$module6 = $_POST["module6"];
$module7 = $_POST["module7"];
$module8 = $_POST["module8"];
$module9 = $_POST["module9"];
$module10 = $_POST["module10"];

$allModules = array($module1, $module2, $module3, $module4, $module5, $module6, $module7, $module8,$module9, $module10);
$countModule1= count(array_keys($allModules, 1));
$countModule2= count(array_keys($allModules, 2));
$countModule3= count(array_keys($allModules, 3));
$countModule4= count(array_keys($allModules, 4));

$getInventory = mysqli_query($conn,"SELECT inventoryMod1, inventoryMod2, inventoryMod3, inventoryMod4 FROM userbase WHERE userID=$ID[userID]");
$inventory = mysqli_fetch_assoc($getInventory);

if ($countModule1 <= $inventory["inventoryMod1"]) {
  $inventory1= 1;
}
if ($countModule2 <= $inventory["inventoryMod2"]) {
  $inventory2= 1;
}
if ($countModule3 <= $inventory["inventoryMod3"]) {
  $inventory3= 1;
}
if ($countModule4 <= $inventory["inventoryMod4"]) {
  $inventory4= 1;
}
if ($inventory1 > 0 && $inventory2 > 0 && $inventory3 > 0 && $inventory4 > 0 ) {
  $sql = "UPDATE userbase SET slot1=?,slot2=?,slot3=?,slot4=?,slot5=?,slot6=?,slot7=?,slot8=?,slot9=?,slot10=? WHERE userID=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "iiiiiiiiiii", $module1, $module2, $module3, $module4, $module5, $module6, $module7, $module8, $module9, $module10, $ID["userID"]);
  if (mysqli_stmt_execute($stmt) === TRUE) {
    header("location: ../".$header."internalBase.php?success=moduleset");
    exit();
  } else {
    header("location: ../".$header."internalBase.php?error=sql");
    exit();
  }

} else {
  header("location: ../".$header."internalBase.php?error=notenoughmodules");
  exit();
}




} else {
  header("location: ../".$header."index.php");
  exit();
}
 ?>
