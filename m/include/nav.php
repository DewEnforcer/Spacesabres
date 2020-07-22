<div class="navbar_box">
    <p class="server_time">Server time: <?php  echo date("G:i:s"); ?></p>
    <hr>
    <nav>
      <ul>
        <a href="internalStart.php">Home Page</a>
        <a href="internalBase.php">Battlestation Overview</a>
        <a href="internalShipyard.php">Shipyard</a>
        <a href="internalWeapons.php">Weapons</a>
        <a href="internalEnergy.php">Shield generators</a>
        <a href="internalEngines.php">Hyperspace engines</a>
        <a href="internalResearch.php">Research laboratory</a>
        <?php
          if ($userInfo["userclan"] == "none") {
            echo '<a href="internalAlliances.php">Alliance</a>';
          } else {
            echo '<a href="internalAlliance.php">Alliance</a>';
          }
         ?>
         <a href="internalDisplayfleet.php" style="width: 100%;">Docks overview</a>
        <a href="internalBriefing.php">Briefing</a>
            <a href="internalFleets.php">Movement Overview</a>
            <a href="internalBattlelobbies.php">Commander's HUD</a>
            <a href="internalDisplaystats.php">Battle statistics</a>
        <a href="internalCompanyDefense.php">Company Defense</a>
        <a href="internalGalaxy.php">Galaxy</a>
        <a href="internalMissions.php">Missions</a>
        <a href="internalProfile.php">Your profile</a>
        <?php
        $sql = mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$userInfo[userID] AND viewed=0");
        if (mysqli_num_rows($sql) > 0) {
          echo '<a href="internalInbox.php" class="inbox_navbar"><img src="../image/graphics/newmsg.png" class="newmsg">Inbox</a>';
        } else {
          echo '<a href="internalInbox.php" class="inbox_navbar">Inbox</a>';
        }
         ?>
        <a href="internalStore.php">Store</a>
        <a href="#" id="search">Player search</a>
        <a href="include/logout.inc.php">Logout</a>
      </ul>
    </nav>
</div>
