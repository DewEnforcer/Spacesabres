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

$sql = mysqli_query($conn, "SELECT dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
$docks = mysqli_fetch_assoc($sql);
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Shipyard</title>
  <?php include "include/font.php"; ?>
  <link rel="stylesheet" href="css/game.css">
  <link rel="stylesheet" href="css/shop.css">
  <?php
  require 'include/head.php';
   ?>
   <script>
     var docks = <?php echo $docks["dockAmount"]; ?>;
   </script>
  <script src="./js/shop.js"></script>
</head>
  <body>
    <?php
    require './include/nav.php';
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

  </section>
  <section class="gameinfo">
    <div class="game_leader">

    </div>
    <div class="special_thanks">

    </div>
    <div class="forum_info">

    </div>
  </section>
  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"]=="sql") {
      echo ' <div class="popup_result">
          <p>An error occurred ID#11</p>
          <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
        </div>';
    } elseif($_GET["error"]== "notenoughresources") {
      echo ' <div class="popup_result">
          <p>You don´t have enough resources to purchase these items!</p>
          <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
        </div>';
    }
  }
  if (isset($_GET["success"])) {
    if ($_GET["success"] == "shipConstructed") {
    echo '<div class="popup_result">
        <p>The items have been successfully purchased!</p>
        <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
      </div>';
    }
  }
   ?>
  <section class="shop_main_wrapper">
      <h2>Shipyard</h2>
      <hr>
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
