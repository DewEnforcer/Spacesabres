<?php
// prepare reward booking
$rewardsCredits = [5000,8000,10000,25000,30000,100000];
$rewardsHyperids = [0,0,5000,25000,27000,60000];
$rewardsNatiums = [0,0,0,0,0,1000];

// start counting points for users
$userPoints = [];
$targetPoints = [];
$lostPointsArr = [2,3,5,10,15,25];
for ($i=0; $i < 6; $i++) {
  $userPoints[$i] = $lostShipsTarget[$i]*$lostPointsArr[$i];
  $targetPoints[$i] = $lostShipsAttacker[$i] * $lostPointsArr[$i];
}


$lostFleetPointsUser = [];
$lostFleetPointsTarget = [];
$fleetPointsArray = [2,3,5,10,15,25];
$i = 0;
while ($i <= 5) {
  $lostFleetPointsUser[$i] = $lostShipsAttacker[$i] * $fleetPointsArray[$i];
  $lostFleetPointsTarget[$i] = $lostShipsTarget[$i] * $fleetPointsArray[$i];
  $i++;
}
$lostFleetPointsUserTotal = $attackerFleetPoints["fleetPoints"] - array_sum($lostFleetPointsUser);
$lostFleetPointsTargetTotal = $targetFleet["fleetPoints"]-array_sum($lostFleetPointsTarget);

if ($lostFleetPointsUserTotal < 0) {
  $lostFleetPointsUserTotal = 0;
}
if ($lostFleetPointsTargetTotal < 0) {
  $lostFleetPointsTargetTotal = 0;
}

countRewards($conn, "rewardsAttacker", $lostShipsTarget[0], $lostShipsTarget[1], $lostShipsTarget[2], $lostShipsTarget[3], $lostShipsTarget[4], $lostShipsTarget[5], $rewardsHyperids, $rewardsNatiums, $rewardsCredits, $userID); //attacker
if ($targetUserID > 100000) {
countRewards($conn, "rewardsDefender", $lostShipsAttacker[0], $lostShipsAttacker[1], $lostShipsAttacker[2], $lostShipsAttacker[3], $lostShipsAttacker[4], $lostShipsAttacker[5], $rewardsHyperids, $rewardsNatiums, $rewardsCredits, $targetUserID); //defender
}
$totalPointsAttacker = array_sum($userPoints);
$sql = mysqli_query($conn, "SELECT destroyedPoints, destroyedShips, destroyedHornets, destroyedSpacefires, destroyedStarhawks, destroyedPeacemakers, destroyedCenturions, destroyedNathalis, battlesWon, battlesLost, battlesDraw, battlesTotal FROM userfleet WHERE userID=$userID");
$attackerPoints = mysqli_fetch_assoc($sql);

$sql = mysqli_query($conn, "SELECT destroyedPoints, destroyedShips, destroyedHornets, destroyedSpacefires, destroyedStarhawks, destroyedPeacemakers, destroyedCenturions, destroyedNathalis, battlesWon, battlesLost, battlesDraw, battlesTotal FROM userfleet WHERE userID=$targetUserID");
$defenderPoints = mysqli_fetch_assoc($sql);

$newAttackerPoints = $totalPointsAttacker+$attackerPoints["destroyedPoints"];
$newAttackerTotalShips = $attackerPoints["destroyedShips"]+ $countAllLossTarget;

