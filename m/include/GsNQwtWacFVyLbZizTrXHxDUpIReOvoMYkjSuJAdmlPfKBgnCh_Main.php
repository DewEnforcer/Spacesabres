<?php
require "dbh.inc.php"; // return all fleets from company defense home
$timeStart = date("U");
$timeEnd = date("U") + 2;
$shipParams = [10000,12500,20000,60000,72500,225000];
$coefArr = ["none",3,2.8,2.6,2.4,2.2,2,1.8,1.6,1.5,1.4];
// points functions
function countRewards($conn, $hornet, $spacefire, $starhawk, $peacemaker, $centurion, $nathalis, &$rewardsHyperids, &$rewardsNatiums , &$rewardsCredits , $userID) {
  $hyperidsReward = (($hornet*$rewardsHyperids[0]) + ($spacefire*$rewardsHyperids[1]) +($starhawk*$rewardsHyperids[2]) +($peacemaker*$rewardsHyperids[3]) +($centurion*$rewardsHyperids[4]) +($nathalis*$rewardsHyperids[5])) * 0.6;
  $natiumsReward = (($hornet*$rewardsNatiums[0]) + ($spacefire*$rewardsNatiums[1]) +($starhawk*$rewardsNatiums[2]) +($peacemaker*$rewardsNatiums[3]) +($centurion*$rewardsNatiums[4]) +($nathalis*$rewardsNatiums[5])) * 0.4;
  $creditsReward = (($hornet*$rewardsCredits[0]) + ($spacefire*$rewardsCredits[1]) +($starhawk*$rewardsCredits[2]) +($peacemaker*$rewardsCredits[3]) +($centurion*$rewardsCredits[4]) +($nathalis*$rewardsCredits[5]));
  addRewards($conn, $hyperidsReward, $natiumsReward, $creditsReward, $userID);
}
function addRewards($conn, $hyperidsReward, $natiumsReward, $creditsReward, $userID) {
  $sql = mysqli_query($conn, "SELECT credits, hyperid, natium FROM users WHERE userID=$userID");
  $userValutes = mysqli_fetch_assoc($sql);
  $newCreds = $userValutes["credits"] + $creditsReward;
  $newHyperids = $hyperidsReward+$userValutes["hyperid"];
  $newNats = $natiumsReward+$userValutes["natium"];
  $sql = mysqli_query($conn, "UPDATE users SET credits=$newCreds,  hyperid=$newHyperids, natium=$newNats WHERE userID=$userID");
  $rewards = [$newCreds, $newHyperids, $newNats];
  manageMissions($userID, $rewards, $conn);
}
function manageMissions($userID, &$rewards, $conn) {
  $sql = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$userID");
  $userCurrentMission = mysqli_fetch_assoc($sql);
  if ($userCurrentMission["currentQuest"] == 0) {
    return;
  }
  $unserializeUser = unserialize($userCurrentMission["userObjectives"]);
  $sql = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$userCurrentMission[currentQuest]");
  $setTasks = mysqli_fetch_assoc($sql);
  echo mysqli_error($conn);
  $unserializeTemplate = unserialize($setTasks["objectives"]);
  $i = 0;
  while ($i <= 2) {
    if ($unserializeTemplate[$i] > 0) {
      if ($unserializeTemplate[$i] < $unserializeUser[$i]+$rewards[$i]) {
        $unserializeUser[$i] = $unserializeTemplate[$i];
        $serialize = serialize($unserializeUser);
        $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
      } else {
        $unserializeUser[$i] += $rewards[$i];
        $serialize = serialize($unserializeUser);
        $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
      }
    }
    $i++;
  }
}
// message functions
function checkMsgs($conn, $to2) {
  $sql = mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$to2 ORDER BY sentTime ASC");// check for all msgs to receiver of this msg
  if (mysqli_num_rows($sql) > 29) { // if he has more than 30 in his inbox archived
    echo mysqli_error($conn);
    $getMsgID = mysqli_fetch_assoc($sql); //get ID of the oldest msg
    $sql= mysqli_query($conn, "DELETE FROM usermsg WHERE id=$getMsgID[id]");
}
}
function sendMsg($conn, $to, $from, $currentTime, $subject, $message, $token) {
  $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, viewed, token) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $viewed = 0;
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "iiissis", $to, $from, $currentTime, $subject, $message, $viewed, $token);
  mysqli_stmt_execute($stmt);
  }
  function handleObjectives($conn, $userID, $index) {
    $sql = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$userID");
    $currentQuest = mysqli_fetch_assoc($sql);

    $unserializeUser = unserialize($currentQuest["userObjectives"]);

    $sql = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$currentQuest[currentQuest]");
    $objective = mysqli_fetch_assoc($sql);
    $unserializeTemplate = unserialize($objective["objectives"]);

    if ($unserializeTemplate[$index]>0) {
      if ($unserializeTemplate[$index] < $unserializeUser[$index] + 1) {
        $unserializeUser[$index] == $unserializeTemplate[$index];
        $serialize = serialize($unserializeUser);
        $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
      } else {
        $unserializeUser[$index] += 1;
        $serialize = serialize($unserializeUser);
        $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
        }
      return true;
    } else {
      return true;
    }
  }

