<?php session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
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
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Weapons</title>
  <?php include "include/font.php"; ?>
  <link rel="stylesheet" href="css/game.css">
  <link rel="stylesheet" href="css/shop.css">
  <?php
  require 'include/head.php';
   ?>
  <script src="./js/shop.js"></script>
</head>
  <body>
    <?php
    include "./include/nav.php";
     ?>
    <header>
    <?php require "include/header.php"; ?>
    <?php
    function handleObjectives($conn, $userID, $index) {
      $sql = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$userID");
      $currentQuest = mysqli_fetch_assoc($sql);

      $unserializeUser = unserialize($currentQuest["userObjectives"]);

      $sql = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$currentQuest[currentQuest]");
      $objective = mysqli_fetch_assoc($sql);
      $unserializeTemplate = unserialize($objective["objectives"]);

      if ($unserializeTemplate[$index]>0) {
        $unserializeUser[$index] += 1;
        $serialize = serialize($unserializeUser);
        $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
        return;
      } else {
        return;
      }
    }

    handleObjectives($conn, $show["userID"], 12);

     ?>
</header>
<main>
  <section class="gameinfo">
    <div class="game_leader">

    </div>
    <div class="special_thanks">

    </div>
    <div class="forum_info">

    </div>
  </section>
  <section class="shop_main_wrapper">
    <h2>Weapons</h2>
    <hr>
    <div class="items_wrapper">
      <img src="../image/shopImg/item19.png" alt="laser_low" class="item" id="19">
     <img src="../image/shopImg/item20.png" alt="laser_heavy" class="item" id="20">
     <img src="../image/shopImg/item21.png" alt="laser_beam" class="item" id="21">
     <img src="../image/shopImg/item26.png" alt="rocket" class="item" id="26">
    </div>
  </section>




</main>

<footer>
  <?php require "include/footer.php"; ?>
</footer>

  </body>
</html>
