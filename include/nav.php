<div class="header_nav"><span>Server time: 22:35:00<span></div>
<div class="header_btns">
<a href="internalStart.php">Home</a>
<a href="internalBase.php">Battlestation</a>
<a href="internalShipyard.php">Galactic shop</a>
<ul>
  <a href="internalShipyard.php">Shipyard</a>
  <a href="internalBaseShop.php">Modules</a>
  <a href="internalWeapons.php">Weapons</a>
  <a href="internalEnergy.php">Shields</a>
  <a href="internalEngines.php">Engines</a>
</ul>

<a href="internalResearch.php">Research</a>
<?php
  if ($userInfo["userclan"] == "none") {
    echo '<a href="internalAlliances.php">Alliance</a>';
  } else {
    echo '<a href="internalAlliance.php">Alliance</a>';
  }
 ?>
<a href="internalBriefing.php">Briefing</a>
<a href="internalDisplayfleet.php" style="width: 100%;">Docks</a>
<a href="internalFleets.php">Fleet Overview</a>
<ul class="menu_extend_fleets">
  <a href="internalFleets.php">Movement Overview</a>
  <a href="internalBattlelobbies.php">Commander's HUD</a>
  <a href="internalDisplaystats.php">Battle statistics</a>
</ul>

<a href="internalCompanyDefense.php">Company Defense</a>
<a href="internalGalaxy.php">Galactic Map</a>
<a href="internalMissions.php">Missions</a>
<a href="internalProfile.php">Profile</a>
<a href="internalStore.php">Store</a>
<a href="#" id="search">Player search</a>
</div>