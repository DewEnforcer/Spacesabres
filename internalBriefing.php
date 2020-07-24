<?php
  require "./include/accessSecurity.php";
  $sql = mysqli_query($conn, "SELECT fleet FROM userfleet WHERE userID=$userInfo[userID]");
  $fleet = mysqli_fetch_assoc($sql)["fleet"];
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="../css/stylegame.css">
    <link rel="stylesheet" href="../css/styleBriefing.css">
    <?php require "include/scripts.php" ?>
    <script src="../js/briefingComponents.js"></script>
    <script src="../js/components/briefingUI.js"></script>
    <script src="../js/briefing.js"></script>
    <script>
      const realFleet = <?php echo $fleet; ?>;
      const targetCoords = {
        x: undefined,
        y: undefined,
        map: undefined
      }
      const basePosition = {
        x: 0,
        y: 0,
        map: 5
      }
      const userFuel = <?php echo 50000; ?>;
    </script>
    <title>SpaceSabres||Briefing</title>
  </head>
  <body>
        <?php require "include/header.php"; ?>
    <main>
      <?php
      require "include/bars.php";
      ?>
    <section class="main_briefing">
      <div class="header_box">
        <h1>Fleet departure controls</h1>
      </div>
      <div class="body_box">
        <div class="menu_box"></div>
        <img id="btn_open_menu" src="./image/graphics/navbarbtn.png" alt="open_menu">
        <canvas id="briefing_display"></canvas>
      </div>
    </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
