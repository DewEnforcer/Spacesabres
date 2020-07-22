<?php
require "dbh.inc.php";
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["item"])) {
  $researchArray = ["researchDmg"=>["credits"=>350000,"hyperid"=>175000,"natium"=>0,"researchTime"=>1500], "researchHp"=>["credits"=>500000,"hyperid"=>100000,"natium"=>0,"researchTime"=>1800], "researchShd"=>["credits"=>200000,"hyperid"=>250000,"natium"=>0,"researchTime"=>1300], "researchSpeed"=>["credits"=>150000,"hyperid"=>150000,"natium"=>0,"researchTime"=>1000], "researchSubspace"=>["credits"=>1000000,"hyperid"=>600000,"natium"=>0,"researchTime"=>2400]];

  $session = mysqli_real_escape_string($conn, $_SESSION["sid"]);
  $itemID = mysqli_real_escape_string($conn, $_POST["item"]);
  $sql = "SELECT * FROM users WHERE sessionID='$session'";
  $getID = mysqli_query($conn, $sql);
  if (mysqli_num_rows($getID) == 0) {
    session_unset();
    session_destroy();
    exit();
  }
  $ID = mysqli_fetch_assoc($getID);
  $sql = "SELECT * FROM userresearch WHERE userID=$ID[userID]" ;
  $getResearchLevels = mysqli_query($conn, $sql);
  $researchLevels = mysqli_fetch_assoc($getResearchLevels);
  if ($researchLevels["currentResearch"] <= 0) {

  if ($itemID == 11) {
    $researchRow = "researchDmg";
    $currentResearch = 1;
  } elseif ($itemID == 12) {
    $researchRow = "researchHp";
    $currentResearch = 2;
  } elseif ($itemID == 13) {
    $researchRow = "researchShd";
    $currentResearch = 3;
  } elseif ($itemID == 14) {
    $researchRow = "researchSpeed";
    $currentResearch = 4;
  } elseif ($itemID == 15) {
    $researchRow = "researchSubspace";
    $currentResearch = 5;
  }

  $level = $researchLevels[$researchRow] + 1;
  if ($level == 1 || $level == 0) {
    $coeficient = 1;
  } elseif ($level == 2) {
    $coeficient = 2;
  } else {
    $coeficient = $level * 2;
  }


  if ($researchArray[$researchRow]["credits"]*$coeficient <= $ID["credits"] && $researchArray[$researchRow]["hyperid"]*$coeficient <= $ID["hyperid"]) {
    $countCredits = $ID["credits"] - $researchArray[$researchRow]["credits"] * $coeficient;
    $countUri = $ID["hyperid"] - $researchArray[$researchRow]["hyperid"] * $coeficient;

    $getTime = $researchArray[$researchRow]["researchTime"] * $coeficient;
    $researchTime = date("U")+$getTime;
    $sql = "UPDATE userresearch SET currentResearch=$currentResearch, researchTime='$researchTime' WHERE userID=$ID[userID]";
    $updateResearch = mysqli_query($conn, $sql);

    $sql1 = mysqli_query($conn, "UPDATE users SET hyperid=$countUri, credits=$countCredits WHERE sessionID='$session'");
    if ($updateResearch!== FALSE && $sql1 !== FALSE) {
        echo "success";
        exit();
    } else {
      echo "error1";
      exit();
    }


  } else {
    echo "error";
    exit();
  }

} else {
  echo "error";
  exit();
}
} else {
  echo "error";
  exit();
}
 ?>
