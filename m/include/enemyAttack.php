<?php
//count DMG Base
$damageDefender = [];
$totalDamage = [];
$AttackerDestroyedShips = [];
$AttackerPostBattleFleet = [];
if ($targetUserID > 1) {
$dmgBase = ($baseParameters[$targetBattlestation["slot1"]][0] +$baseParameters[$targetBattlestation["slot2"]][0]+ $baseParameters[$targetBattlestation["slot3"]][0]+ $baseParameters[$targetBattlestation["slot4"]][0]+ $baseParameters[$targetBattlestation["slot5"]][0]+ $baseParameters[$targetBattlestation["slot6"]][0]+  $baseParameters[$targetBattlestation["slot7"]][0] + $baseParameters[$targetBattlestation["slot8"]][0] +  $baseParameters[$targetBattlestation["slot9"]][0] + $baseParameters[$targetBattlestation["slot10"]][0])*($TargetResearch["researchDmg"]*0.1+1);


} else {
  $dmgBase = 0;
}
//counts all ship dmg
$a = 0; //what ship is attacking
foreach ($TargetDamage as $shipTypeAttack) {
  for ($i=0; $i < 6; $i++) { //lets apply damage to every enemy type ship
    $damageDefender[$i][$a] = $shipTypeAttack*$rapidFireArray[$a][$i];
  }
  $a++;
}
// count total dmg to all ship types
$totalDamage[0] = array_sum($damageDefender[0])+$dmgBase;
$totalDamage[1] = array_sum($damageDefender[1])+$dmgBase;
$totalDamage[2] = array_sum($damageDefender[2])+$dmgBase;
$totalDamage[3] = array_sum($damageDefender[3])+$dmgBase;
$totalDamage[4] = array_sum($damageDefender[4])+$dmgBase;
$totalDamage[5] = array_sum($damageDefender[5])+$dmgBase;
$i = 0;
foreach ($AttackerFleet as $ship) {
  $rng = rand(1,100);
  $chance = $ship["stats"][4]*100;
  if ($rng <= $chance) { // placeholder of evasion system
    $totalDamage[$ship["type"]] -= $ship["stats"][2]+$ship["stats"][1];
    $i++;
    continue;
  }
  $shd =  $ship["stats"][1] - $totalDamage[$ship["type"]];
  if ($shd < 0) {
    $hp = $shd + $ship["stats"][2];
    if ($hp < 1) {
      array_splice($AttackerFleet, $i, 1); // removes the ship from the array;
      $attackerFleetnumber[$ship["type"]]--;// decreases the number of ships for battlemsg
      $totalDamage[$ship["type"]] -= $ship["stats"][2]+$ship["stats"][1]; //if the ship explodes, remove its hp and shd from the total damage for this ship type
      continue;
    } else {
        $AttackerFleet[$i]["stats"][1] = 0;
        $AttackerFleet[$i]["stats"][2] = $hp; //+ protože je to hodnota v mínusu // changes the ship stats according to dmg taken
        $totalDamage[$ship["type"]] = 0; //ship has obviously survived all total damage, therefore it is auto 0
        break;
    }
  } else {
    $AttackerFleet[$i]["stats"][1] = $ship["stats"][1] - $totalDamage[$ship["type"]];// changes the ship stats according to dmg taken
    $totalDamage[$ship["type"]] = 0; //ship has obviously survived all total damage, therefore it is auto 0
    break;
  }
}
 ?>
