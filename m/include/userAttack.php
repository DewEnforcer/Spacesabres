<?php
// count DMG hornet
$damageAttacker = [];
$totalDamage = [];
$TargetDestroyedShips = [];
$TargetPostBattleFleet = [];
$baseDamage = [];
$totalBaseDamage = [];
$a = 0; //what ship is attacking
foreach ($AttackerDamage as $shipTypeAttack) {
  for ($i=0; $i < 6; $i++) { //lets apply damage to every enemy type ship
    $damageAttacker[$i][$a] = $shipTypeAttack*$rapidFireArray[$a][$i];
  }
  $a++;
}
// count total dmg to all ship types
$totalDamage[0] = array_sum($damageAttacker[0]);
$totalDamage[1] = array_sum($damageAttacker[1]);
$totalDamage[2] = array_sum($damageAttacker[2]);
$totalDamage[3] = array_sum($damageAttacker[3]);
$totalDamage[4] = array_sum($damageAttacker[4]);
$totalDamage[5] = array_sum($damageAttacker[5]);

// destroy enemy ships now, those that survive are left in the array for next round;
$d = 0;
foreach ($TargetFleet as $dock) {
  $ti = 0;
  foreach ($dock as $ship) {
    if ($totalDamage[$ship["type"]] < 1) {
      break;
    }
    $rng = rand(1,100);
    $chance = $ship["stats"][4]*100;
    if ($rng <= $chance) { // placeholder of evasion system
      $totalDamage[$ship["type"]] -= $ship["stats"][2]+$ship["stats"][1];
      $ti++;
      continue;
    }
      $shd = $ship["stats"][1] - $totalDamage[$ship["type"]];
      if ($shd < 0) {
        $hp = $shd + $ship["stats"][2];
        if ($hp < 0) {
          array_splice($TargetFleet[$d], $ti, 1); // removes the ship from the array;
          $targetFleetNumber[$ship["type"]]--;
          $totalDamage[$ship["type"]] -= $ship["stats"][2]+$ship["stats"][1]; //if the ship explodes, remove its hp and shd from the total damage for this ship type
          continue;
        } else {
            $TargetFleet[$d][$ti]["stats"][1] = 0;
            $TargetFleet[$d][$ti]["stats"][2] = $hp; //+ protože je to hodnota v mínusu // changes the ship stats according to dmg taken
            $totalDamage[$ship["type"]] = 0; //ship has obviously survived all total damage, therefore it is auto 0
            break;
        }
      } else {
        $TargetFleet[$d][$ti]["stats"][1] = $ship["stats"][1] - $totalDamage[$ship["type"]];// changes the ship stats according to dmg taken
        $totalDamage[$ship["type"]] = 0; //ship has obviously survived all total damage, therefore it is auto 0
        break;
      }

  }
  $d++;
}
// ↓ base damage count
if ($targetUserID > 1) {
  $t = 0;
  foreach ($totalDamage as $dmgType) {
    if ($dmgType != 0) {
      for ($i=0; $i < 11; $i++) {
        $baseDamage[$i][$t] = $dmgType;
      }
    }
    $t++;
  }

  for ($i=0; $i < 11; $i++) {
    $totalBaseDamage[$i] = array_sum($baseDamage[$i]);
  }

  $a = 1;
  $shdIncrease = 1;
  while ($a <= 10) {
    if ($targetBattlestation["slot".$a.""] == 4) {
      $shdIncrease += 0.3;
    }
    $a++;
  }

  if ($totalBaseDamage[0] - ((($baseParameters[5][1]*($TargetResearch["researchShd"]*0.1+1))*$formationLightStatsBuffShd[$targetFormationLight][1])*$shdIncrease) > 0) {
    $totalBaseDamage[0] -= ((($baseParameters[5][1]*($TargetResearch["researchShd"]*0.1+1))*$formationLightStatsBuffShd[$targetFormationLight][1])*$shdIncrease);
    $destroyedCoreShd = 0;
    if ($totalBaseDamage[0] - ($baseParameters[5][2]*($TargetResearch["researchHp"]*0.1+1)) > 0) {
      $destroyedCoreHp = 0;
    }else {
      $destroyedCoreHp = ($baseParameters[5][2]*($TargetResearch["researchHp"]*0.1+1)) - $totalBaseDamage[0];
    }
  } else {
    $destroyedCoreShd = ((($baseParameters[5][1]*($TargetResearch["researchShd"]*0.1+1))*$formationLightStatsBuffShd[$targetFormationLight][1])*$shdIncrease) - $totalBaseDamage[0];
    $destroyedCoreHp = $baseParameters[5][2]*($TargetResearch["researchHp"]*0.1+1);
  }
  $b = 1;
  while ($b <= 10) {
    $baseSlot[$b] = $targetBattlestation["slot".$b.""];
    $b++;
  }
  $p = 1;
  while ($p <= 10) {
    $PostBattle[$p] = (($baseParameters[$baseSlot[$p]][1]*($TargetResearch["researchShd"]*0.1+1))+($baseParameters[$baseSlot[$p]][2]*($TargetResearch["researchHp"]*0.1+1)))-$totalBaseDamage[$p];
    $p++;
  }

  $i = 1;
  while ($i <= 10) {
    if ($PostBattle[$i] <= 0) {
      $PostBattle[$i] = 0;
    } else {
      if ($targetBattlestation["slot".$i.""] == 0) {
        $PostBattle[$i] = 0;
      }
      elseif ($targetBattlestation["slot".$i.""] == 1) {
        $PostBattle[$i] = 1;
      } elseif ($targetBattlestation["slot".$i.""] == 2) {
        $PostBattle[$i] = 2;
      }
      elseif ($targetBattlestation["slot".$i.""] == 3) {
        $PostBattle[$i] = 3;
      }
      elseif ($targetBattlestation["slot".$i.""] == 4) {
        $PostBattle[$i] = 4;
      }
    }
    $i++;
  }

} else {
  "";
}
 ?>
