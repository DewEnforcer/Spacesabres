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
      <link rel="stylesheet" href="../css/styleAllyIn.css">
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


      <section class="searchPopup">

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
