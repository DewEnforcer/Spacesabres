<?php
session_start();
//if (isset($_POST["getRes"])  && $_POST["getRes"]=="research" && isset($_SESSION["sid"]) && isset($_POST["research"])) {
require "dbh.inc.php";
$session = $_SESSION["sid"];

if ($_POST["research"] == 11) {
  $research = "researchDmg";
  $resParamTypeCreds = "dmgPriceCreds";
  $resParamTypeHyperid = "dmgPriceHyperid";
} elseif ($_POST["research"] == 12) {
  $research = "researchHp";
  $resParamTypeCreds = "hpPriceCreds";
  $resParamTypeHyperid = "hpPriceHyperid";
} elseif ($_POST["research"] == 13) {
  $research = "researchShd";
  $resParamTypeCreds = "shdPriceCreds";
  $resParamTypeHyperid = "shdPriceHyperid";
} elseif ($_POST["research"] == 14) {
  $research = "researchSpeed";
  $resParamTypeCreds = "speedPriceCreds";
  $resParamTypeHyperid = "speedPriceHyperid";
} else {
  echo "An error has occured #152";
  echo $_POST["research"];
  exit();
}
$getID = mysqli_query($conn, "SELECT userID from users WHERE sessionID='$session'");
$ID = mysqli_fetch_assoc($getID);
$convertID = intval($ID["userID"]);
$sql = mysqli_query($conn ,"SELECT $research FROM userresearch WHERE userID=$convertID");
$lvlRes = mysqli_fetch_assoc($sql);

$getResTime = $lvlRes["$research"]+1;

$getResParams = mysqli_query($conn, "SELECT $resParamTypeCreds, $resParamTypeHyperid, researchTime FROM research WHERE level=$getResTime");
$resParams = mysqli_fetch_assoc($getResParams);

echo ''.$resParams["$resParamTypeCreds"].' Credits, and '.$resParams["$resParamTypeHyperid"].' Hyperids';
}  elseif (isset($_POST["getRes"])  && $_POST["getRes"]=="lvl" && isset($_SESSION["sid"]) && isset($_POST["research"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];

  if ($_POST["research"] == 11) {
    $research = "researchDmg";
    $resParamTypeCreds = "dmgPriceCreds";
    $resParamTypeHyperid = "dmgPriceHyperid";
  } elseif ($_POST["research"] == 12) {
    $research = "researchHp";
    $resParamTypeCreds = "hpPriceCreds";
    $resParamTypeHyperid = "hpPriceHyperid";
  } elseif ($_POST["research"] == 13) {
    $research = "researchShd";
    $resParamTypeCreds = "shdPriceCreds";
    $resParamTypeHyperid = "shdPriceHyperid";
  } elseif ($_POST["research"] == 14) {
    $research = "researchSpeed";
    $resParamTypeCreds = "speedPriceCreds";
    $resParamTypeHyperid = "speedPriceHyperid";
  } else {
    echo "An error has occured #152";
    echo $_POST["research"];
    exit();
  }
  $getID = mysqli_query($conn, "SELECT userID from users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);
  $sql = mysqli_query($conn ,"SELECT $research, currentResearch FROM userresearch WHERE userID=$convertID");
  $lvlRes = mysqli_fetch_assoc($sql);

  $getResTime = $lvlRes["$research"]+1;
  // credits, hyperid, natium, time

  print json_encode($resInfo);

//} else {
//  echo "An error has occured #152/1";
//  exit();
//}

 ?>
