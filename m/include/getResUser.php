<?php
session_start();
$researchArray = ["researchDmg"=>["credits"=>350000,"hyperid"=>175000,"natium"=>0,"researchTime"=>1500], "researchHp"=>["credits"=>500000,"hyperid"=>100000,"natium"=>0,"researchTime"=>1800], "researchShd"=>["credits"=>200000,"hyperid"=>250000,"natium"=>0,"researchTime"=>1300], "researchSpeed"=>["credits"=>150000,"hyperid"=>150000,"natium"=>0,"researchTime"=>1000], "researchSubspace"=>["credits"=>1000000,"hyperid"=>600000,"natium"=>0,"researchTime"=>2400]];
if (isset($_POST["getRes"])  && $_POST["getRes"]=="lvl" && isset($_SESSION["sid"]) && isset($_POST["research"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];

  if ($_POST["research"] == 11) {
    $research = "researchDmg";
  } elseif ($_POST["research"] == 12) {
    $research = "researchHp";
  } elseif ($_POST["research"] == 13) {
    $research = "researchShd";
  } elseif ($_POST["research"] == 14) {
    $research = "researchSpeed";
  } elseif ($_POST["research"] == 15) {
    $research = "researchSubspace";
  } else {
    echo "An error has occured #152";
    exit();
  }
  $getID = mysqli_query($conn, "SELECT userID from users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);
  $sql = mysqli_query($conn ,"SELECT $research, currentResearch FROM userresearch WHERE userID=$ID[userID]");
  $lvlRes = mysqli_fetch_assoc($sql);

  $level = $lvlRes[$research]+1;
  if ($level == 1) {
    $coeficient = 1;
  } elseif ($level == 2) {
    $coeficient = 2;
  } else {
    $coeficient = $level * 2;
  }

  $preConvertTime = $researchArray[$research]["researchTime"] * $coeficient;
  if ($preConvertTime >= 86400) {
    $days = floor($preConvertTime / 86400);
    $hours = floor($preConvertTime / 3600);
    $minutes = floor(($preConvertTime / 60) % 60);
    $seconds = $preConvertTime % 60;
    $time = "$days day(s) $hours hour(s) $minutes minute(s) $seconds second(s)";
  }
  elseif ($preConvertTime < 86400 && $preConvertTime > 3599) {
    $hours = floor($preConvertTime / 3600);
    $minutes = floor(($preConvertTime / 60) % 60);
    $seconds = $preConvertTime % 60;
    $time = "$hours hour(s) $minutes minute(s) $seconds second(s)";
  } else {
    $minutes = floor(($preConvertTime / 60) % 60);
    $seconds = $preConvertTime%60;
    $time = "$minutes minute(s) $seconds second(s)";
  }
  $resInfo = ["credits"=>$researchArray[$research]["credits"] * $coeficient, "hyperid"=>$researchArray[$research]["hyperid"] * $coeficient, "resTime"=>$time, "level"=>$level - 1, "curRes"=>$lvlRes["currentResearch"]];
  print json_encode($resInfo);

} elseif (isset($_SESSION["sid"]) && isset($_POST["action"]) && $_POST["action"] == "getID") {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID = '$session'");
  $userID = mysqli_fetch_assoc($sql);
  echo $userID["userID"];
  exit();
} else {
  echo "An error has occured #152/1";
  exit();
}

 ?>
