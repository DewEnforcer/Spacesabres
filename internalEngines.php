<?php
  require "./include/accessSecurity.php";
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Generators</title>
  <?php include "include/font.php"; ?>
  <link rel="stylesheet" href="../css/stylegame.css">
  <link rel="stylesheet" href="../css/styleShop.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="../js/shop.js"></script>
  <script src="../js/gameinfo.js" charset="utf-8"></script>
  <script src="../js/countDownPage.js" charset="utf-8"></script>
  <script src="../js/backgroundmanager.js" charset="utf-8"></script>
  <script src="../js/search-player.js"></script>
</head>
  <body>
    <header>
    <?php 
    require "include/header.php"; 
    handleObjectives($conn, $show["userID"], 12);
     ?>
</header>
<main>
  <section class="shop_main_wrapper">
    <ul class="shop_navbar">
      <?php
      include "./include/shopNav.php";
       ?>
    </ul>
    <div class="items_wrapper">
      <img src="../image/shopImg/item22.png" alt="hge_01" class="item" id="22">
      <img src="../image/shopImg/item23.png" alt="hge_02" class="item" id="23">
      <img src="../image/shopImg/item24.png" alt="hge_03" class="item" id="24">
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
