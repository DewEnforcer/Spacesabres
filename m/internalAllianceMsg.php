<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
    if ($userInfo["userclan"] == "none") {
      header("location: ../internalAlliances.php");
      exit();
    }
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
      <link rel="stylesheet" href="css/allyoverview.css">
      <?php
      require 'include/head.php';
       ?>
    <script src="js/alliance.js"></script>
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
        <h2>Interplanetary alliance</h2>
        <div class="ally_wrapper_box">
          <div class="ally_navbar">
            <ul class="ally_nav">
              <a href="internalAlliance.php">Alliance</a>
              <a href="internalAllianceMembers.php">Alliance members</a>
              <a href="internalAllianceMsg.php">Send alliance message</a>
              <a href="internalAllianceDiplo.php">Alliance diplomacy</a>
            </ul>
          </div>
        <div class="ally_overview">
          <h2>Send alliance message</h2>
          <form class="ally_msg_form" action="./include/pm-handler.php" method="post">
            <div style="display: flex; flex-flow: row nowrap;">
              <label style="padding: 4px;">Subject:</label><input style="margin-left: 5px;" type="text" name="subject" placeholder="Subject">
            </div>
            <textarea name="msg" rows="8" cols="80" maxlength="1000" placeholder="Your message goes here.."></textarea>
            <button type="submit" name="btn_submit_allymsg">Send message</button>
          </form>
        </div>
        </div>
      </section>

    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
