<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["dataX"]) && isset($_POST["dataY"]) && isset($_POST["type"]) && isset($_POST["map"])) {
  require "dbh.inc.php";

  if ($_POST["type"] == "Enemy" || $_POST["type"] == "Passive" || $_POST["type"] == "Ally" || $_POST["type"] == "User" ) {
    $session = $_SESSION["sid"];
    $x = $_POST["dataX"];
    $y = $_POST["dataY"];
    $map = $_POST["map"];

    $sql = mysqli_query($conn, "SELECT userID, ingameNick FROM users WHERE sessionID='$session'");
    if (mysqli_num_rows($sql) == 0) {
        $_SESSION["sid"] = "";
      header("Location: ../index.php");
      exit();
    }
    $getOwnNick = mysqli_fetch_assoc($sql);
    $sql = mysqli_query($conn, "SELECT researchSubspace FROM userresearch WHERE userID=$getOwnNick[userID]");
    $ownSubspaceResearch = mysqli_fetch_assoc($sql);
    if ($ownSubspaceResearch["researchSubspace"] > 0) {
      $hackingStatus = 1;
    } else {
      $hackingStatus = 0;
    }
    $sql = "SELECT * FROM userfleet WHERE pageCoordsX=? AND pageCoordsY=? AND mapLocation=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sss" ,$x, $y, $map);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $planetParams = mysqli_fetch_assoc($result);
    $sql = mysqli_query($conn, "SELECT ingameNick, userclan FROM users WHERE userID=$planetParams[userID]");
    $getNick = mysqli_fetch_assoc($sql);
    if ($getOwnNick["ingameNick"] != $getNick["ingameNick"]) {
      $check = 0;
    } elseif ($getOwnNick["ingameNick"] == $getNick["ingameNick"]) {
      $check = 1;
    }

    if ($getNick["userclan"] == "none") {
      $getNick["userclan"] = "";
    } else {
      $getNick["userclan"] = $getNick["userclan"];
    }

    if ($check == 0){
    $planetParamsArray = ["owner"=>"[".$getNick["userclan"]."] ".$getNick["ingameNick"], "coordsx"=>$planetParams["pageCoordsX"], "coordsy"=>$planetParams["pageCoordsY"], "mapLocation"=>$planetParams["mapLocation"], "attack"=>$check, "hackingStatus"=> $hackingStatus];
    }
    elseif($check==1) {
      $planetParamsArray = ["owner"=>"[".$getNick["userclan"]."] ".$getNick["ingameNick"], "coordsx"=>$planetParams["pageCoordsX"], "coordsy"=>$planetParams["pageCoordsY"], "mapLocation"=>$planetParams["mapLocation"], "attack"=>$check, "hackingStatus"=> $hackingStatus];
    }

    print json_encode($planetParamsArray);
    exit();

  } elseif ($_POST["type"] == "npc") {
    $x = $_POST["dataX"];
    $y = $_POST["dataY"];
    $map = $_POST["map"];

    $system = "systems".ceil($map/100);

    $sql = "SELECT npcType FROM $system WHERE coordsX=? AND coordsY=? AND map=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sss" ,$x, $y, $map);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $planetParams = mysqli_fetch_assoc($result);

    $planetParamsArray = ["owner"=>$planetParams["npcType"], "coordsx"=>$x, "coordsy"=>$y, "mapLocation"=>$map, "attack"=>0, "hackingStatus"=>0];

    print json_encode($planetParamsArray);
    exit();
  } else {
    exit();
  }
} else {
  header("location: ../index.php");
  exit();
}
 ?>
