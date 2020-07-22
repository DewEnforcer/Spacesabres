<?php session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
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
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Shipyard</title>
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
    <ul class="shop_navbar">
      <?php
      include "./include/shopNav.php";
       ?>
    </ul>
    <div class="items_wrapper">
      <img src="../image/shopImg/item7.png" alt="low_ls_mod" class="item" id="7">
     <img src="../image/shopImg/item8.png" alt="hev_ls_mod" class="item" id="8">
     <img src="../image/shopImg/item9.png" alt="silo_mod" class="item" id="9">
     <img src="../image/shopImg/item10.png" alt="ms01_mod" class="item" id="10">
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