$newAttackerDestroyedHornets = $attackerPoints["destroyedHornets"] + $lostShipsTarget[0];
$newAttackerDestroyedSpacefires = $attackerPoints["destroyedSpacefires"] + $lostShipsTarget[1];
$newAttackerDestroyedStarhawks = $attackerPoints["destroyedStarhawks"] + $lostShipsTarget[2];
$newAttackerDestroyedPeacemakers = $attackerPoints["destroyedPeacemakers"] + $lostShipsTarget[3];
$newAttackerDestroyedCenturions = $attackerPoints["destroyedCenturions"] + $lostShipsTarget[4];
$newAttackerDestroyedNathalis = $attackerPoints["destroyedNathalis"] + $lostShipsTarget[5];
// for attacker
if ($victory == "attacker") {
  $battleStatus = $attackerPoints["battlesWon"] + 1;
  $battleQuery = "battlesWon";
  $battleStatus1 = $defenderPoints["battlesWon"] + 1;
  $battleQuery1 = "battlesWon";
} elseif ($victory == "defender") {
  $battleStatus = $attackerPoints["battlesLost"] + 1;
  $battleQuery = "battlesLost";
  $battleStatus1 = $defenderPoints["battlesWon"] + 1;
  $battleQuery1 = "battlesWon";
} else {
  $battleStatus = $attackerPoints["battlesDraw"] + 1;
  $battleQuery = "battlesDraw";
  $battleStatus1 = $defenderPoints["battlesDraw"] + 1;
  $battleQuery1 = "battlesDraw";
}

$newAttackerBattlesTotal = $attackerPoints["battlesTotal"] + 1;

$totalPointsTarget = array_sum($targetPoints);


$newDefenderPoints = $totalPointsTarget+$defenderPoints["destroyedPoints"];
$newDefenderTotalShips = $defenderPoints["destroyedShips"]+ $countAllLossUser;

$newDefenderDestroyedHornets = $defenderPoints["destroyedHornets"] + $lostShipsAttacker[0];
$newDefenderDestroyedSpacefires = $defenderPoints["destroyedSpacefires"] + $lostShipsAttacker[1];
$newDefenderDestroyedStarhawks = $defenderPoints["destroyedStarhawks"] + $lostShipsAttacker[2];
$newDefenderDestroyedPeacemakers = $defenderPoints["destroyedPeacemakers"] + $lostShipsAttacker[3];
$newDefenderDestroyedCenturions = $defenderPoints["destroyedCenturions"] + $lostShipsAttacker[4];
$newDefenderDestroyedNathalis = $defenderPoints["destroyedNathalis"] + $lostShipsAttacker[5];

$newDefenderBattlesTotal = $defenderPoints["battlesTotal"] + 1;



$sql = mysqli_query($conn, "UPDATE userfleet SET destroyedPoints=$newAttackerPoints, fleetPoints=$lostFleetPointsUserTotal, destroyedShips=$newAttackerTotalShips, destroyedHornets=$newAttackerDestroyedHornets, destroyedSpacefires=$newAttackerDestroyedSpacefires, destroyedStarhawks=$newAttackerDestroyedStarhawks, destroyedPeacemakers=$newAttackerDestroyedPeacemakers, destroyedCenturions=$newAttackerDestroyedCenturions, destroyedNathalis=$newAttackerDestroyedNathalis WHERE userID=$userID");
$sql = mysqli_query($conn, "UPDATE userfleet SET destroyedPoints=$newDefenderPoints, fleetPoints=$lostFleetPointsTargetTotal, destroyedShips=$newDefenderTotalShips, destroyedHornets=$newDefenderDestroyedHornets, destroyedSpacefires=$newDefenderDestroyedSpacefires, destroyedStarhawks=$newDefenderDestroyedStarhawks, destroyedPeacemakers=$newDefenderDestroyedPeacemakers, destroyedCenturions=$newDefenderDestroyedCenturions, destroyedNathalis=$newDefenderDestroyedNathalis WHERE userID=$targetUserID");
if ($round == 3 || $victory == "attacker" || $victory == "defender" || $victory == "drawDestruction") {
  $sql = mysqli_query($conn, "UPDATE userfleet SET $battleQuery=$battleStatus, battlesTotal=$newAttackerBattlesTotal WHERE userID=$userID");
  $sql = mysqli_query($conn, "UPDATE userfleet SET $battleQuery1=$battleStatus1, battlesTotal=$newDefenderBattlesTotal WHERE userID=$targetUserID");
}
 ?>
