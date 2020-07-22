<?php
require "dbh.inc.php";
// NOTE: USE $userID VARIABLE FOR ATTACKERS USERID (VARIABLE FROM MAIN CRON SCRIPT);
// ↓ coordinates of the target
$TargetCoordinates = ["x"=>$fetchedX, "y"=>$fetchedY, "map"=>$fetchedMap];
$type = $fetchedType;
$AttackerFleet = $fetchedFleet;
$sql = mysqli_query($conn, "SELECT fleetPoints FROM userfleet WHERE userID=$userID");
$attackerFleetPoints = mysqli_fetch_assoc($sql);


// ↓ variable determining which array of system is the npc in for databse
  $system = "systems".ceil($TargetCoordinates["map"]/100);
// ↓ userID and fleet of the target
$targetFleetNumber = [0,0,0,0,0,0];
$attackerFleetnumber = unserialize($SelectedAttacks["fleetNumbers"]);
// ↓ get attackers research
$sql = mysqli_query($conn, "SELECT researchHp, researchShd, researchDmg FROM userresearch WHERE userID=$userID");
if (mysqli_error($conn) == "") {
$attackerResearch = mysqli_fetch_assoc($sql);
} else {
  exit();
}
if ($type == "player") {
  $sql = mysqli_query($conn, "SELECT userID, dockAmount, formationLight, formationHeavy, fleetPoints FROM userfleet WHERE pageCoordsX=$TargetCoordinates[x] AND pageCoordsY=$TargetCoordinates[y] AND mapLocation=$TargetCoordinates[map]");
  if (mysqli_error($conn) == "") {
  $targetFleet = mysqli_fetch_assoc($sql);
  $TargetFleet = [];
  $targetUserID = $targetFleet["userID"];
  $targetFormationLight = $targetFleet["formationLight"];
  $targetFormationHeavy = $targetFleet["formationHeavy"];
  for ($i=1; $i <= $targetFleet["dockAmount"]; $i++) {
    $dock = "dock$i";
    $sql = mysqli_query($conn, "SELECT $dock FROM userfleet WHERE userID=$targetUserID");
    $fetch = mysqli_fetch_assoc($sql);
    $fetch = unserialize(gzdecode($fetch[$dock]));
    array_push($TargetFleet, $fetch);
  }
  } else {
    exit();
  }
  // ↓ get targets research
  $sql = mysqli_query($conn, "SELECT researchShd, researchDmg FROM userresearch WHERE userID=$targetUserID");
  if (mysqli_error($conn) == "") {
  $TargetResearch = mysqli_fetch_assoc($sql);

  } else {
    exit();
  }
  // ↓ get targets battlestation information
  $sql = mysqli_query($conn, "SELECT * FROM userbase WHERE userID=$targetUserID");
  if (mysqli_error($conn) == "") {
  $targetBattlestation = mysqli_fetch_assoc($sql);
  } else {
    exit();
  }

} elseif($type== "npc") {
  $sql = mysqli_query($conn, "SELECT npcType, fleet, fleetNumbers, formationLight, formationHeavy FROM $system WHERE coordsX=$TargetCoordinates[x] AND coordsY=$TargetCoordinates[y] AND map=$TargetCoordinates[map]");
  if (mysqli_error($conn) == "") {
  $targetFleet = mysqli_fetch_assoc($sql);
  $TargetFleet = [unserialize(gzdecode($targetFleet["fleet"]))];
  $targetFormationLight = $targetFleet["formationLight"];
  $targetFormationHeavy = $targetFleet["formationHeavy"];
  $targetFleet["fleetPoints"] = 0;
  $targetFleet["dockAmount"] = 1;
  $targetUserID = 0;
  } else {
    exit();
  }
  // ↓ placeholder research
  $TargetResearch["researchShd"] = 0;
  $TargetResearch["researchHp"] = 0;
  $TargetResearch["researchDmg"] = 0;
  $targetBattlestation["coreHealth"] = 0;
  $targetBattlestation["coreShields"] = 0;
  $destroyedCoreHp = 0;
  $destroyedCoreShd = 0;
}
$originalTargetFleet = $TargetFleet;
$originalAttackerFleet = $AttackerFleet;
$TargetDamage = [0,0,0,0,0,0];
$TargetShields = [0,0,0,0,0,0];
$TargetHealth = [0,0,0,0,0,0];

