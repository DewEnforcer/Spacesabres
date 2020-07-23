<div class="header_nav"><span class="server_time">Server time: 00:00:00<span></div>
<div class="header_btns">
<a href="internalStart.php">Home</a>
<a href="internalBase.php">Battlestation</a>
<a href="internalShipyard.php">Shipyard</a>
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
<a href="internalFleets.php">Movement Overview</a>
<a href="internalBattlelobbies.php">Commander's HUD</a>
<a href="internalDisplaystats.php">Battle statistics</a>
<a href="internalCompanyDefense.php">Company Defense</a>
<a href="internalGalaxy.php">Galactic Map</a>
<a href="internalMissions.php">Missions</a>
<a href="internalProfile.php">Profile</a>
<a href="internalStore.php">Store</a>
<a href="#" id="search">Player search</a>
</div>