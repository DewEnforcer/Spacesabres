<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="description" content="">
      <meta name=viewport content="width=device-width, initial-scale=1">
      <?php include "include/font.php"; ?>
      <link rel="stylesheet" href="../css/stylegame.css">
      <link rel="stylesheet" href="../css/styleDisplayfleet.css">
      <?php require "include/scripts.php" ?>
      <script src="../js/dock.js"></script>
      <script>
        <?php 
        $sql = mysqli_query($conn, "SELECT fleet FROM userfleet WHERE userID=$userInfo[userID]");
        $fleet = mysqli_fetch_assoc($sql)["fleet"];
         ?>
        const shipAm = <?php echo $fleet; ?>;
      </script>
      <title>SpaceSabres||Docks</title>
    </head>
  <body>
      <?php require "include/header.php"; ?>
    <main>
      <?php require "include/bars.php" ?>
      <section class="dock_main_box">
        <h1 id="dock_title">Dock overview</h1>
        <div class="dock_ship_list">

        </div>
        <div class="dock_ship_details">

        </div>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
  </body>
</html>