$AttackerDamage = [0,0,0,0,0,0];
$AttackerShields = [0,0,0,0,0,0];
$AttackerHealth = [0,0,0,0,0,0];
// ↓ ship and battlestation technical parameters
// DMG , shield, hp, speed
$rapidFireArray = [[1,1,1,4,4,4],[1,1,1,3,3,3],[2,2,1,1,1,1],[6,6,6,1,1,1],[1,1,2,5,5,5],[1,1,1,8,8,8]]; // rapid fire
$baseParameters = array(array(0, 0, 0, 0), array(6000, 150000, 120000, 0), array(9000, 150000, 120000, 0), array(10000, 60000, 150000, 0), array(0, 200000, 150000, 0), array(0,$targetBattlestation["coreShields"],$targetBattlestation["coreHealth"],0));
// placeholder, lightlaser, heavylaser, rocket silo , shield module ,core
$formationHeavyStatsBuffDmg = array("Line"=>array(1,1,1,1,1,1,1), "Column"=>array(0.5, 0.5, 0.5, 0.5, 0.5, 0.5, 1.5), "Echelon"=>array(1.3, 1.3, 1.3, 0.9, 0.9, 0.9, 0.65), "Wedge"=>array(0.85,0.85,0.85,0.85,0.85,0.85));
$formationHeavyStatsBuffShd = array("Line"=>array(1), "Column"=>array(1.25), "Echelon"=>array(1), "Wedge"=>array(1.5));

$formationLightStatsBuffDmg = array("Phalanx"=>array(1,1,1,1,1,1,1), "Pincer"=>array(1.2, 1.2, 1.2, 0.85, 0.85, 0.85, 1.05), "Arrow"=>array(0.8, 0.8, 0.8, 1.3, 1.3, 1.3, 0.7), "Turtle"=>array(1,1,1,0.6,0.6,0.6,0.6), "Lance"=>array(0.5,0.5,0.5,0.4,0.4,0.4,1.5));
$formationLightStatsBuffShd = array("Phalanx"=>array(1,1), "Pincer"=>array(1.02,1), "Arrow"=>array(0.8,1), "Turtle"=>array(1.7,1.5), "Lance"=>array(1.15,1));
// hornet, spacefire, starhawk, peacemaker, centurion, nathalis, battlestation
//Shd => first increases ships shield value , second increases core shields values
foreach ($AttackerFleet as $ship) {
  if ($ship["type"] > 2) {
    $formationDmg = $formationHeavyStatsBuffDmg;
    $formationShd = $formationHeavyStatsBuffShd;
    $formation = $attackerFormationHeavy;
  } elseif ($ship["type"] < 3) {
    $formationDmg = $formationLightStatsBuffDmg;
    $formationShd = $formationLightStatsBuffShd;
    $formation = $attackerFormationLight;
  }
    $AttackerDamage[$ship["type"]] += ($ship["stats"][0]*($attackerResearch["researchDmg"]*0.1+1))*$formationDmg[$formation][$ship["type"]];
    $AttackerShields[$ship["type"]] += ($ship["stats"][1]*($attackerResearch["researchShd"]*0.1+1))*$formationShd[$formation][0];
    $AttackerHealth[$ship["type"]] += $ship["stats"][2];
}
  foreach ($TargetFleet as $dock) {
      foreach ($dock as $ship) {
        if ($ship["type"] < 3) {
          $formationDmg = $formationLightStatsBuffDmg;
          $formationShd = $formationLightStatsBuffShd;
          $formation = $targetFormationLight;
        } elseif ($ship["type"] > 2) {
          $formationDmg = $formationHeavyStatsBuffDmg;
          $formationShd = $formationHeavyStatsBuffShd;
          $formation = $targetFormationHeavy;
        }
      $TargetDamage[$ship["type"]] += ($ship["stats"][0]*($TargetResearch["researchDmg"]*0.1+1))*$formationDmg[$formation][$ship["type"]];
      $TargetShields[$ship["type"]] += ($ship["stats"][1]*($TargetResearch["researchShd"]*0.1+1))*$formationShd[$formation][0];
      $TargetHealth[$ship["type"]] += $ship["stats"][2];
      $targetFleetNumber[$ship["type"]]++;
      }

  }