while ($timeStart <= $timeEnd) {
$timeStart = date("U");
$sql = mysqli_query($conn, "SELECT execute FROM cronControl WHERE id=1");
$control = mysqli_fetch_assoc($sql);
if ($control["execute"] == 1) {
  exit();
}
$timeCurrent = date("U");
// deals with company defense
$sqlCompany = mysqli_query($conn, "SELECT userID, fleet, fleetNumbers, defenseHours, reward FROM usermovement WHERE travelTime<=$timeCurrent AND missionType=3 AND returnWay=1 ORDER BY travelTime ASC");
if (mysqli_num_rows($sqlCompany) > 0) {
  while($SelectedComp = mysqli_fetch_assoc($sqlCompany)) {
    $defenseFleetObjects = unserialize(gzdecode($SelectedComp["fleet"]));
    $defenseFleetNumbers = unserialize($SelectedComp["fleetNumbers"]);
    require "companyDefenseGeneratorEvent.php";
    $compUserID = $SelectedComp["userID"];
    $sql1 = mysqli_query($conn, "SELECT natium FROM users WHERE userID=$compUserID");
    $uri = mysqli_fetch_assoc($sql1);
    if ($event != "noevent") {
      $subject = "Company Defense report";
      $from = 1;
      $token = bin2hex(random_bytes(10));
      sendMsg($conn, $compUserID, $from, date("U"), $subject, $log, $token);
    }
    $coef = $coefArr[$SelectedComp["defenseHours"]]; //global array usage
      $countCooldownTime = round((($SelectedComp["defenseHours"] * $coef)*60)*60);
      $newUri = $uri["natium"]+$SelectedComp["reward"];
      $defenseCD= date("U") + $countCooldownTime;
     $sql2 = mysqli_query($conn, "UPDATE users SET natium=$newUri, defenseCooldown=$defenseCD WHERE userID=$compUserID");
      if(TRUE) { // fix to sql2
        handleObjectives($conn, $compUserID, 17);
        "";
      }

  }
}
// returns all fleets that arrived at home base
 $sqlReturns = mysqli_query($conn, "SELECT * FROM usermovement WHERE travelTime<=$timeCurrent AND returnWay=1 ORDER BY travelTime ASC"); // return all fleets stuck in database home
if (mysqli_num_rows($sqlReturns) > 0) {
while ($SelectedReturns = mysqli_fetch_assoc($sqlReturns)) {
  $userID = $SelectedReturns["userID"];
  $attackID = $SelectedReturns["id"];
  $returningFleet = unserialize(gzdecode($SelectedReturns["fleet"]));
  $sql1 = mysqli_query($conn, "SELECT dockAmount, temporaryDock FROM userfleet WHERE userID=$userID");
  $getStationaryFleet = mysqli_fetch_assoc($sql1);
  $sql = mysqli_query($conn, "SELECT researchHp FROM userresearch WHERE userID=$userID");
  $userResearch = mysqli_fetch_assoc($sql);
  $temporaryDock = unserialize(gzdecode($getStationaryFleet["temporaryDock"]));
  $realDocks = [];
  $fleetNumbers = unserialize($SelectedReturns["fleetNumbers"]);
  $missionType = $SelectedReturns["missionType"];
  for ($i=1; $i <= $getStationaryFleet["dockAmount"]; $i++) {
    $dock = "dock$i";
    $sql = mysqli_query($conn, "SELECT $dock FROM userfleet WHERE userID=$userID");
    $fetch = mysqli_fetch_assoc($sql);
    $fetch = unserialize(gzdecode($fetch[$dock]));
    array_push($realDocks, $fetch);
  }
  $d = 0;
  $dockEmpty;
    foreach ($realDocks as $dock) {
      foreach ($returningFleet as $ship) {
        if (count($returningFleet) == 0) {
          break; //if all ships have been assigned back, ends the search
        }
      if (count($dock) < 1000) {
        $dockEmpty = true; //returns all ships to their maximal hp/shd
        $ship["stats"][1] = 0;
        $ship["stats"][2] = $shipParams[$ship["type"]]*($userResearch["researchHp"]*0.1+1);
        foreach ($ship["shields"] as $slotShd) {
          if ($slotShd == 25) {
            $ship["stats"][1] += 5000;
          }
        }
        array_push($realDocks[$d], $ship); //sends the ship into dock
        array_splice($returningFleet, 0, 1); //removes the ship from returning fleet
      } else {
        break; // dock doesnt have anymore space, therefore break from this loop without increasing ship index (since we havent assigned this ship yet)
      }
    }
    $d++;
  }
  if (count($returningFleet) > 0) { // checks if any ships havent been assigned yet
    foreach ($returningFleet as $ship) {
      array_push($temporaryDock, $ship);
    }
  }

  $tempdockQuery = gzencode(serialize($temporaryDock));
  $sql = "UPDATE userfleet SET temporaryDock=? WHERE userID=$userID";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "s", $tempdockQuery);
  mysqli_stmt_execute($stmt);
  // ↓ starts updating docks
  for ($i=1; $i <= $getStationaryFleet["dockAmount"]; $i++) {
    $dock = "dock$i";
    $dockQuery = gzencode(serialize($realDocks[$i-1]));
    $sql = "UPDATE userfleet SET $dock=? WHERE userID=$userID";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $dockQuery);
    echo mysqli_error($conn);
    mysqli_stmt_execute($stmt);
  }
$sql = mysqli_query($conn, "DELETE FROM usermovement WHERE userID=$userID AND id=$attackID");
  $time = date("U");
  if ($missionType == 2) {
    $status = "Reason of fleet return: Standard protocol.";
  } elseif ($missionType == 3) {
    $status = "Reason of fleet return: Lost subspace connection with the core.";
  } else {
    $status = "";
  }
  $subject = "Fleet return report";
  $msg = "<p style=\"text-align:center;\">Your fleet has returned from ".$SelectedReturns["targetMapLocation"].":".$SelectedReturns["attackedUserX"].":".$SelectedReturns["attackedUserY"]."</p>";
  $msg .= "<p style=\"text-align:center;\">Ships docked during this process: </p>";
  $msg .= "<p style=\"text-align:center;\"> $fleetNumbers[0] Hornet(s) </p><p style=\"text-align:center;\"> $fleetNumbers[1] Spacefire(s) </p><p style=\"text-align:center;\"> $fleetNumbers[2] Starhawk(s)</p><p style=\"text-align:center;\"> $fleetNumbers[3] Peacemaker(s)</p><p style=\"text-align:center;\"> $fleetNumbers[4] Centurion(s)</p><p style=\"text-align:center;\"> $fleetNumbers[5] Na-Thalis Destroyer(s)</p>";
  $msg .= "<p style=\"text-align:center;\">$status</p>";
  $token = bin2hex(random_bytes(10));
  $sql = mysqli_query($conn, "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, token) VALUES ($userID, 1, $time, '$subject', '$msg', '$token')");
  echo mysqli_error($conn);
}
}
// goes through all attacks that arrived at the enemy base
 $selectAttacks = mysqli_query($conn, "SELECT * FROM usermovement WHERE travelTime<=$timeCurrent AND travelWay=1 AND missionType=1 ORDER BY travelTime ASC");
 if (mysqli_num_rows($selectAttacks) > 0) {
   while($SelectedAttacks = mysqli_fetch_assoc($selectAttacks)) {
     // prepare all variables for battlesystem
     $userID = $SelectedAttacks["userID"];
     $attackID = $SelectedAttacks["id"];
     $fetchedX = $SelectedAttacks["attackedUserX"];
     $fetchedY = $SelectedAttacks["attackedUserY"];
     $fetchedMap = $SelectedAttacks["targetMapLocation"];
     $fetchedFleet = unserialize(gzdecode($SelectedAttacks["fleet"]));
     $fetchedType = $SelectedAttacks["type"];
     $timeSet = $SelectedAttacks["travelTime"];
     $timeAttack = $SelectedAttacks["setAttack"];
     $round = $SelectedAttacks["rounds"];
     $attackerFormationLight = $SelectedAttacks["formationLight"];
     $attackerFormationHeavy = $SelectedAttacks["formationHeavy"];
     $presentTarget = $SelectedAttacks["hudPresentTarget"];
     $presentAttacker =  $SelectedAttacks["hudPresentAttacker"];
     $targetUserID = $SelectedAttacks["targetUserID"];
     // ↓ initiate battleEngine
     require "battleSystem.php";
 }
} else {

}
// Goes through all researches
$sqlResearch = mysqli_query($conn, "SELECT * FROM `userresearch` WHERE `currentResearch`>0 ORDER BY `researchTime` ASC");
$date = date("U");
while ($researchUsers = mysqli_fetch_assoc($sqlResearch)) {
  if ($researchUsers["currentResearch"] > 0) {
    if ($researchUsers["researchTime"] <= $date) {
      if ($researchUsers["currentResearch"] == 1) {
        $updateRes = "researchDmg";
        $index = 18;
      } elseif ($researchUsers["currentResearch"] == 2) {
        $updateRes = "researchHp";
        $index = 19;
        $getBaseHp = mysqli_query($conn, "SELECT coreHealth FROM userbase WHERE userID=$researchUsers[userID]");
        $baseHp = mysqli_fetch_assoc($getBaseHp);
        $newHp = $baseHp["coreHealth"] * (($researchUsers["researchHp"]+1)*0.1+1);
        $updateHp = mysqli_query($conn, "UPDATE userbase SET coreHealth=$newHp WHERE userID=$researchUsers[userID]");
        unset($getBaseHp, $baseHp, $newHp, $updateHp);
      }
      elseif ($researchUsers["currentResearch"] == 3) {
        $updateRes = "researchShd";
        $index = 20;
        $getBaseHp = mysqli_query($conn, "SELECT coreShields FROM userbase WHERE userID=$researchUsers[userID]");
        $baseHp = mysqli_fetch_assoc($getBaseHp);
        $newHp = $baseHp["coreShields"] * (($researchUsers["researchShd"]+1)*0.1+1);
        $updateHp = mysqli_query($conn, "UPDATE userbase SET coreShields=$newHp WHERE userID=$researchUsers[userID]");
        unset($getBaseHp, $baseHp, $newHp, $updateHp);
      }
      elseif ($researchUsers["currentResearch"] == 4) {
        $updateRes = "researchSpeed";
        $index = 21;
      } elseif ($researchUsers["currentResearch"] == 5) {
          $updateRes = "researchSubspace";
          $index = 24;
      }
      $newRes = $researchUsers[$updateRes]+1;
      $updateResearch = mysqli_query($conn, "UPDATE userresearch SET $updateRes=$newRes, currentResearch=0, researchTime=0 WHERE userID=$researchUsers[userID]");
      $getUserTask = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$researchUsers[userID]");
      $userTask = mysqli_fetch_assoc($getUserTask);
      $unserializeUser = unserialize($userTask["userObjectives"]);
      if ($index > 0 && $index < 22) {
      $getTask = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$userTask[currentQuest]");
      $task = mysqli_fetch_assoc($getTask);
      $unserializeTemplate = unserialize($task["objectives"]);


      if ($unserializeTemplate[$index] > 0) {
        $unserializeUser[$index] += 1;
        if ($unserializeUser[$index] > $unserializeTemplate[$index]) {
          $serialize = serialize($unserializeUser);
          $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$researchUsers[userID]");
        } else {
          $unserializeUser[$index] = $unserializeTemplate[$index];
          $serialize = serialize($unserializeUser);
          $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$researchUsers[userID]");
        }
      }
      }
    } else {
      "";
    }
  } else {
    "";
  }
}
}
// end of the event loop
exit();


 ?>
