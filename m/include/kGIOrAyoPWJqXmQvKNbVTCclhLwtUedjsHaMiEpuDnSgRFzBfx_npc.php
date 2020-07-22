<?php
require "dbh.inc.php";
// dmg *0.8, shd , hp;
$array = [];
$arrayStats = [];
function generateFleets($maxPoints, $coef, $rank) {
  $shipParams = [[800,1040,1280,3200,4800,8000], [0,0,5000,30000,35000,75000], [10000,12500,20000,60000,72500,225000]];
  $arrayFleetNumbers = [];
  $fleet = [];
  $arrayStats = [];
  $maxPoints = $maxPoints * $coef;
  $arrayFleetNumbers[0] = ceil(($maxPoints / 2) * ((2/60)) + ((((($maxPoints / 25) * (25/60)) /1.25)*25)/2));
  $arrayFleetNumbers[1] = ceil(($maxPoints / 3) * (3/60) + ((((($maxPoints / 15) * (15/60)) /2)*15)/3));
  $arrayFleetNumbers[2] = ceil(($maxPoints / 5) * (5/60) + ((((($maxPoints / 10) * (10/60)) /4)*10)/5));
  if ($rank != "Pirate" && $rank != "Master Pirate" && $rank != "Chief Pirate") {
  $arrayFleetNumbers[3] = ceil(($maxPoints / 10) * (10/60) - ((($maxPoints / 10) * (10/60)) /4));
  $arrayFleetNumbers[4] = ceil(($maxPoints / 15) * ( 15/60) - ((($maxPoints / 15) * (15/60)) /2));
  $arrayFleetNumbers[5] = ceil(($maxPoints / 25) * (25/60) - ((($maxPoints / 25) * (25/60)) /1.25));
} else {
  $arrayFleetNumbers[3] = 0;
  $arrayFleetNumbers[4] = 0;
  $arrayFleetNumbers[5] = 0;
}
  $arrayFleetNumbers[6] = $rank;
  for ($i=0; $i < 6; $i++) {
    for ($k=0; $k < $arrayFleetNumbers[$i]; $k++) {
      $shipObj = ["type"=>$i, "stats"=>[$shipParams[0][$i], $shipParams[1][$i], 0]];
      array_push($fleet, $shipObj);
    }
  }
  $array = [$arrayFleetNumbers, $fleet];
  return $array;
}
function generateRandomNumber() {
  $keyLength = 2;
  $str = "1234567890";
  $randNumber = substr(str_shuffle($str), 0 , $keyLength);
  return $randNumber;
}
function checkForSunY ($varToCheck) {
  if ($varToCheck <= 300) {
    if ($varToCheck >= 150) {
      $result = false;
    } else {
      $result = true;
    }
  }else {
    $result = true;
  }
  return $result;
}
function checkForSunX ($varToCheck) {
  if ($varToCheck <= 350) {
    if ($varToCheck >= 500) {
      $result = false;
    } else {
      $result = true;
    }
  } else {
    $result = true;
  }
  return $result;
}
function checkForOffset ($vartocheck, $alreadygenerated) {
  if ($vartocheck < $alreadygenerated-40) {
    $result = true;
  } else if($vartocheck > $alreadygenerated+40) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}
function checkCoords($conn, $randX, $randY, $maximalMap) {
  $system = "systems".ceil($maximalMap/100);
  if ($randX <740 && $randY < 370 && checkForSunX($randX) == true && checkForSunY($randY) == true ) {
  $sql = "SELECT * FROM $system WHERE map=$maximalMap";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)) {
    if ($row['coordsX'] != $randX && $row['coordsY'] != $randY) {
      if ($randX < $row["coordsX"]-35 || $randX > $row["coordsY"]+35) {
        if ($randY < $row["coordsY"]-35 || $randY > $row["coordsY"]+35) {
          $coordExists = false;
        } else {
          $coordExists = true;
          break;
        }
      } else {
        $coordExists = true;
        break;
      }
    } else {
      $coordExists = true;
      break;
    }
  }
} else {
  $coordExists = true;
  return $coordExists;
}
}

function generateCoords($conn, $maximalMap) {
  $keyLength = 3;
  $str = "1234567890";
  $randX = substr(str_shuffle($str), 0 , $keyLength);
  $randY = substr(str_shuffle($str), 0 , $keyLength);
  $checkCoords= checkCoords($conn, $randX, $randY, $maximalMap);

  while ($checkCoords == true) {
    $randX = substr(str_shuffle($str), 0 , $keyLength);
    $randY = substr(str_shuffle($str), 0 , $keyLength);
    $checkCoords = checkCoords($conn, $randX, $randY, $maximalMap);
  }
  $coords = [$randX, $randY];
  return $coords;
}
// end of all functions
// points for each ship
$points = [2,3,5,10,15,25];
// ↓ deletes all previous npcs before generating starts
  $a = 1;
  while ($a <= 10) {
    $system = "systems$a";
    $deletePrevNpc = mysqli_query($conn, "DELETE FROM $system");
    $a++;
  }
