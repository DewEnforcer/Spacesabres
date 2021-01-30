<?php
  require "./include/accessSecurity.php";
  $sql = mysqli_query($conn, "SELECT dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
  $docks = mysqli_fetch_assoc($sql)["dockAmount"];
 ?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Shipyard</title>
  <?php include "include/font.php"; ?>
  <link rel="stylesheet" href="../css/stylegame.css">
  <link rel="stylesheet" href="../css/styleShop.css">
  <script>
    var docks = <?php echo $docks ?>;
  </script>
  <?php require "include/scripts.php" ?>
  <script src="../js/shop.js"></script>
</head>
  <body>
    <?php 
    require "include/header.php";
    handleObjectives($conn, $userInfo["userID"], 12);
    ?>
<main>
  <?php require "include/bars.php" ?>
  <section class="shop_main_wrapper">
    <ul class="shop_navbar">
      <?php
      include "./include/shopNav.php";
       ?>
    </ul>
    <div class="items_wrapper">
      <img src="../image/shopImg/item27.png" alt="dock" class="item" id="27">
      <img src="../image/shopImg/item28.png" alt="fuel" class="item" id="28">
      <img src="../image/shopImg/ship1.png" alt="hornet" class="item" id="1">
     <img src="../image/shopImg/ship2.png" alt="spacefire" class="item" id="2">
     <img src="../image/shopImg/ship3.png" alt="starhawk" class="item" id="3">
     <img src="../image/shopImg/ship4.png" alt="peacemaker" class="item" id="4">
     <img src="../image/shopImg/ship5.png" alt="centurion" class="item" id="5">
     <img src="../image/shopImg/ship6.png" alt="na-thalis" class="item" id="6">
    </div>
    <div class="items_description">

    </div>
  </section>




</main>

<footer>
  <?php require "include/footer.php"; ?>
</footer>

  </body>
</html>
