<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["data"]) && isset($_POST["action"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, defenseCooldown FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
  } else {
    session_unset();
    session_destroy();
    header("location: ../index.php?error=10");
    exit();
  }

  function checkIds($conn, $randID) {
    $sql = "SELECT lobbyID FROM usermovement";
    $result = mysqli_query($conn, $sql);
    $idExists = false;
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

  if (isset($_POST["action"]) && isset($_POST["data"]) && empty($_POST["action"]) == false) {
    $action = $_POST["action"];
    $data = $_POST["data"];
    if ($data == "") {
      echo "error";
      exit();
    }
    if ($action == "switch") {
      if (is_numeric($data) == false || $data > 10 || $data < 1) {
        echo "error";
        exit();
      }
      $dockSelect = "dock$data";
      $sql = mysqli_query($conn, "SELECT $dockSelect FROM userfleet WHERE userID=$userInfo[userID]");
      $userDock = mysqli_fetch_assoc($sql);

      $decompile = json_encode(unserialize(gzdecode($userDock[$dockSelect])));
      $compileJS = gzencode($decompile);

      header("Content-type: text/javascript");
      header('Content-Encoding: gzip');

      echo $compileJS;
      exit();
    } elseif ($action == "attack") {
      try {
        $attackInfo = json_decode($data);
      } catch (\Exception $e) {
        echo "error";
        exit();
      }
      $sql = mysqli_query($conn, "SELECT fuel, pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE userID=$userInfo[userID]");
      $distanceParams = mysqli_fetch_assoc($sql);

      $sql = mysqli_query($conn, "SELECT researchSpeed FROM userresearch WHERe userID=$userInfo[userID]");
      $UserResearch = mysqli_fetch_assoc($sql);

      $realDocks = [];
      $warFleet = [];
      $selectedShips = $attackInfo[0];
      for ($i=1; $i <= count($selectedShips); $i++) {
        $dock = "dock$i";
          $sql = mysqli_query($conn, "SELECT $dock FROM userfleet WHERE userID=$userInfo[userID]");
          $fetch = mysqli_fetch_assoc($sql);
          $decompile = unserialize(gzdecode($fetch[$dock]));
          array_push($realDocks, $decompile);
      }
      $sql = "SELECT userID, admin FROM userfleet WHERE pageCoordsX=? AND pageCoordsY=? AND mapLocation=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "sss", $attackInfo[1],$attackInfo[2],$attackInfo[3]);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $target = mysqli_fetch_assoc($result);
      $type = "player";
      if ($target["admin"] == 1) {
        echo "admin";
        exit();
      }
      if ($target["userID"] < 1) { // if no planet found , try to check if it isnt npc
        if (is_numeric($attackInfo[3]) == false) {
          echo "error";
          exit();
        }
        $system = "systems".ceil($attackInfo[3]/100);
        $sql ="SELECT npcType FROM $system WHERE coordsX=? AND coordsY=? AND map=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $attackInfo[1], $attackInfo[2], $attackInfo[3]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $target = mysqli_fetch_assoc($result);

        if ($target["npcType"] == "") {
          echo "error";
          exit();
        }
        $target["userID"] = 0;
        $type = "npc";
      }
                          //hornet, spacefire, starhawk ...
      $hyperspaceSpeed = [25000,30000,22500,19000,17000,12500];

      $distance = ["X"=>abs($attackInfo[1] - $distanceParams["pageCoordsX"]), "Y"=>abs($attackInfo[2] - $distanceParams["pageCoordsY"]), "map"=>abs($attackInfo[3] - $distanceParams["mapLocation"])];

      $d = 0; //dock
      $i = 0; // recieved ships to be sent index
      $st = 0; //ship position
      $fuelConsumptionAmount = [0,0,0,0,0,0];
      $fuelConsumption = [0.1,0.15,0.2,0.5,0.5,1];
      $maxSpeed = 1000000;
      $typeArr = ["hornet", "spacefire", "starhawk", "peacemaker", "centurion", "nathalis"];
      //↓ creates ship object for battleengine while also managing max travel speed
      foreach ($realDocks as $dock) {
        $st = 0;
        foreach ($dock as $ship) {
            if (count($selectedShips[$d]) == 0) { // <- made to stop searching if all ships are already selected
              break;
            }
            foreach ($selectedShips[$d] as $key) {

              if ($ship["number"] == $key) {
                if ($ship["hyperspace"][0] == 0) {
                  break; // since ship has no hyperspace engine, it cannot travel , therefore it is not assigned to the warfleet
                }
                if ($ship["hyperspace"][0] == 22) {
                  if ($distance["map"] < 6) {
                    $speed = $hyperspaceSpeed[$ship["type"]] * 1.2;
                  } else {
                    $speed = $hyperspaceSpeed[$ship["type"]];
                  }
                } elseif ($ship["hyperspace"][0] == 23) {
                  if ($distance["map"] > 4 && $distance["map"] < 16) {
                    $speed = $hyperspaceSpeed[$ship["type"]] * 1.2;
                  } else {
                    $speed = $hyperspaceSpeed[$ship["type"]];
                  }
                } elseif ($ship["hyperspace"][0] == 24) {
                  if ($distance["map"] > 14 && $distance["map"] < 31) {
                    $speed = $hyperspaceSpeed[$ship["type"]] * 1.2;
                  } else {
                    $speed = $hyperspaceSpeed[$ship["type"]];
                  }
                }
                if ($maxSpeed > $speed) {
                  $maxSpeed = $speed; //max travel speed
                }
                // 0 - 19, 1 - 20, 2 - 21, 3- 25, 4 - 26
                array_push($warFleet, $ship);
                array_splice($realDocks[$d], $st, 1);
                $fuelConsumptionAmount[$ship["type"]]++;
                $found = true;
                break;
              }
              $i++;
            }
            $i = 0;
            if ($found != true) {
              $st++;
            } else {
              $st = 0;
              $found = false;
            }
        }
        $d++;
      }
      if (empty($warFleet) === true) {
        echo "hyperspace";
        exit();
      }
      $totalConsumptionFuel = [];
      for ($i=0; $i < 6; $i++) {
        $consumption = ceil(($fuelConsumptionAmount[$i] * $fuelConsumption[$i])*(($distance["X"]+$distance["Y"])+($distance["map"]*1000)));
        array_push($totalConsumptionFuel,  $consumption);
      }
      $totalConsumptionFuel = array_sum($totalConsumptionFuel);
      if ($distanceParams["fuel"] - $totalConsumptionFuel < 0) {
        echo "notenoughfuel";
        exit();
      }
      $newFuel = round($distanceParams["fuel"] - $totalConsumptionFuel);
      $distanceUnitPerX = 400;
      $distanceUnitPerY = 400;
      $distanceUnitPerMap = 10000;
      //↓ counts travel time
      $travelTime = round((((($distanceUnitPerX*$distance["X"]) + ($distanceUnitPerY * $distance["Y"]) + ($distanceUnitPerMap * $distance["map"]))/($maxSpeed*($UserResearch["researchSpeed"]*0.1+1)))*60), 0);
      $travelWay = 1;
      $generatedID = generateID($conn);
      $setTime = date("U");
      $travelTime = date("U") + $travelTime;
      $warFleet = gzencode(serialize($warFleet));
      $fleetNumbers = serialize($fuelConsumptionAmount);
      $sql = "INSERT INTO usermovement (targetUserID, attackedUserX, attackedUserY, targetMapLocation, fleet, fleetNumbers ,userID, travelTime, setAttack, travelWay, type, lobbyID) VALUES (?, ?, ? , ?, ?, ?, ?, ?, ?, ?, ?,?)";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "isssssiiiiss", $target["userID"], $attackInfo[1], $attackInfo[2], $attackInfo[3], $warFleet, $fleetNumbers, $userInfo["userID"], $travelTime, $setTime, $travelWay, $type, $generatedID);
      mysqli_stmt_execute($stmt);
      for ($i=1; $i <= count($selectedShips); $i++) {
        $dock = "dock$i";
        $dockUpdate = gzencode(serialize($realDocks[$i-1]));
        $sql = "UPDATE userfleet SET $dock=? WHERE userID=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "si", $dockUpdate, $userInfo["userID"]);
       mysqli_stmt_execute($stmt);
      }

      $sql = mysqli_query($conn, "UPDATE userfleet SET fuel=$newFuel WHERE userID=$userInfo[userID]");
      if ($sql !== false) {
        $_SESSION["coordsX"] = "";
        $_SESSION["coordsY"] = "";
        $_SESSION["map"] = "";
        echo "success";
        exit();
     }
   } elseif ($action == "compdef") {
     if ($userInfo["defenseCooldown"] > date("U")) {
       echo "cooldown";
       exit();
     }
     $sql = mysqli_query($conn, "SELECT userID FROM usermovement WHERE userID=$userInfo[userID] AND missionType=3 AND returnWay=1");
     if (mysqli_num_rows($sql) > 0) {
       echo "error";
       exit();
     }
     try {
       $compDefFleet = json_decode($data);
     } catch (\Exception $e) {
       echo "error";
       exit();
     }
     if ($compDefFleet[1] < 1 && $compDefFleet > 10) {
       echo "time";
       exit();
     }

     $timeFinish = round(date("U")+($compDefFleet[1] * 60 * 60));
    $fleetNumbers = [0,0,0,0,0,0];
     $realDocks = [];
     for ($i=1; $i <= count($compDefFleet[0]); $i++) {
       $dock = "dock$i";
         $sql = mysqli_query($conn, "SELECT $dock FROM userfleet WHERE userID=$userInfo[userID]");
         $fetch = mysqli_fetch_assoc($sql);
         $decompile = unserialize(gzdecode($fetch[$dock]));
         array_push($realDocks, $decompile);
     }
     $defenseFleet = [];
     $d = 0; //dock
     $i = 0;
     $st = 0; //ship position
     foreach ($realDocks as $dock) {
         foreach ($dock as $ship) {
           if (count($compDefFleet[0][$d]) == 0) { // <- made to stop searching if all ships are already selected
             break;
           }
           foreach ($compDefFleet[0][$d] as $key) {
             if ($ship["number"] == $key) {
               if ($ship["hyperspace"][0] == 0) {
                 break; // since ship has no hyperspace engine, it cannot travel , therefore it is not assigned to the warfleet
               }
               if ($ship["stats"][0] == 0) {
                 break; //same rule with hyperspace, has no lasers/rockets , therefore cannot actually defend something
               }

               // 0 - 19, 1 - 20, 2 - 21, 3- 25, 4 - 26
               array_push($defenseFleet, $ship);
               array_splice($compDefFleet[0][$d], $i, 1);
               array_splice($realDocks[$d], $st, 1);
               $fleetNumbers[$ship["type"]]++;
               break;
             }
             $i++;
           }
           $i = 0;
           $st++;
         }
         $st = 0;
       $d++;
     }
     if (empty($defenseFleet) === true) {
       echo "noships";
       exit();
     }
     $rewardsForEach = array(10, 12, 15, 50, 75, 250); //values TO CHANGE IN ALPHA, natium reward
     $reward = [];
     for ($i=0; $i < 6; $i++) {
       array_push($reward, ($rewardsForEach[$i] * $fleetNumbers[$i])*($compDefFleet[1]/10));
     }
     $fleetNumbers = serialize($fleetNumbers);
     $defenseFleet = gzencode(serialize($defenseFleet));
     $returnWay = 1;
     $travelWay = 0;
     $missionType = 3;
     $reward = array_sum($reward);
     $sql = "INSERT INTO usermovement (fleet, fleetNumbers ,userID, defenseHours, travelTime, returnWay, travelWay, reward, missionType) VALUES (?,?,?,?,?,?,?,?,?)";
     $stmt = mysqli_stmt_init($conn);
     mysqli_stmt_prepare($stmt, $sql);
     mysqli_stmt_bind_param($stmt, "ssisiiiii", $defenseFleet, $fleetNumbers, $userInfo["userID"], $compDefFleet[1], $timeFinish, $returnWay, $travelWay, $reward, $missionType);
     mysqli_stmt_execute($stmt);
     for ($i=1; $i <= count($compDefFleet[0]); $i++) {
       $dock = "dock$i";
       $dockUpdate = gzencode(serialize($realDocks[$i-1]));
       $sql = "UPDATE userfleet SET $dock=? WHERE userID=?";
       $stmt = mysqli_stmt_init($conn);
       mysqli_stmt_prepare($stmt, $sql);
       mysqli_stmt_bind_param($stmt, "si", $dockUpdate, $userInfo["userID"]);
       mysqli_stmt_execute($stmt);
     }

     echo "success";
     exit();
   }
  }
}
 ?>
