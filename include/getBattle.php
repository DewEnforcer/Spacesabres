<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
    "";
  } else {
        $_SESSION["sid"] = "";
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}
//↓ if the user joins the lobby, it will update time of his presence in the database , after that there is an interval ajax taking care of this
if (isset($_POST["battleid"]) && !empty($_POST["battleid"]) && isset($_POST["type"]) && isset($_POST["action"]) && $_POST["action"] == "start" ) {
  $array = [];
  $battleid = mysqli_real_escape_string($conn, $_POST["battleid"]);
  $type = $_POST["type"];
  $date = date("U")+60;
  if ($type == "user") {
      $sql = mysqli_query($conn, "UPDATE usermovement SET hudPresentAttacker=$date WHERE userID=$userInfo[userID] AND lobbyID='$battleid'");
      $query = "userID";
  } else if ($type == "enemy") {
      $sql = mysqli_query($conn, "UPDATE usermovement SET hudPresentTarget=$date WHERE lobbyID='$battleid' AND targetUserID=$userInfo[userID]");
      $query = "targetUserID";
  }

//↓ since js provides the lobby ID in the ajax , it is used here
    $sql = "SELECT * FROM usermovement WHERE lobbyID=? AND $query=? AND missionType=1";
    $stmt = mysqli_stmt_init($conn);
    $checkLobby = mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "si", $battleid, $userInfo["userID"]);
    mysqli_stmt_execute($stmt);
    $preResult = mysqli_stmt_get_result($stmt);
    $battleInfo = mysqli_fetch_assoc($preResult);
    if ($checkLobby === TRUE && $battleInfo["userID"] > 0) {
      if ($battleInfo["returnWay"] == 1 && $battleInfo["missionType"] == 2) {
        echo "end";
        exit();
      }
        $fleetAttacker = unserialize($battleInfo["fleetNumbers"]);
        $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$battleInfo[userID]");
        $attackerNick = mysqli_fetch_assoc($sql);
        if ($battleInfo["type"] == "player") {
          if ($battleInfo["rounds"] == 1) {
            if ($type == "user") {
              $targetResearch = "rounderror";
              $sql = mysqli_query($conn, "SELECT researchDmg, researchShd, researchHp FROM userresearch WHERE userID=$battleInfo[userID]");
              $userResearch = mysqli_fetch_assoc($sql);
            } elseif ($type == "enemy") {
              $sql = mysqli_query($conn, "SELECT researchDmg, researchShd, researchHp FROM userresearch WHERE userID=$battleInfo[targetUserID]");
              $targetResearch = mysqli_fetch_assoc($sql);
              $userResearch = "rounderror";
            }

          } else {
              $sql = mysqli_query($conn, "SELECT researchDmg, researchShd, researchHp FROM userresearch WHERE userID=$battleInfo[targetUserID]");
              $targetResearch = mysqli_fetch_assoc($sql);

              $sql = mysqli_query($conn, "SELECT researchDmg, researchShd, researchHp FROM userresearch WHERE userID=$battleInfo[userID]");
              $userResearch = mysqli_fetch_assoc($sql);

          }


          $sql = mysqli_query($conn, "SELECT userID, formationHeavy, formationLight, dockAmount FROM userfleet WHERE pageCoordsX=$battleInfo[attackedUserX] AND pageCoordsY=$battleInfo[attackedUserY] AND mapLocation=$battleInfo[targetMapLocation]");
          $targetUserID = mysqli_fetch_assoc($sql);

          $targetFleet = [0,0,0,0,0,0];
          for ($i=1; $i <= $targetUserID["dockAmount"]; $i++) {
            $dock = "dock$i";
            $sql = mysqli_query($conn, "SELECT $dock FROM userfleet WHERE userID=$targetUserID[userID]");
            $fetch = mysqli_fetch_assoc($sql);
            $fetch = unserialize(gzdecode($fetch[$dock]));
            foreach ($fetch as $ship) {
              $targetFleet[$ship["type"]]++;
            }
          }
          $fetch = "";
          $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$targetUserID[userID]");
          $targetNick = mysqli_fetch_assoc($sql);
          $targetNick = $targetNick["ingameNick"];
          $sql = mysqli_query($conn, "SELECT slot1, slot2, slot3, slot4, slot5, slot6, slot7, slot8, slot9, slot10 FROM userbase WHERE userID=$targetUserID[userID]");
          $battlestation = mysqli_fetch_assoc($sql);
          $sql = mysqli_query($conn, "SELECT coreHealth, coreShields FROM userbase WHERE userID=$targetUserID[userID]"); // // NOTE: SEPERATE QUERY FOR THE JAVASCRIPT PROCESS, DO NOT CONNECT WITH SLOT QUERY
          $battlestationCore = mysqli_fetch_assoc($sql);
          $battlestationCore["coreHealth"] = number_format($battlestationCore["coreHealth"], '0', '.',' ');
          $battlestationCore["coreShields"] = number_format($battlestationCore["coreShields"], '0', '.',' ');
          $battlestationImg = $battlestation; //create copy for visuals
          $index = 1;
          foreach ($battlestation as $module) {
            $slot = "slot".$index."";
            if ($module == 0) {
              $battlestation[$slot] = "Empty";
            }elseif ($module == 1) {
              $battlestation[$slot] = "L-L01";
            }
            elseif ($module == 2) {
              $battlestation[$slot] = "H-L01";
            }
            elseif ($module == 3) {
              $battlestation[$slot] = "M-RS01";
            }
            elseif ($module == 4) {
              $battlestation[$slot] = "M-S01";
            }
            $index++;
          }

        } elseif ($battleInfo["type"] == "npc") {
          $system = "systems".ceil($battleInfo["targetMapLocation"] / 100);
          $sql = mysqli_query($conn, "SELECT npcType, formationHeavy, formationLight, fleetNumbers FROM $system WHERE coordsX=$battleInfo[attackedUserX] AND coordsY=$battleInfo[attackedUserY] AND map=$battleInfo[targetMapLocation]");
          $targetUserID = mysqli_fetch_assoc($sql);
          $fleetNumbers = $targetUserID["fleetNumbers"];
          $targetFleet = unserialize($targetUserID["fleetNumbers"]);
          $targetResearch = ["researchDmg"=>0, "researchShd"=>0, "researchHp"=>0];
          if ($battleInfo["rounds"] == 1) {
            $targetResearch = "rounderror";
          }
          $battlestationCore = "npc";
          $targetNick = $targetUserID["npcType"];
          $targetUserID["userID"] = "npc";
          $battlestation = ["slot1"=>"npc"];
          $battlestationImg = ["slot1"=>"npc"];
          $sql = mysqli_query($conn, "SELECT researchDmg, researchShd, researchHp FROM userresearch WHERE userID=$battleInfo[userID]");
          $userResearch = mysqli_fetch_assoc($sql);
        } else {
          echo "error";
          exit();
        }
      $array["npc"] = $battleInfo["type"];
      $array["attackerFleet"] = $fleetAttacker; //these supply ship numbers to the HUD
      $array["defenderFleet"] = $targetFleet; //these supply ship numbers to the HUD
      $array["attackerFormations"] = [$battleInfo["formationLight"], $battleInfo["formationHeavy"]];
      $array["defenderFormations"] = [$targetUserID["formationLight"], $targetUserID["formationHeavy"]];
      $array["attackerResearch"] = $userResearch;
      $array["targetNick"] = $targetNick;
      if ($type == "enemy") {
          $array["battlestationCore"] = $battlestationCore;
      } elseif ($type == "user") {
        if ($battleInfo["rounds"] == 1) {
            $array["battlestationCore"] = "";
        } elseif ($battleInfo["rounds"] == 2 || $battleInfo["rounds"] == 3) {
            $array["battlestationCore"] = $battlestationCore;
        }
      } else {
        $array["battlestationCore"] = "";
      }
      $array["targetResearch"] = $targetResearch;
      $array["attackerNick"] = $attackerNick["ingameNick"];
      $array["battlestationInfo"] = $battlestation;
      $array["battlestationImg"] = $battlestationImg;
      $array["lobbyID"] = $battleInfo["lobbyID"];
      $array["time"] = $battleInfo["travelTime"];
      $array["round"] = $battleInfo["rounds"];
      $array["end"] = ($array["time"] - date("U"))-45;
      if ($battleInfo["travelTime"] - date("U") > 45 && $array["round"] == 1 && $battleInfo["type"] != "npc") {
        if ($type == "user") {
          $array["defenderFleet"] = [];
          $array["defenderFormations"] = [];
        } elseif ($type == "enemy") {
          $array["attackerFleet"] = [];
          $array["attackerFormations"] = [];
        }
      }

      print json_encode($array);
      exit();
  } else {
    echo "error";
    exit();
  }


} elseif (isset($_POST["battleid"]) && !empty($_POST["battleid"]) && isset($_POST["type"]) && isset($_POST["action"]) && $_POST["action"] == "formationSwitch" && isset($_POST["newFormation"])) {
  $newFormation = $_POST["newFormation"];
  if ($newFormation == "Line" || $newFormation == "Echelon" || $newFormation == "Wedge" || $newFormation == "Column") {
    $query = "formationHeavy";
} elseif ($newFormation == "Phalanx" || $newFormation == "Arrow" || $newFormation == "Turtle" || $newFormation == "Pincer" || $newFormation == "Lance") {
    $query = "formationLight";
} else {
  echo "error";
  exit();
}
if ($_POST["type"] == "user") {
  $sql = mysqli_query($conn, "SELECT $query FROM usermovement WHERE userID=$userInfo[userID]");
  $checkForm = mysqli_fetch_assoc($sql);
  if ($checkForm[$query] == $newFormation) {
    echo "nochange";
    exit();
  }
  $sql = "UPDATE usermovement SET $query=? WHERE userID=? AND lobbyID=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "error";
    exit();
  };
  mysqli_stmt_bind_param($stmt, "sis", $newFormation, $userInfo["userID"], $_POST["battleid"]);
  mysqli_stmt_execute($stmt);
  echo "success";
  exit();
} elseif ($_POST["type"] == "enemy") {
  $sql = mysqli_query($conn, "SELECT $query FROM userfleet WHERE userID=$userInfo[userID]");
  $checkForm = mysqli_fetch_assoc($sql);
  if ($checkForm[$query] == $newFormation) {
    echo "nochange";
    exit();
  }
  $sql = "UPDATE userfleet SET $query=? WHERE userID=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "error";
    exit();
  };
  mysqli_stmt_bind_param($stmt, "si", $newFormation, $userInfo["userID"]);
  mysqli_stmt_execute($stmt);
  echo "success";
  exit();
}
} elseif (isset($_POST["battleid"]) && !empty($_POST["battleid"]) && isset($_POST["type"]) && isset($_POST["action"]) && $_POST["action"] == "formationCheck") {
  if ($_POST["type"] == "user") {
    $sql = "SELECT attackedUserX, attackedUserY, targetMapLocation FROM usermovement WHERE lobbyID=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "error";
      exit();
    };
    mysqli_stmt_bind_param($stmt, "s", $_POST["battleid"]);
    mysqli_stmt_execute($stmt);
    $getResult = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_assoc($getResult);

    $sql = mysqli_query($conn, "SELECT formationLight, formationHeavy FROM userfleet WHERE pageCoordsX=$result[attackedUserX] AND pageCoordsY=$result[attackedUserY] AND mapLocation=$result[targetMapLocation]");
    $newFormations = mysqli_fetch_assoc($sql);
      $array[0] = $newFormations["formationLight"];
      $array[1] = $newFormations["formationHeavy"];
    print json_encode($array);
    exit();
  } elseif ($_POST["type"] == "enemy") {
    $sql = "SELECT formationHeavy, formationLight FROM usermovement WHERE lobbyID=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "error";
      exit();
    };
    mysqli_stmt_bind_param($stmt, "s", $_POST["battleid"]);
    mysqli_stmt_execute($stmt);
    $getResult = mysqli_stmt_get_result($stmt);
    $newFormations = mysqli_fetch_assoc($getResult);
      $array[0] = $newFormations["formationLight"];
      $array[1] = $newFormations["formationHeavy"];
    print json_encode($array);
    exit();
  } else {
    echo "error";
    exit();
  }
} elseif (isset($_POST["id"]) && !empty($_POST["id"]) && isset($_POST["type"]) && isset($_POST["action"]) && $_POST["action"] == "update") {
  $date = date("U")+80;
  if ($_POST["type"] == "user") {
    $query = "hudPresentAttacker";
  } elseif ($_POST["type"] == "enemy") {
      $query = "hudPresentTarget";
    }
  $sql = "UPDATE usermovement SET $query=? WHERE lobbyID=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "is", $date, $_POST["id"]);
  mysqli_stmt_execute($stmt);
  exit();
} else if (isset($_POST["action"]) && isset($_POST["battleid"]) && isset($_POST["type"]) && $_POST["action"] == "additionalData") {
  $player = $_POST["type"];
  $battleID = $_POST["battleid"];
  if ($player == "user") {
    $query = "userID";
  } elseif ($player == "enemy") {
    $query = "targetUserID";
  }
  $sql = "SELECT travelTime, targetUserID, userID FROM usermovement WHERE $query=? AND lobbyID=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "is", $userInfo["userID"], $battleID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $checkTime = mysqli_fetch_assoc($result);

  if ($checkTime["travelTime"] == "") {
    echo "error";
    exit();
  }
  $returnFleet = [0,0,0,0,0,0];
  $returnForms = [];
  if ($checkTime["travelTime"] - date("U") <= 45) {
    if ($player == "user") {
      $sql = mysqli_query($conn, "SELECT dockAmount, formationLight, formationHeavy FROM userfleet WHERE userID=$checkTime[targetUserID]");
      $targetDocks = mysqli_fetch_assoc($sql);
      $returnForms[0] = $targetDocks["formationLight"];
      $returnForms[1] = $targetDocks["formationHeavy"];
      for ($i=1; $i <= $targetDocks["dockAmount"]; $i++) {
        $dock = "dock$i";
        $sql = mysqli_query($conn, "SELECT $dock FROM userfleet WHERE userID=$checkTime[targetUserID]");
        $fetch = mysqli_fetch_assoc($sql);
        $fetch = unserialize(gzdecode($fetch[$dock]));
        foreach ($fetch as $ship) {
          $returnFleet[$ship["type"]]++;
        }
      }
    } elseif ($player == "enemy") {
      $sql = "SELECT fleetNumbers, formationLight, formationHeavy FROM usermovement WHERE lobbyID=? AND targetUserID=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "si", $battleID, $userInfo["userID"]);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $attackerFleet = mysqli_fetch_assoc($result);

      if ($attackerFleet["formationLight"] == "") {
        exit();
      }
      $returnFleet = $attackerFleet["fleetNumbers"];
      $returnForms[0] = $attackerFleet["formationLight"];
      $returnForms[1] = $attackerFleet["formationHeavy"];
    } else {
      echo "error";
      exit();
    }
    echo json_encode([$returnFleet, $returnForms]);
    exit();
  } else {
    echo "errortime";
    exit();
  }

}
else {
  echo "error";
  exit();
}
 ?>