// ↓ npc generator starts here
$index = 1;
while ($index <= 999) {
  $sql = mysqli_query($conn, "SELECT * FROM userfleet WHERE mapLocation=$index");
  if (mysqli_num_rows($sql) == 0) {
    $index++;
    continue;
  }
  $getMaxFleetPoints = mysqli_query($conn, "SELECT fleetPoints FROM userfleet WHERE mapLocation=$index AND admin=0");
  $a = 0;
  if (mysqli_num_rows($getMaxFleetPoints) > 0) {
    while ($maxFleetPoints = mysqli_fetch_assoc($getMaxFleetPoints)) {
      $totalPoints[$a] = $maxFleetPoints["fleetPoints"];
      $a++;
      }
      $averagePointsMap = array_sum($totalPoints) / mysqli_num_rows($getMaxFleetPoints);
      if ($averagePointsMap < 1500) {
        $averagePointsMap = 1500;
      }
      $mapNumber = ceil($index/100);
      $system = "systems$mapNumber";
      $generateNpc = [];
      $generateNpcFleet = [];
      $generateNpc[1]= generateCoords($conn, $index);
      $generateNpc[2]= generateCoords($conn, $index);
      $generateNpc[3]= generateCoords($conn, $index);
      $generateNpc[4]= generateCoords($conn, $index);
      $generateNpc[5]= generateCoords($conn, $index);
      $generateNpc[6]= generateCoords($conn, $index);
      $generateNpc[7]= generateCoords($conn, $index);
      $generateNpc[8]= generateCoords($conn, $index);
      $generateNpc[9]= generateCoords($conn, $index);
      $generateNpc[10]= generateCoords($conn, $index);
      $generateNpc[11]= generateCoords($conn, $index);
      $generateNpc[12]= generateCoords($conn, $index);
      $generateNpc[13]= generateCoords($conn, $index);
      $generateNpc[14]= generateCoords($conn, $index);
      $generateNpc[15]= generateCoords($conn, $index);
      $generateNpc[16]= generateCoords($conn, $index);
      $generateNpcFleet[1] = generateFleets( 100, 1, "Pirate");
      $generateNpcFleet[2] = generateFleets( 100, 1, "Pirate");
      $generateNpcFleet[3] = generateFleets( 100, 1, "Pirate");
      $generateNpcFleet[4] = generateFleets( 500, 1, "Chief Pirate");
      $generateNpcFleet[5] = generateFleets( 500, 1, "Chief Pirate");
      $generateNpcFleet[6] = generateFleets( 500, 1, "Chief Pirate");
      $generateNpcFleet[7] = generateFleets( 1000, 1, "Master Pirate");
      $generateNpcFleet[8] = generateFleets( 1000, 1, "Master Pirate");
      $generateNpcFleet[9] = generateFleets( 1000, 1, "Master Pirate");
      $generateNpcFleet[10] = generateFleets( $averagePointsMap , 0.1, "Xamon scout");
      $generateNpcFleet[11] = generateFleets( $averagePointsMap , 0.2, "Xamon Ensign");
      $generateNpcFleet[12] = generateFleets( $averagePointsMap , 0.3, "Xamon Lieutenant");
      $generateNpcFleet[13] = generateFleets( $averagePointsMap , 0.4, "Xamon Commander");
      $generateNpcFleet[14] = generateFleets( $averagePointsMap , 0.6, "Xamon Captain");
      $generateNpcFleet[15] = generateFleets( $averagePointsMap , 0.7, "Xamon Admiral");
      $generateNpcFleet[16] = generateFleets( $averagePointsMap , 0.8, "Xamon Fleet Admiral");
      $i =1;
      while ($i <= 16) {
        $pageX = $generateNpc[$i][0];
        $pageY = $generateNpc[$i][1];
        $rank = $generateNpcFleet[$i][0][6];
        array_splice($generateNpcFleet[$i][0], 6, 1); //removes the rank from fleets
        $fleetNumbers = serialize($generateNpcFleet[$i][0]);
        $fleetStats = gzencode(serialize($generateNpcFleet[$i][1]),9);
        $sql = "INSERT INTO $system (coordsX, coordsY, map, fleetNumbers, fleet, npcType) VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql) == false) {
          $i++;
          continue;
        }
        mysqli_stmt_bind_param($stmt, "iiisss", $pageX, $pageY, $index, $fleetNumbers, $fleetStats, $rank);
        mysqli_stmt_execute($stmt);
        $i++;
      }
  } else {
    $index++;
    continue;
  }

$index++;
}

 ?>
