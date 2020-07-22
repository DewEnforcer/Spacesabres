<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
    "";
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
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="css/game.css">
    <link rel="stylesheet" href="css/alliancestart.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="../js/alliance.js"></script>

    <title>SpaceSabres||Alliance</title>
  </head>
  <body>
    <?php
    require 'include/nav.php';
     ?>
    <div class="opacity_box">

    </div>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <?php
      require '../include/allianceResults.php';
       ?>

      <section class="searchPopup">

      </section>
      <section class="gameinfo">
        <div class="game_leader">

        </div>
        <div class="special_thanks">

        </div>
        <div class="forum_info">

        </div>
      </section>

      <section class="ally_main_box">
        <h2>Interplanetary alliances</h2>
        <div class="ally_wrapper_box">
          <div class="ally_navbar">
            <ul class="ally_nav">
              <?php
                if ($userInfo["userclan"] == "none")  {
                  echo "<a href=\"internalAlliances.php\">Find alliance</a>";
                  echo "<a href=\"internalJoinally.php\">Join alliance</a>";
                  echo "<a href=\"internalCreateally.php\">Create alliance</a>";
                } else {
                  echo "<a href=\"internalAlliance.php\">Alliance</a>";
                  echo "<a href=\"internalAllianceMembers.php\">Alliance members</a>";
                  echo "<a href=\"internalAllianceMsg.php\">Send alliance message</a>";
                  echo "<a href=\"internalAllianceMsg.php\">Alliance diplomacy</a>";
                }
               ?>
            </ul>
          </div>
          <div class="ally_join">
            <h2>Create alliance</h2>
            <span>Note: Creating an alliance will cost you 750 000 Credits!</span>
            <?php
            if ($userInfo["userclan"] == "none") {
              echo '<form class="join_ally_form " action="./include/allianceHandler.php" method="post">
                            <div style="display: flex; flex-flow: row nowrap;  width: 100%; margin: 10px 0; justify-content: space-evenly;"><div style=" display: flex; flex-flow: column nowrap; width:48%; margin: 0 1%;"><label style="margin-right: 5px;">Enter Alliance name: </label><input class="ally_name_create" type="text" name="ally_name_create"></div><div style=" display: flex; flex-flow: column nowrap; width: 48%; margin: 0 1%;"><label style="margin-right: 5px;">Enter Alliance tag: </label><input class="ally_tag_create" type="text" name="ally_tag_create"></div></div>
                            <label>Alliance description: </label>
                            <textarea name="ally_create_desc" class="app_text" rows="8" cols="80" maxlength="300"></textarea>
                            <button type="submit" name="btn_create_ally">Create alliance</button>
                          </form>';
            } else {
              echo "<p>In order to create alliance, you have to leave your current one!</p>";
            }
             ?>
          </div>
      </section>

    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
