<?php
session_start();
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

$sql = mysqli_query($conn, "SELECT researchDmg, researchShd, researchHp, researchSpeed, researchSubspace FROM userresearch WHERE userID=$userInfo[userID]");
$userRes = mysqli_fetch_assoc($sql);

$resTypeArr = ["researchDmg", "researchHp", "researchShd", "researchSpeed", "researchSubspace"];
$timesArr = [];
$researchArray = ["researchDmg"=>["researchTime"=>1500], "researchHp"=>["researchTime"=>1800], "researchShd"=>["researchTime"=>1300], "researchSpeed"=>["researchTime"=>1000], "researchSubspace"=>["researchTime"=>2400]];
$i = 0;
foreach ($userRes as $resType) {
  if ($resType == 1) {
    $coeficient = 1;
  } elseif ($resType == 2) {
    $coeficient = 2;
  } else {
    $coeficient = $resType * 2;
  }
  $preConvertTime = $researchArray[$resTypeArr[$i]]["researchTime"] * $coeficient;
  array_push($timesArr, countTime($preConvertTime));
  $i++;
}
function countTime ($preConvertTime) {
  if ($preConvertTime >= 86400) {
    $days = floor($preConvertTime / 86400);
    $hours = floor($preConvertTime / 3600);
    $minutes = floor(($preConvertTime / 60) % 60);
    $seconds = $preConvertTime % 60;
    $time = "$days day(s) $hours hour(s) $minutes minute(s) $seconds second(s)";
  }
  elseif ($preConvertTime < 86400 && $preConvertTime > 3599) {
    $hours = floor($preConvertTime / 3600);
    $minutes = floor(($preConvertTime / 60) % 60);
    $seconds = $preConvertTime % 60;
    $time = "$hours hour(s) $minutes minute(s) $seconds second(s)";
  } else {
    $minutes = floor(($preConvertTime / 60) % 60);
    $seconds = $preConvertTime%60;
    $time = "$minutes minute(s) $seconds second(s)";
  }
  return $time;
}

?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Research</title>
  <?php include "include/font.php"; ?>
  <link rel="stylesheet" href="css/game.css">
  <link rel="stylesheet" href="css/shop.css">
  <?php
  require 'include/head.php';
   ?>
   <script>
   var lvls = {
     11: <?php echo $userRes["researchDmg"]; ?>,
     12: <?php echo $userRes["researchHp"]; ?>,
     13: <?php echo $userRes["researchShd"]; ?>,
     14: <?php echo $userRes["researchSpeed"]; ?>,
     15: <?php echo $userRes["researchSubspace"]; ?>
   }
   var time = {
     11: "<?php echo $timesArr[0]; ?>",
     12: "<?php echo $timesArr[1]; ?>",
     13: "<?php echo $timesArr[2]; ?>",
     14: "<?php echo $timesArr[3]; ?>",
     15: "<?php echo $timesArr[4]; ?>"
   }
   </script>
  <script src="../js/researchSelector.js"></script>
  <script src="../js/researchCountdown.js"></script>
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

    handleObjectives($conn, $show["userID"], 13);
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
  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"]=="sql") {
      echo ' <div class="popup_result">
          <p>An error occurred ID#11</p>
          <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
        </div>';
    } elseif($_GET["error"]== "notresources") {
      echo ' <div class="popup_result">
          <p>You don´t have enough resources to start this research!</p>
          <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
        </div>';
    }
    elseif($_GET["error"]== "researchon") {
      echo ' <div class="popup_result">
          <p>You cannot start more researches at one time!</p>
          <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
        </div>';
    }
    elseif($_GET["error"]== "notexist") {
      echo ' <div class="popup_result">
          <p>This item doesn´t exist!</p>
          <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
        </div>';
    }
  }
  if (isset($_GET["success"])) {
    if ($_GET["success"] == "1") {
    if ($_GET["res"] == "1") {
      $res = "Weapon systems research";
    } elseif ($_GET["res"] == "2") {
      $res = "Hull alloy durability research";
    }
    elseif ($_GET["res"] == "3") {
      $res = "Shield energy capacity research";
    }
    elseif ($_GET["res"] == "4") {
      $res = "Fuel efficiency research";
    }
    elseif ($_GET["res"] == "5") {
      $res = "Subspace communication hacking research";
    }
    echo '<div class="popup_result">
        <p>You have successfully started researching '.$res.'</p>
        <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
      </div>';
    }
  }
   ?>
  <section class="searchPopup">

  </section>
  <section class="shop_main_wrapper">
  <h2>Research laboratory</h2>
  <hr>
  <?php
  $sql = mysqli_query($conn, "SELECT currentResearch FROM userresearch WHERE userID=$show[userID]");
  $userRes = mysqli_fetch_assoc($sql);
  if ($userRes["currentResearch"] > 0) {
    if ($userRes["currentResearch"] == 1) {
      $research = "Weapon systems research";
    } elseif ($userRes["currentResearch"] == 2) {
      $research = "Hull alloy durability research";
    }
    elseif ($userRes["currentResearch"] == 3) {
      $research = "Shield energy capacity research";
    }
    elseif ($userRes["currentResearch"] == 4) {
      $research = "Fuel efficiency research";
    }
    elseif ($userRes["currentResearch"] == 5) {
      $research = "Subspace communication hacking research";
    }
    echo '<section class="current_research_info">
      <p class="research_info_item">You are currently researching:<br> '.$research.'</p>
      <p id="research_info_time"></p>
    </section>';
  } else {
    "";
  }
   ?>
   <div class="items_wrapper">
     <img src="../image/shopImg/res11.png" alt="dmg" class="research" id="11">
     <img src="../image/shopImg/res12.png" alt="hp" class="research" id="12">
     <img src="../image/shopImg/res13.png" alt="shd" class="research" id="13">
     <img src="../image/shopImg/res14.png" alt="spd" class="research" id="14">
     <img src="../image/shopImg/res15.png" alt="sbspc" class="research" id="15">
   </div>
</section>
</main>

<footer>
  <?php require "include/footer.php"; ?>
</footer>

  </body>
</html>