$originalTargetFleetNumber = $targetFleetNumber;
$originalAttackerFleetNumber = $attackerFleetnumber;
// ↓ initiate damage counter
if ($presentAttacker > date("U") && $presentTarget < date("U")) {
  $order = "Only attacking commander has been present in this round, therefore surprising enemy fleet and being the first one to attack.";
  require "userAttack.php";
  $originalTargetFleet = $TargetFleet; // since attacker started first, gotta change the fleet for defender from attacking at the same time to after attacker
  require "enemyAttack.php";

} elseif ($presentAttacker < date("U") && $presentTarget > date("U")) {
  $order = "Only defending commander has been present in this round, therefore surprising enemy fleet and being the first one to attack.";
    require "enemyAttack.php";
    require "userAttack.php";
  } elseif ($presentAttacker > date("U") && $presentTarget > date("U")) {
    $order = "Both defending and attacking commanders have been present during this round, therefore both fleets engaged each other in the same time";
    require "userAttack.php";
    require "enemyAttack.php";
    }
    elseif ($presentAttacker < date("U") && $presentTarget < date("U")) {
      $order = "Both defending and attacking commanders have not been present during this round, therefore both fleets engaged each other in the same time";
      require "userAttack.php";
      require "enemyAttack.php";
      }


      if (array_sum($targetFleetNumber) < 1 && empty($AttackerFleet) == false) {
        $victory = "attacker";
      } elseif (empty($AttackerFleet) == true && array_sum($targetFleetNumber) > 0) {
          $victory = "defender";
        } elseif (empty($AttackerFleet) == true && array_sum($targetFleetNumber) < 1) {
          $victory = "drawDestruction";
        } else {
          $victory = "draw";
          }
// ↓ update user accounts post battle
// ↓ either delete the movement completely (since attackers fleet was completely destroyed, or set return time);
  $fleetNumberAttackQuery = serialize($attackerFleetnumber);
