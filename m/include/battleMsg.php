<?php
require "dbh.inc.php";
for ($i=0; $i < 6; $i++) {
  $lostShipsAttacker[$i] = $originalAttackerFleetNumber[$i] - $attackerFleetnumber[$i];
  $lostShipsTarget[$i] = $originalTargetFleetNumber[$i] - $targetFleetNumber[$i];
}

$countAllLossUser = array_sum($lostShipsAttacker);
$countAllLossTarget = array_sum($lostShipsTarget);

require "countDestroyedPoints.php";
// 1 = target win, 2 == user win , 3== draw // core = 1 - not destroyed, 0 - destroyed

  $message = "<div class='table_wrapper'><table class='table_round".$SelectedAttacks["rounds"]."_attacker'>
  <tbody>
    <tr>
      <th colspan='4'><h3>Attacker</h3></th>
    </tr>
    <tr>
      <th>Ship type</th>
      <th>Pre-battle</th>
      <th>Post-battle</th>
      <th>Casulties</th>
    </tr>
    <tr>
      <td>Hornet</td>
      <td>".$originalAttackerFleetNumber[0]."</td>
      <td>".$attackerFleetnumber[0]."</td>
      <td style='color:#fe3939'>-".$lostShipsAttacker[0]."</td>
    </tr>
    <tr>
      <td>Spacefire</td>
      <td>".$originalAttackerFleetNumber[1]."</td>
      <td>".$attackerFleetnumber[1]."</td>
      <td style='color:#fe3939'>-".$lostShipsAttacker[1]."</td>
    </tr>
    <tr>
      <td>Starhawk</td>
      <td>".$originalAttackerFleetNumber[2]."</td>
      <td>".$attackerFleetnumber[2]."</td>
      <td style='color:#fe3939'>-".$lostShipsAttacker[2]."</td>
    </tr>
    <tr>
      <td>Peacemaker</td>
      <td>".$originalAttackerFleetNumber[3]."</td>
      <td>".$attackerFleetnumber[3]."</td>
      <td style='color:#fe3939'>-".$lostShipsAttacker[3]."</td>
    </tr>
    <tr>
      <td>Centurion</td>
      <td>".$originalAttackerFleetNumber[4]."</td>
      <td>".$attackerFleetnumber[4]."</td>
      <td style='color:#fe3939'>-".$lostShipsAttacker[4]."</td>
    </tr>
    <tr>
      <td>Nathalis</td>
      <td>".$originalAttackerFleetNumber[5]."</td>
      <td>".$attackerFleetnumber[5]."</td>
      <td style='color:#fe3939'>-".$lostShipsAttacker[5]."</td>
    </tr>
    </tbody>
  </table>";
$message .= "<table class='table_round".$SelectedAttacks["rounds"]."_defender'>
  <tbody>
  <tr>
    <th colspan='4'><h3>Defender</h3></th>
  </tr>
  <tr>
    <th>Ship type</th>
    <th>Pre-battle</th>
    <th>Post-battle</th>
    <th>Casulties</th>
  </tr>
  <tr>
    <td>Hornet</td>
    <td>".$originalTargetFleetNumber[0]."</td>
    <td>".$targetFleetNumber[0]."</td>
    <td style='color:#fe3939'>-".$lostShipsTarget[0]."</td>
  </tr>
  <tr>
    <td>Spacefire</td>
    <td>".$originalTargetFleetNumber[1]."</td>
    <td>".$targetFleetNumber[1]."</td>
    <td style='color:#fe3939'>-".$lostShipsTarget[1]."</td>
  </tr>
  <tr>
    <td>Starhawk</td>
    <td>".$originalTargetFleetNumber[2]."</td>
    <td>".$targetFleetNumber[2]."</td>
    <td style='color:#fe3939'>-".$lostShipsTarget[2]."</td>
  </tr>
  <tr>
    <td>Peacemaker</td>
    <td>".$originalTargetFleetNumber[3]."</td>
    <td>".$targetFleetNumber[3]."</td>
    <td style='color:#fe3939'>-".$lostShipsTarget[3]."</td>
  </tr>
  <tr>
    <td>Centurion</td>
    <td>".$originalTargetFleetNumber[4]."</td>
    <td>".$targetFleetNumber[4]."</td>
    <td style='color:#fe3939'>-".$lostShipsTarget[4]."</td>
  </tr>
  <tr>
    <td>Nathalis</td>
    <td>".$originalTargetFleetNumber[5]."</td>
    <td>".$targetFleetNumber[5]."</td>
    <td style='color:#fe3939'>-".$lostShipsTarget[5]."</td>
  </tr>
  </tbody>
