<?php
$eventsArray = ["Pirates", "Xamons", "Lost Lab", "Destroyed Shipyard", "Abandoned natium mines", "Lost Credit bank", "Asteroid rich in hyperid", "noevent"];
$randEventPre = rand(1,100);
if ($randEventPre > 0 && $randEventPre <= 58) { // 58% chance for nothing
  $randEvent = 7;
} elseif ($randEventPre > 58 && $randEventPre <= 65) { // 7% chance for lab
  $randEvent = 2;
} elseif ($randEventPre > 65 && $randEventPre <= 72) { // 7% chance for shipyard
  $randEvent = 3;
} elseif ($randEventPre > 72 && $randEventPre <= 79) { // 7% chance for natium
  $randEvent = 4;
} elseif ($randEventPre > 79 && $randEventPre <= 91) { // 12% for credits
  $randEvent = 5;
} elseif ($randEventPre > 91 && $randEventPre <= 100) { // 9% for hyperid
  $randEvent = 6;
}


$event = $eventsArray[$randEvent];
switch ($event) {
  case 'Pirates':
  $group = 1;
  $ships = [];
  break;
  case 'Xamons':
    $group = 1;
  break;
  case 'Lost Lab':
    $group = 2;
    $item = 1;
  break;
  case 'Destroyed Shipyard':
    $group = 2;
    $item = 2;
  break;
  case 'Abandoned natium mines':
    $group = 2;
    $item = 3;
  break;
  case 'Lost Credit bank':
    $group = 2;
    $item = 4;
  break;
  case 'Asteroid rich in hyperid':
    $group = 2;
    $item = 5;
  break;
  case 'noevent':
    $group = 3;
  break;
}
if ($group == 1) {
"";
} elseif ($group == 2) {
  if ($item == 1) {
    $randomizeResearch = rand(1,5);
    switch ($randomizeResearch) {
      case '1':
        $res = "researchDmg";
        $logs = "maximal laser energy cap";
      break;
      case '2':
        $res = "researchHp";
        $logs = "hull alloys";
      break;
      case '3':
        $res = "researchShd";
        $logs = "shield generator maximal energy output";
      break;
      case '4':
        $res = "researchSpeed";
        $logs = "lowering the energy needed for hyperspace jumps";
      break;
      case '5':
        $res = "researchSubspace";
        $logs = "improving new hashing alghorhytms";
      break;
    }
    $sql = mysqli_query($conn, "SELECT $res FROM userresearch WHERE userID=$SelectedComp[userID]");
    $resCurrent = mysqli_fetch_assoc($sql);

    if ($resCurrent[$res] != 50) {
      $resCurrent[$res] += 1;
    }


    $log = "<p>During the patrol in new gained territory by company, we have found an abandoned xamon laboratory. Their logs about $logs seem to be almost intact. We have downloaded them to our isolated computers and we are bringing them back home.</p>";

    $sql = mysqli_query($conn, "UPDATE userresearch SET $res=$resCurrent[$res] WHERE userID=$SelectedComp[userID]");
    $sql = mysqli_query($conn, "UPDATE companydefense SET defLogs='$log' WHERE userID=$SelectedComp[userID]");

    unset($resCurrent, $sql);

  } elseif ($item == 2) {
    $sql = mysqli_query($conn, "SELECT dockIncrement FROM userfleet WHERE userID=$SelectedComp[userID]");
    $dockInc = mysqli_fetch_assoc($sql);
    $arrayShips = [0,0,0,0,0,0];
    for ($i=0; $i < 6; $i++) {
      $countCap = ceil(($defenseFleetNumbers[$i] * 0.2)*($SelectedComp["defenseHours"]/10));
        if ($defenseFleetNumbers[$i] > 0) {
          $randomize = rand(1,$countCap);
          $arrayShips[$i] += $randomize;
          $defenseFleetNumbers[$i] += $arrayShips[$i];
          for ($k=0; $k < $arrayShips[$i]; $k++) {
            $shipObj = constructShipObj($i, $dockInc["dockIncrement"]);
            $dockInc["dockIncrement"]++;
            array_push($defenseFleetObjects, $shipObj);
          }
        }
    }
    $log = "<p>We have found a destroyed shipyard above the new discovered planet that is rich in natiums. Although we weren\'t able to figure out to who did this shipyard belong, we were able to recover many ships.</p>";
    $log .= "<h3>Here is the full list of recovered ships</h3>";
    $log .= "<div class=\'table_wrapper\'><table class=\'table_round1_defender\'>
      <tbody>
      <tr>
        <th colspan=\'2\'><h3>$event</h3></th>
      </tr>
      <tr>
        <th>Ship type</th>
        <th>Found amount</th>
      </tr>
      <tr>
        <td>Hornet</td>
        <td>".$arrayShips[0]."</td>
      </tr>
      <tr>
        <td>Spacefire</td>
        <td>".$arrayShips[1]."</td>
      </tr>
      <tr>
        <td>Starhawk</td>
        <td>".$arrayShips[2]."</td>
      </tr>
      <tr>
        <td>Peacemaker</td>
        <td>".$arrayShips[3]."</td>
      </tr>
      <tr>
        <td>Centurion</td>
        <td>".$arrayShips[4]."</td>
      </tr>
      <tr>
        <td>Nathalis</td>
        <td>".$arrayShips[5]."</td>
      </tr>
      </tbody>
    </table></div>";
    $defenseFleetObjects = gzencode(serialize($defenseFleetObjects));
    $defenseFleetNumbers= serialize($defenseFleetNumbers);
    $sql = "UPDATE usermovement SET fleet=?, fleetNumbers=? WHERE userID=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $defenseFleetObjects, $defenseFleetNumbers, $SelectedComp["userID"]);
    mysqli_stmt_execute($stmt);
  } elseif ($item == 3 || $item == 4 || $item == 5) {
    switch ($item) {
      case '3':
        $type = "natium";
        $coef = 0.04;
      break;
      case '4':
        $type = "credits";
        $coef = 0.1;
      break;
      case '5':
        $type = "hyperid";
        $coef = 0.07;
      break;
    }

    $sql = mysqli_query($conn, "SELECT $type FROM users WHERE userID=$SelectedComp[userID]");
    $userValutes = mysqli_fetch_assoc($sql);

    $userValutes[$type] += ceil($userValutes[$type]*$coef);
    $sql = mysqli_query($conn, "UPDATE users SET $type=$userValutes[$type] WHERE userID=$SelectedComp[userID]");

    $log = "<p>While checking borders of system S-2:375 , we have discovered $event. From what has been left, we were still able to recover ".number_format(ceil($userValutes[$type]*$coef), '0', '.',' ')." $type!</p>";

    $sql = mysqli_query($conn, "UPDATE companydefense SET defLogs='$log' WHERE userID=$SelectedComp[userID]");
    unset($userValutes, $type, $coef);
}
} elseif ($group == 3) {
  unset($eventsArray);
}


 ?>