if ($round < 3) {
// ↓ if user has missions , this will be triggered
  $fleetQueryAttacker = gzencode(serialize($AttackerFleet),9);
if ($victory == "attacker") {
  // ↓ for pvp missions (checks if the defeated target is player)
  if ($targetUserID > 1) {
    handleObjectives($conn, $userID, 9);
    // ↓ for pve missions (checks if the defeated target is npc)
  } elseif ($targetUserID < 1) {
    if ($targetFleet["npcType"] == "Pirate" || $targetFleet["npcType"] == "Chief Pirate" || $targetFleet["npcType"] == "Master Pirate") {
      handleObjectives($conn, $userID, 10);
    } else {
      handleObjectives($conn, $userID, 22);
      }
  } else {
    "";
  }
  $countReturn = ($timeAttack-$timeSet) - (180*($round-1));
  $return = date("U")+$countReturn;
  $sql = "UPDATE usermovement SET fleet=?, fleetNumbers=?, travelTime=?, travelWay=0, returnWay=1, missionType=2 WHERE userID=? AND id=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "ssiis", $fleetQueryAttacker, $fleetNumberAttackQuery, $return, $userID, $attackID);
  mysqli_stmt_execute($stmt);
} elseif ($victory == "defender") {
  handleObjectives($conn, $targetUserID, 9);
  foreach ($TargetFleet as $dock) {
    foreach ($dock as $ship) {
      $ship["stats"][1] = 0;
      $ship["stats"][2] = $shipParams[$ship["type"]];
      foreach ($ship["shields"] as $slotShd) {
        if ($slotShd == 25) {
          $ship["stats"][1] += 5000;
        }
    }
  }
  }
  } elseif ($victory == "draw") {
    $newTime = date("U")+180;
    $newRound = $round+1;
    $sql = "UPDATE usermovement SET fleet=?, fleetNumbers=?, travelTime=?, rounds=? WHERE userID=? AND id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssiiis", $fleetQueryAttacker, $fleetNumberAttackQuery, $newTime, $newRound, $userID, $attackID);
    mysqli_stmt_execute($stmt);
  }
} else if ($round > 2) {
  $countReturn = ($timeAttack-$timeSet) - (180*($round-1));
  $return = date("U")+$countReturn;
  $fleetQueryAttacker = gzencode(serialize($AttackerFleet),9);
  if ($victory == "attacker") {
    $sql = "UPDATE usermovement SET fleet=?, fleetNumbers=?, travelTime=?, travelWay=0, returnWay=1, missionType=2 WHERE userID=? AND id=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssiis", $fleetQueryAttacker, $fleetNumberAttackQuery, $return, $userID, $attackID);
    mysqli_stmt_execute($stmt);
    if ($targetUserID > 1) {
      handleObjectives($conn, $userID, 9);
    } else {
      if ($targetFleet["npcType"] == "Pirate" || $targetFleet["npcType"] == "Chief Pirate" || $targetFleet["npcType"] == "Master Pirate") {
        handleObjectives($conn, $userID, 10);
      } else {
        handleObjectives($conn, $userID, 22);
        }
      }
  } else if ($victory == "defender") {
    handleObjectives($conn, $targetUserID, 9);
    } elseif ($victory == "draw") {
      $sql = "UPDATE usermovement SET fleet=?, travelTime=?, travelWay=0, returnWay=1, missionType=2 WHERE userID=? AND id=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "siis", $fleetQueryAttacker, $return, $userID, $attackID);
      echo mysqli_error($conn);
      mysqli_stmt_execute($stmt);
    }
    }
    if ($type == "player") {
      $i = 1;
      foreach ($TargetFleet as $dock) {
        $dockName = "dock$i";
        $dockQuery = gzencode(serialize($dock),9);
        $sql = "UPDATE userfleet SET $dockName=? WHERE userID=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "si", $dockQuery, $targetUserID);
        mysqli_stmt_execute($stmt);
      }
      $updateEnemyBase = mysqli_query($conn, "UPDATE userbase SET coreHealth=$destroyedCoreHp, coreShields=$destroyedCoreShd, slot1=$PostBattle[1],slot2=$PostBattle[2],slot3=$PostBattle[3],slot4=$PostBattle[4],slot5=$PostBattle[5],slot6=$PostBattle[6],slot7=$PostBattle[7],slot8=$PostBattle[8],slot9=$PostBattle[9],slot10=$PostBattle[10] WHERE userID=$targetUserID");

    } elseif ($type == "npc") {
      if ($round < 3 && $victory != "attacker" && $victory != "drawDestruction") {
        $dockQuery = gzencode(serialize($TargetFleet[0]),9);
        $fleetNumbers = serialize($targetFleetNumber);
        $sql = "UPDATE $system SET fleet=?, fleetNumbers=? WHERE coordsX=? AND coordsY=? AND map=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ssiii", $dockQuery, $fleetNumbers, $TargetCoordinates["x"], $TargetCoordinates["y"], $TargetCoordinates["map"]);
        mysqli_stmt_execute($stmt);
      } else {
        $sql = mysqli_query($conn, "DELETE FROM $system WHERE coordsX=$TargetCoordinates[x] AND coordsY=$TargetCoordinates[y] AND map=$TargetCoordinates[map]");
        }
      }// ↓ check if all queries have been executed before starting message script
  require "battleMsg.php";
  unset($AttackerFleet, $TargetFleet, $originalTargetFleet, $originalAttackerFleet, $originalTargetFleetNumber, $originalAttackerFleetNumber, $attackerFleetnumber, $targetFleetNumber, $AttackerDamage, $AttackerShields, $AttackerHealth, $TargetDamage, $TargetHealth, $TargetShields, $realDocks,  $TargetCoordinates, $getTargetFleet, $TargetMsgFleet, $TargetResearch, $targetBattlestation, $TargetResearch, $attackerResearch,  $totalDamage, $damageAttacker, $damageDefender, $PostBattle, $baseDamage, $totalBaseDamage, $fullMsg);
 ?>
