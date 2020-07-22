<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
  } else {
    session_unset();
    session_destroy();
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}
if (isset($_POST["fleetSubmit"])) {
$userNick = $_SESSION["sid"];
$sql = "SELECT * FROM users WHERE sessionID='$userNick'";
      $getID = mysqli_query($conn, $sql);
      $ID = mysqli_fetch_assoc($getID);
      $sql = "SELECT * FROM userfleet WHERE userID=$ID[userID]";
      $getAttacks = mysqli_query($conn, $sql);
      $getPlayer = mysqli_fetch_assoc($getAttacks);
      $getBase = mysqli_query($conn, "SELECT coreHealth FROM userbase WHERE userID=$ID[userID]");
      $base = mysqli_fetch_assoc($getBase);
      $sql = "SELECT researchSpeed FROM userresearch WHERE userID=$ID[userID]";
      $getUserResearch = mysqli_query($conn, $sql);
      $UserResearch = mysqli_fetch_assoc($getUserResearch);
$planetX = $_POST["locationX"];
$planetY = $_POST["locationY"];
$map = $_POST["locationMap"];
$hornet = $_POST["hornet"];
$spacefire = $_POST["spacefire"];
$starhawk = $_POST["starhawk"];
$peacemaker = $_POST["peacemaker"];
$centurion = $_POST["centurion"];
$nathalis = $_POST["nathalis"];
$getAttack = mysqli_query($conn, "SELECT * FROM usermovement WHERE userID=$ID[userID]");

if (mysqli_num_rows($getAttack) <= 5 && $base["coreHealth"] > 0){ //kontrola maxima slotů pohybu 5
if (empty($planetX)== FALSE && empty($map)== FALSE && empty($planetY) === FALSE) {
if ($planetX == $getPlayer["pageCoordsX"] && $planetY == $getPlayer["pageCoordsY"]&& $map == $getPlayer["mapLocation"]) {
  header("location: ../internalBriefing.php?error=cantattackyourself");
  exit();
} else {


  if ($getAttack === FALSE ) {
  header("location: ../internalBriefing.php?error=sql");
  exit();

}
else {
  if ($hornet > $getPlayer["hornet"]) {
    $hornet = $getPlayer["hornet"];
  }
  if ($spacefire > $getPlayer["spacefire"]) {
    $spacefire = $getPlayer["spacefire"];
  }
  if ($starhawk > $getPlayer["starhawk"]) {
    $starhawk = $getPlayer["starhawk"];
  }
  if ($peacemaker > $getPlayer["peacemaker"]) {
    $peacemaker = $getPlayer["peacemaker"];
  }
  if ($centurion > $getPlayer["centurion"]) {
    $centurion = $getPlayer["centurion"];
  }
  if ($nathalis > $getPlayer["nathalis"]) {
    $nathalis = $getPlayer["nathalis"];
  }


//count ships
$hornetSql= $getPlayer["hornet"] - intval($hornet);
$spacefireSql= $getPlayer["spacefire"] - intval($spacefire);
$starhawkSql= $getPlayer["starhawk"] - intval($starhawk);
$peacemakerSql= $getPlayer["peacemaker"] - intval($peacemaker);
$centurionSql= $getPlayer["centurion"] - intval($centurion);
$nathalisSql= $getPlayer["nathalis"] - intval($nathalis);
$Fuel = $getPlayer["fuel"];
if ($getPlayer["hornet"] == 0) {
  $hornetSql = 0;
  $hornet = 0;
}
if ($getPlayer["spacefire"] == 0) {
  $spacefireSql = 0;
  $spacefire = 0;
}
if ($getPlayer["starhawk"] == 0) {
  $starhawkSql = 0;
  $starhawk = 0;
}
if ($getPlayer["peacemaker"] == 0) {
  $peacemakerSql = 0;
  $peacemaker = 0;
}
if ($getPlayer["centurion"] == 0) {
  $centurionSql = 0;
  $centurion = 0;
}
if ($getPlayer["nathalis"] == 0) {
  $nathalisSql = 0;
  $nathalis = 0;
}
if (empty($hornet)) {
  $hornet = 0;
}
if (empty($spacefire)) {
  $spacefire = 0;
}
if (empty($starhawk)) {
  $starhawk = 0;
}
if (empty($peacemaker)) {
  $peacemaker = 0;
}
if (empty($centurion)) {
  $centurion = 0;
}
if (empty($nathalis)) {
  $nathalis = 0;
}

$shipSpeeds = array(array(25000), array(30000), array(20000), array(17500), array(19000), array(12500));

$CountDistanceX = $planetX - $getPlayer["pageCoordsX"];
$CountDistanceY = $planetY - $getPlayer["pageCoordsY"];
$CountDistanceMap = $map - $getPlayer["mapLocation"];

if ($CountDistanceX < 0) {
  $CountDistanceX= abs($CountDistanceX);
}
if ($CountDistanceY < 0) {
  $CountDistanceY= abs($CountDistanceY);
}
if ($CountDistanceMap < 0) {
  $CountDistanceMap= abs($CountDistanceMap);
}


$travelTimeX = 4.5; //seconds
$travelTimeY = 2;
$travelTimeMap = 900;

$countFuel1 = ($hornet * ($CountDistanceX+$CountDistanceMap)* 0.01);
$countFuel2 = ($spacefire * ($CountDistanceX+$CountDistanceMap)* 0.015);
$countFuel3 = ($starhawk * ($CountDistanceX+$CountDistanceMap)* 0.020);
$countFuel4 = ($peacemaker * ($CountDistanceX+$CountDistanceMap)* 0.05);
$countFuel5 = ($centurion * ($CountDistanceX+$CountDistanceMap)* 0.05);
$countFuel6 = ($nathalis * ($CountDistanceX+$CountDistanceMap)* 0.2);
$usedFuel = $getPlayer["fuel"] - $countFuel1 - $countFuel2 - $countFuel3 - $countFuel4 -$countFuel5 -$countFuel6 ;
$distanceX = 400;
$distanceY = 400;
$distanceMap = 8000;
if ($nathalis > 0) {
  $speed = $shipSpeeds[5][0];
} elseif ($peacemaker > 0) {
  $speed = $shipSpeeds[3][0];
} elseif ($centurion > 0) {
  $speed = $shipSpeeds[4][0];
} elseif ($starhawk > 0) {
  $speed = $shipSpeeds[2][0];
} elseif ($hornet > 0) {
  $speed = $shipSpeeds[0][0];
} elseif ($spacefire > 0) {
  $speed = $shipSpeeds[1][0];
}

$CountTime =  round((((($CountDistanceMap*$distanceMap)+($CountDistanceX*$distanceX)+($CountDistanceY*$distanceY))/($speed*($UserResearch["researchSpeed"]*0.1+1)))*60), 0);




$time1 = date("U") + $CountTime;
$attackWhen = date("U");

if ($getPlayer["hornet"] > 0 || $getPlayer["spacefire"] > 0 || $getPlayer["starhawk"] > 0 || $getPlayer["peacemaker"] > 0 || $getPlayer["centurion"] > 0 || $getPlayer["nathalis"] > 0) {
if ($hornet > 0 || $spacefire > 0 || $starhawk > 0 || $peacemaker>0 || $centurion>0 || $nathalis >0)  {
if ($usedFuel >= 0) {
  function checkIds($conn, $randID) {
    $sql = "SELECT lobbyID FROM usermovement";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)) {
      if ($row['lobbyID'] == $randID) {
        $idExists = true;
        break;
      } else {
        $idExists = false;
      }
    }
    return $idExists;
  }

  function generateID($conn) {
    $keyLength = 20;
    $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $randID = substr(str_shuffle($str), 0 , $keyLength);
    $checkId = checkIds($conn, $randID);

    while ($checkId == true) {
      $randID = substr(str_shuffle($str), 0 , $keyLength);
      $checkId = checkIds($conn, $randID);
    }

    return $randID;
  }


$checkBase = "SELECT whenDestroyed, userID, fleetPoints, admin FROM userfleet WHERE pageCoordsX=? AND pageCoordsY=? AND mapLocation=?";
$stmt1 = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt1, $checkBase);
mysqli_stmt_bind_param($stmt1, "iii", $planetX, $planetY, $map);
mysqli_stmt_execute($stmt1);
$result = mysqli_stmt_get_result($stmt1);
$ResultID = mysqli_fetch_assoc($result);
echo mysqli_error($conn);
  if ($ResultID["admin"] == 1) {
    header("location: ../internalBriefing.php?error=admin");
    exit();
  }
  if ($ResultID["userID"] > 0) {
    if ($ResultID["fleetPoints"]*10 < $getPlayer["fleetPoints"]) {
      header("location: ../internalBriefing.php?error=weak");
      exit();
    }
    $sql = mysqli_query($conn, "SELECT userclan FROM users WHERE userID=$ResultID[userID]");
    $enemyClan = mysqli_fetch_assoc($sql);

    if ($enemyClan["userclan"] == $userInfo["userclan"] && $enemyClan["userclan"] != "none") {
      header("location: ../internalBriefing.php?error=member");
      exit();
    }

    $sql = mysqli_query($conn, "SELECT clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
    $clanUser = mysqli_fetch_assoc($sql);

    $travelWay = 1;
    $type = "player";
    $sql = mysqli_query($conn, "SELECT userclan FROM users WHERE userID=$ResultID[userID]");
    $clan = mysqli_fetch_assoc($sql);

    $sql = mysqli_query($conn, "SELECT clanDiplo FROM userclans WHERE clanTag='$clan[userclan]'");
    $clanDiplo = mysqli_fetch_assoc($sql);

    $decompiledDiplo = unserialize($clanDiplo["clanDiplo"]);
    foreach ($decompiledDiplo as $key) {
      if (array_search($clanUser["clanID"], $key) != FALSE) {
        switch ($key[1]) {
          case '1':
            $status = "Alliance";
          break;
          case '2':
            $status = "Non-agression pact";
          break;
        }
        header("location: ../internalBriefing.php?error=allydiplo&&type=$status");
        exit();
      }
    }


    $generatedID = generateID($conn);
      $sql = "INSERT INTO usermovement (targetUserID, attackedUserX, attackedUserY, targetMapLocation, attack1, attack2, attack3, attack4, attack5, attack6, userID, travelTime, setAttack, travelWay, type, lobbyID) VALUES (?, ?, ? , ?, ? , ? , ? ,?, ?, ?, ?, ?, ?, ?, ?,?)";
      $stmt = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiiiiss", $ResultID["userID"], $planetX, $planetY, $map, $hornet, $spacefire, $starhawk, $peacemaker, $centurion, $nathalis, $ID["userID"], $time1, $attackWhen, $travelWay, $type, $generatedID);
        mysqli_stmt_execute($stmt);
          $setWhen ="UPDATE userfleet SET hornet=?, spacefire=?, starhawk=?, peacemaker= ?, centurion= ?, nathalis=?, fuel=?  WHERE userID=?;";
          $stmt = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt, $setWhen);
          mysqli_stmt_bind_param($stmt, "iiiiiiii", $hornetSql,$spacefireSql,$starhawkSql,$peacemakerSql,$centurionSql,$nathalisSql,$usedFuel,$ID["userID"]);
          $executed = mysqli_stmt_execute($stmt);
          if ($executed === TRUE) {
              header("location: ../internalBriefing.php?success=attacksent");
              exit();

        }
        else {
        header("location: ../internalBriefing.php?error=sql");
        exit();
      }
      } //
      else {
        header("location: ../internalBriefing.php?error=sql");
        exit();
      }
    }  else {
      $system = "systems".ceil($map/100);
      $sql = "SELECT npcType FROM $system WHERE coordsX=? AND coordsY=? AND map=?";
      $stmt = mysqli_stmt_init($conn);
      if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "iii", $planetX, $planetY, $map);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $ResultID = mysqli_fetch_assoc($result);
        if ($ResultID["npcType"] != "") {
          $travelWay = 1;
          $type = "npc";
          $generatedID = generateID($conn);
            $sql = "INSERT INTO usermovement (attackedUserX, attackedUserY, targetMapLocation, attack1, attack2, attack3, attack4, attack5, attack6, userID, travelTime, setAttack, travelWay, type, lobbyID) VALUES (?, ?, ? , ?, ? , ? , ? ,?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
              mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiiiss", $planetX, $planetY, $map, $hornet, $spacefire, $starhawk, $peacemaker, $centurion, $nathalis, $ID["userID"], $time1, $attackWhen, $travelWay, $type, $generatedID);
              mysqli_stmt_execute($stmt);
                $setWhen ="UPDATE userfleet SET hornet=?, spacefire=?, starhawk=?, peacemaker= ?, centurion= ?, nathalis=?, fuel=?  WHERE userID=?;";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $setWhen);
                mysqli_stmt_bind_param($stmt, "iiiiiiii", $hornetSql,$spacefireSql,$starhawkSql,$peacemakerSql,$centurionSql,$nathalisSql,$usedFuel,$ID["userID"]);
                $executed = mysqli_stmt_execute($stmt);
                if ($executed === TRUE) {
                    header("location: ../internalBriefing.php?success=attacksent");
                    exit();

              }
              else {
              header("location: ../internalBriefing.php?error=sql");
              exit();
            }
          }
            else {
              header("location: ../internalBriefing.php?error=sql");
            exit();
          }
        } else {
          header("location: ../internalBriefing.php?error=notfound");
          exit();
        }
      }  else {
        header("location: ../internalBriefing.php?error=sql");
        exit();
      }
    }


  } else {
  $fuelabs=abs($usedFuel);
  header("location: ../internalBriefing.php?error=fuel&&ammount=".$fuelabs."");
  exit();
  }

} else {
  header("location: ../internalBriefing.php?error=noshipsselected");
  exit();
}
}
else {
  header("location: ../internalBriefing.php?error=noships");
  exit();
}
} //end else

} //end else

} else {
  header("location: ../internalBriefing.php?error=emptycoords");
  exit();
}
} else {
  header("location: ../internalBriefing.php?error=core0");
  exit();
}
} else {
  header("location: ../index.php");
  exit();
}
 ?>
