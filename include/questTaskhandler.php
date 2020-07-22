<?php

    $sql = mysqli_query($conn, "SELECT userObjectives, currentQuest FROM userquests WHERE userID=$userInfo[userID]");
    $getCurrentMission = mysqli_fetch_assoc($sql);
    if ($getCurrentMission["currentQuest"] > 0) {
      $sql = mysqli_query($conn, "SELECT * FROM quests WHERE questID=$getCurrentMission[currentQuest]");
      $missionParams = mysqli_fetch_assoc($sql);
      $unserializeTemplate = unserialize($missionParams["objectives"]);
      $unserializeUser = unserialize($getCurrentMission["userObjectives"]);
      $objectiveNumber = $itemID + 2;
  
      if ($unserializeTemplate[$objectiveNumber] > 0) {
        if ($Ammount + $unserializeUser[$objectiveNumber] > $unserializeTemplate[$objectiveNumber]) {
          $unserializeUser[$objectiveNumber] = $unserializeTemplate[$objectiveNumber];
          $serializeObjectives = serialize($unserializeUser);
          $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serializeObjectives' WHERE userID=$userInfo[userID]");
        } else {
          $unserializeUser[$objectiveNumber] += $amount;
          $serializeObjectives = serialize($unserializeUser);
          $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serializeObjectives' WHERE userID=$userInfo[userID]");
        }
      } 
    }
 ?>
