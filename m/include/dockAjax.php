<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
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
    $arrayPoints = [2,3,5,10,15,25];
function checkIds(&$array, $randID) {

foreach ($array as $key) {
  if ($key["squadronID"] == $randID) {
    $idExists = true;
    break;
  } else {
    $idExists = false;
  }
}
  return $idExists;
}
function countEquipped (&$ship, $equipment) {
  $amount = 0;
  for ($l=0; $l < count($ship[$equipment]); $l++) {
    if ($ship[$equipment][$l] > 0) {
      $amount++;
    }
  }
  return $amount;
}
function generateResponseBody (&$unserializedSquadrons) {
  $arrayParams = ["lasers"=>[0,2,4,10,15,25], "rockets"=>[4,2,0,0,0,0], "energy"=>[1,1,1,2,2,3], "hyperspace"=>[1,1,1,1,1,1]];
  $arrayNames = ["Hornet","Spacefire","Starhawk","Peacemaker","Centurion","Na-Thalis Destroyer"];
  $arrayData = ["hornet","spacefire","starhawk","peacemaker","centurion","nathalis"];
  echo '<tr class="header_tr">
      <th>Ship number</th>
      <th>Ship type</th>
      <th>Equipment</th>
    </tr>';
    $i = 0;
    if (empty($unserializedSquadrons) == TRUE) {
      echo "<tr><td colspan=\"3\">Currently there are no ships present in the dock</tr>";
    }
  foreach ($unserializedSquadrons as $shipType) {
    foreach ($shipType as $ship) {
      if (strpos($ship["number"], "away") !== false) {
        continue;
      }
      echo "<tr class=\"ship_tab\" id=\"$ship[number]\" type=\"$arrayData[$i]\">";
      echo "<td>$ship[number]</td>";
      echo "<td>$arrayNames[$i]</td>";
      $lasers = countEquipped($ship, "lasers");
      $rockets = countEquipped($ship, "rockets");
      $energy =  countEquipped($ship, "energy");
      $hyperspace = countEquipped($ship, "hyperspace");
      echo "<td><div class='equipment_wrapper'>Lasers: $lasers/".$arrayParams["lasers"][$i]." | Missile platforms: $rockets/".$arrayParams["rockets"][$i]." | Energy generators: $energy/".$arrayParams["energy"][$i]." | Hyperspace engines: $hyperspace/".$arrayParams["hyperspace"][$i]." </div></td>";
      echo "</tr>";
    }
  $i++;
  }
}
if (isset($_POST["action"]) && isset($_POST["data"]) && empty($_POST["action"]) == false ) {
  $action = $_POST["action"];
  $data = $_POST["data"];
  if ($action == "remove") {
    if ($data != "") {
      try {
        $dataArray = json_decode($data);
      } catch (\Exception $e) {
        echo "error";
        exit();
      }
      $dockSelect = $dataArray[2];
      $dockNum = "dock$dockSelect";
      $sql = mysqli_query($conn, "SELECT $dockNum, fleetPoints FROM userfleet WHERE userID=$userInfo[userID]");
      $userDock = mysqli_fetch_assoc($sql);
      $oldPoints = $userDock["fleetPoints"];
      $userDock = unserialize(gzdecode($userDock[$dockNum]));

      $position = 0;
      $status;
      foreach ($userDock as $key) {
        if ($key["number"] == $dataArray[0]) {
          $removePoints = $arrayPoints[$key["type"]];
          $status = true;
          break;
        }
        $position++;
      }
      if ($status == true) {
        $oldPoints -= $removePoints;
        array_splice($userDock, $position, 1);
        $queryDock = gzencode(serialize($userDock));
        $shipBook = "UPDATE userfleet SET $dockNum=?, fleetPoints=? WHERE userID=?";
        $stmt = mysqli_stmt_init($conn);
        $check = mysqli_stmt_prepare($stmt, $shipBook);
        mysqli_stmt_bind_param($stmt, "sii", $queryDock, $oldPoints, $userInfo["userID"]);
        mysqli_stmt_execute($stmt);
        if ($check !== false) {
          echo "success";
          exit();
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
  } elseif ($action == "switch") {
    if (is_numeric($data) == false || $data > 10 || $data < 1) {
      echo "error";
      exit();
    }
    $dockSelect = $data;
    $dockNum = "dock$dockSelect";
    $sql = mysqli_query($conn, "SELECT $dockNum FROM userfleet WHERE userID=$userInfo[userID]");
    $switchedDock = mysqli_fetch_assoc($sql);

    $decompile = json_encode(unserialize(gzdecode($switchedDock[$dockNum])));
    $compileJS = gzencode($decompile);

    header("Content-type: text/javascript");
    header('Content-Encoding: gzip');

    echo $compileJS;
    exit();
  } elseif ($action == "delete_all") {
    $dockSelect = $data;
    $dockNum = "dock$dockSelect";
    $sql = mysqli_query($conn, "SELECT $dockNum, fleetPoints FROM userfleet WHERE userID=$userInfo[userID]");
    $dock = mysqli_fetch_assoc($sql);
    $fleetPointsOld = $dock["fleetPoints"];
    $dock = unserialize(gzdecode($dock[$dockNum]));
    $fleetNumbers = [0,0,0,0,0,0];
    $removePoints = 0;
    foreach ($dock as $ship) {
      $fleetNumbers[$ship["type"]]++;
    }
    $dock = "";
    for ($i=0; $i < 6; $i++) {
      $minusPoints = $fleetNumbers[$i]*$arrayPoints[$i];
      $removePoints += $minusPoints;
    }
    $newPoints = $fleetPointsOld - $removePoints;
    $template = 'a:0:{}';
    $compress = gzencode($template);

    $sql = "UPDATE userfleet SET $dockNum=?, fleetPoints=? WHERE userID=?";
    $stmt = mysqli_stmt_init($conn);
    $check = mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $compress, $newPoints, $userInfo["userID"]);
    mysqli_stmt_execute($stmt);
    if ($check !== FALSE) {
      echo "success";
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
