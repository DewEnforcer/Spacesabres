<li><a href="internalStart.php">Home</a></li>
<li><a href="internalBase.php">Battlestation</a></li>
<li style="min-width: 100px; display: flex; align-items: center; justify-content: center;"><a href="internalShipyard.php">Galactic shop</a>
<ul>
  <li><a href="internalShipyard.php">Shipyard</a></li>
  <li><a href="internalBaseShop.php">Modules</a></li>
  <li><a href="internalWeapons.php">Weapons</a></li>
  <li><a href="internalEnergy.php">Shields</a></li>
  <li><a href="internalEngines.php">Engines</a></li>
</ul>
</li>
<li><a href="internalResearch.php">Research</a></li>
<?php
  if ($show["userclan"] == "none") {
    echo '<li><a href="internalAlliances.php">Alliance</a></li>';
  } else {
    echo '<li><a href="internalAlliance.php">Alliance</a></li>';
  }
 ?>
<li><a href="internalBriefing.php">Briefing</a></li>
<li><a href="internalDisplayfleet.php" style="width: 100%;">Docks</a></li>
<li><a href="internalFleets.php">Fleet Overview</a>
<ul class="menu_extend_fleets">
  <li><a href="internalFleets.php">Movement Overview</a></li>
  <li><a href="internalBattlelobbies.php">Commander's HUD</a></li>
  <li><a href="internalDisplaystats.php">Battle statistics</a></li>
</ul>
</li>
<li><a href="internalCompanyDefense.php">Company Defense</a></li>
<li><a href="internalGalaxy.php">Galactic Map</a></li>
<li><a href="internalMissions.php">Missions</a></li>
<li><a href="internalProfile.php">Profile</a></li>
<li><a href="internalStore.php">Store</a></li>
<li><a href="#" id="search">Player search</a></li>
