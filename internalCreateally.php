<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
      <link rel="stylesheet" href="../css/stylegame.css">
      <link rel="stylesheet" href="../css/styleAlly.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-143336464-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-143336464-1');
  </script>
    <script src="../js/search-player.js"></script>
    <script src="../js/gameinfo.js"></script>
    <script src="../js/backgroundmanager.js"></script>
    <script src="../js/alliance.js"></script>
    <title>SpaceSabres||Alliance</title>
  </head>
  <body>
    <div class="opacity_box">

    </div>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <?php
      require './include/allianceResults.php';
       ?>

      <section class="searchPopup">

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
                            <div style="display: flex; flex-flow: row nowrap; height: 20%; width: 85%; margin: 10px 0; justify-content: space-evenly;"><div style=" display: flex; flex-flow: column nowrap; width: 30%;"><label style="margin-right: 5px;">Enter Alliance name: </label><input class="ally_name_create" type="text" name="ally_name_create"></div><div style=" display: flex; flex-flow: column nowrap; width: 30%;"><label style="margin-right: 5px;">Enter Alliance tag: </label><input class="ally_tag_create" type="text" name="ally_tag_create"></div></div>
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