</table></div>";
$message .= "<p class='coreInfo".$SelectedAttacks["rounds"]."'>In round ".$SelectedAttacks["rounds"]." defenders battlestation core shields were lowered to ".$destroyedCoreShd." points, and hull to ".$destroyedCoreHp." points</p>";
$message .= "<p class='shotInfo'>$order</p>";
$reportN = "report".$SelectedAttacks["rounds"]."";
$sql = "UPDATE usermovement SET $reportN=? WHERE id=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "si", $message, $attackID);
mysqli_stmt_execute($stmt);

if ($sql === FALSE) {

}
if ($victory == "attacker" || $victory == "defender") {
  $end = true;
} else {
  $end = false;
}

if ($SelectedAttacks["rounds"] == 3 || $end == true) {

  $token = bin2hex(random_bytes(10));

  $sql = mysqli_query($conn, "SELECT report1, report2, report3 FROM usermovement WHERE id=$attackID");
  $getMessage = mysqli_fetch_assoc($sql);

  $m = 0;
  if ($getMessage["report1"] != "") {
    $m += 1;
  }
  if ($getMessage["report2"] != "empty") {
    $m += 1;
  }
  if ($getMessage["report3"] != "empty") {
    $m += 1;
  }
  $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$userID");
  $userNick = mysqli_fetch_assoc($sql);
  if ($type != "npc") {
    $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$targetUserID");
    $targetNick = mysqli_fetch_assoc($sql);
  } else {
    $targetNick["ingameNick"] = $targetFleet["npcType"];
  }

  $currentTime = date("U");
  $to1 = $targetUserID;
  $to2 = $userID;
  $from = 1;

  if ($victory == "defender") {
    $battleStatus = "Total victory for the defender";
    $updateUserFleet = mysqli_query($conn, "DELETE FROM usermovement WHERE userID=$userID AND id=$attackID"); // $attack ID IS PARSED FROM MAIN FLEET CRON
  }
  elseif ($victory == "attacker") {
    $battleStatus = "Total victory for the attacker";
  } elseif ($victory == "draw") {
    $battleStatus = "Fleets of both admirals have survived";
  } elseif ($victory == "drawDestruction") {
    $battleStatus = "Fleets of both admirals have been destroyed";
    $updateUserFleet = mysqli_query($conn, "DELETE FROM usermovement WHERE userID=$userID AND id=$attackID"); // $attack ID IS PARSED FROM MAIN FLEET CRON
  }
  if ($targetUserID > 1) {
  if ($targetBattlestation["coreHealth"] > 0) {
  if ($destroyedCoreHp == 0) {
    $core = ", while defenders battlestation core has been destroyed.";
  } elseif ($destroyedCoreHp > 0) {
    $core = ", while defenders battlestation core hasn't been destroyed.";
  }
} else {
  $core = ", defenders battlestation core has been already destroyed.";
}
  } else {
    $core = ".";
  }

  $subject = "Battle report";
  $fullMsg = "<h3>Fleets of two commanders have engaged over these coordinates: $TargetCoordinates[map]:$TargetCoordinates[x]:$TargetCoordinates[y]</h3>";
  $fullMsg .= "<span>Attacker being: ".$userNick["ingameNick"]." and defender being: ".$targetNick["ingameNick"]."</span>";
  $i = 1;
  while ($i <= $m) {
    $fullMsg .= "<div class='round".$i."'><h3 class='summary'>Round ".$i." summary</h3>".$getMessage["report".$i.""]."</div>";
    $i++;
  }
  $fullMsg .= "<p class='outcome'><b>Battle outcome: ".$battleStatus.$core."</b></p>";
  checkMsgs($conn, $to2);
  if ($targetUserID > 1) {
  checkMsgs($conn, $to1);
  sendMsg($conn, $to1, $from, $currentTime, $subject, $fullMsg, $token);
  }
  sendMsg($conn, $to2, $from, $currentTime, $subject, $fullMsg, $token);
} else {
  "";
}
 ?>
