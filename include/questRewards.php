<?php
  session_start();
  if (isset($_GET["id"]) && isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $userNick =  mysqli_real_escape_string($conn, $_SESSION["sid"]);
  $sql = "SELECT userID, credits, hyperid, natium FROM users WHERE sessionID='$userNick'";
  $getID = mysqli_query($conn, $sql);
  if (mysqli_num_rows($getID) == 0) {
    session_unset();
    session_destroy();
    header("location: ../index.php");
    exit();
  }
  $ID = mysqli_fetch_assoc($getID);
  $getUserCurrentQuest = mysqli_query($conn, "SELECT * FROM userquests WHERE userID=$ID[userID]");
  $currentUserQuest=mysqli_fetch_assoc($getUserCurrentQuest);
  $currentQuest = $currentUserQuest["currentQuest"];
  $unserializedUserObjectives = unserialize($currentUserQuest["userObjectives"]);

    $getQuestRewards = mysqli_query($conn, "SELECT rewards, objectives FROM quests WHERE questID = $currentQuest");
    $questRewards = mysqli_fetch_assoc($getQuestRewards);

    $unserializedRewards = unserialize($questRewards["rewards"]);
    $unserialzedObjectives = unserialize($questRewards["objectives"]);
    $i = 0;
    $check = 0;
    foreach ($unserialzedObjectives as $key) {
      if ($key <= $unserializedUserObjectives[$i]) {
        $check++;
      }
      $i++;
    }
    if ($check == 24) {
      $newUriValue = $unserializedRewards[1] + $ID["hyperid"];
      $newCredValue = $unserializedRewards[0] + $ID["credits"];
      $newNatium = $unserializedRewards[2] + $ID["natium"];
      $newMission = $currentUserQuest["questsCompleted"]+1;
      $sql = mysqli_query($conn, "SELECT * FROM userresearch WHERE userID=$ID[userID]");
      $userResearch = mysqli_fetch_assoc($sql);
      $shipParams = [10000,12500,20000,60000,72500,225000];
      for ($i=0; $i < 6; $i++) {
        $shipParams[$i] = $shipParams[$i] * ($userResearch["researchHp"]*0.1+1);
      }
      $sql = mysqli_query($conn, "SELECT dockAmount, dockIncrement, fleetPoints FROM userfleet WHERE userID=$ID[userID]");
      $userDock = mysqli_fetch_assoc($sql);
      $realDocks = [];
      $pointsArr = [2,3,5,10,15,25];
      $points = 0 + $userDock["fleetPoints"];
      $returningFleet = [];

      $arrayParams = ["lasers"=>[0,2,4,12,15,25], "rockets"=>[4,2,0,0,0,0], "shields"=>[0,0,1,6,7,15], "hyperspace"=>[1,1,1,1,1,1]];
      $arrayEvasion = [0.20,0.20,0.3,0.1,0.1,0.05];
      $t = 0;
      for ($d=3; $d < 9; $d++) {
        for ($i=0; $i < $unserializedRewards[$d]; $i++) {
          $shipLasers = [];  //creates ship slots for equipment
          $shipRockets = [];
          $shipShields = [];
          $shipHyperspace = [];
          for ($j=0; $j < $arrayParams["lasers"][$t]; $j++) {
            array_push($shipLasers, 0);
          }
          for ($j=0; $j < $arrayParams["rockets"][$t]; $j++) {
            array_push($shipRockets, 0);
          }
          for ($j=0; $j < $arrayParams["shields"][$t]; $j++) {
            array_push($shipShields, 0);
          }
          for ($j=0; $j < $arrayParams["hyperspace"][$t]; $j++) {
            array_push($shipHyperspace,0);
          } // end of slot creation                                                                                                                                             dmg, shd, hp , evasion
          $param = ["number"=>$userDock["dockIncrement"], "lasers"=>$shipLasers, "rockets"=>$shipRockets, "shields"=>$shipShields, "hyperspace"=>$shipHyperspace, "stats"=>[0,0,$shipParams[$t],$arrayEvasion[$t]], "type"=>$t];
          array_push($returningFleet, $param);
          $userDock["dockIncrement"]++;
          $points += $pointsArr[$t];
        }
        $t++;
      }
      $sql = mysqli_query($conn, "UPDATE userfleet SET fleetPoints=$points WHERE userID=$ID[userID]");
      for ($i=1; $i <= $userDock["dockAmount"]; $i++) {
        $dockQ = "dock$i";
        $sql = mysqli_query($conn, "SELECT $dockQ FROM userfleet WHERE userID=$ID[userID]");
        $fetch = mysqli_fetch_assoc($sql);
        $fetch = unserialize(gzdecode($fetch[$dockQ]));
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
          $ship["stats"][2] = $shipParams[$ship["type"]];
          array_push($realDocks[$d], $ship); //sends the ship into dock
          array_splice($returningFleet, 0, 1); //removes the ship from returning fleet
        } else {
          break; // dock doesnt have anymore space, therefore break from this loop without increasing ship index (since we havent assigned this ship yet)
        }
      }
      $d++;
    }
      $newDmgRes = $userResearch["researchDmg"]+$unserializedRewards[9];
      $newHpRes = $userResearch["researchHp"]+$unserializedRewards[10];
      $newShdRes = $userResearch["researchShd"]+$unserializedRewards[11];
      $newSpeedRes = $userResearch["researchSpeed"]+$unserializedRewards[12];
    } else {
      $_SESSION["mission_result"] =  "notcomplete";
      header("location: ../internalMissions.php");
      exit();
    }

    $addRewards = "UPDATE users SET hyperid=$newUriValue, credits=$newCredValue, natium=$newNatium WHERE sessionID='$userNick'";
    $resetQuests = "UPDATE userquests SET userObjectives='a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', questAccomplished=0, currentQuest=0, questsCompleted=$newMission WHERE userID=$ID[userID]";
    for ($i=1; $i <= $userDock["dockAmount"]; $i++) {
      $dock = "dock$i";
      $dockQuery = gzencode(serialize($realDocks[$i-1]));
      $sql = "UPDATE userfleet SET $dock=? WHERE userID=$ID[userID]";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $dockQuery);
      echo mysqli_error($conn);
      mysqli_stmt_execute($stmt);
    }
    $sql = mysqli_query($conn, "UPDATE userfleet SET dockIncrement=$userDock[dockIncrement] WHERE userID=$ID[userID]");
    $addResearch = "UPDATE userresearch SET researchDmg=$newDmgRes, researchHp=$newHpRes, researchShd=$newShdRes, researchSpeed=$newSpeedRes WHERE userID=$ID[userID]";
    if ($conn->query($addRewards) === TRUE && $conn->query($resetQuests) === TRUE && $conn->query($addResearch)=== TRUE) {
      $_SESSION["mission_result"] = "completed";
      header("location: ../internalMissions.php");
      exit();
    }
    else {
      $_SESSION["mission_result"] =  "sql";
      header("location: ../internalMissions.php");
      exit();
    }
} else {
  $_SESSION["mission_result"] =  "wrongid";
  session_unset();
  session_destroy();
  header("location: ../index.php");
  exit();
}
 ?>
