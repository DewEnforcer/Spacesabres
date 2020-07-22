<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, claimed, loginBonusDay, userclan, ingameNick FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $claimedLogin = mysqli_fetch_assoc($sql);
    $userInfo = $claimedLogin;
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
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="css/game.css">
    <link rel="stylesheet" href="css/start.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="../js/news.js"></script>
    <script src="../js/dailyLoginHandler.js"></script>
    <title>SpaceSabres||Home</title>
  </head>
  <body>
    <?php
    require './include/nav.php';
     ?>
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
      <?php // handles the daily login window
      if ($claimedLogin["claimed"] == 0) {
        echo '<section class="daily_login">';
        echo '<h2>Daily Login Bonus</h2><hr style="width: 100%;">';
        $sql = mysqli_query($conn, "SELECT * FROM dailybonus WHERE day=$claimedLogin[loginBonusDay]");
        $bonus = mysqli_fetch_assoc($sql);
        echo '<h3>Day '.$bonus["day"].'</h3>';
        echo "<p>Welcome back $userInfo[ingameNick]. Login again tomorrow to recieve even better rewards!</p>";
          echo '<div class="day_bonus_items">';
          echo '<img src="../image/dailybonusicons/item'.$bonus["itemID1"].'.webp"><span>'.$bonus["item1Ammount"].'</span>';
          echo "</div>";
        echo '<button type="button" name="claim_login_reward" class="claim_login_reward">Claim your bonus</button>
        </section>';

      }
       ?>
        <section class="infoContainer">
          <div class="UserInfo">
            <?php
            $sql = mysqli_query($conn, "SELECT rank, leaderboardPos, pageCoordsX, pageCoordsY ,mapLocation FROM userfleet WHERE userID=$show[userID]");
            $rank = mysqli_fetch_assoc($sql);
             ?>
             <?php
             if ($rank["rank"] == 1 ) {
               $rankName = "Ensign";
             } elseif ($rank["rank"] == 2) {
               $rankName = "Basic Lieutenant";
             } elseif ($rank["rank"] == 3) {
               $rankName = "Lieutenant";
             }elseif ($rank["rank"] == 4) {
               $rankName = "Lieutenant Commander";
             }elseif ($rank["rank"] == 5) {
               $rankName = "Commander";
             }elseif ($rank["rank"] == 6) {
               $rankName = "Captain";
             }elseif ($rank["rank"] == 7) {
               $rankName = "Rear Admiral";
             }elseif ($rank["rank"] == 8) {
               $rankName = "Vice Admiral";
             }elseif ($rank["rank"] == 9) {
               $rankName = "Admiral";
             } elseif ($rank["rank"] == 10) {
               $rankName = "Fleet Admiral";
             } elseif ($rank["rank"] == 11) {
               $rankName = "Administrator";
             }
             echo "<div class='wrapper_info'>";
             echo "          <h2>Commanders info</h2>
                       <hr>";
             $sql = mysqli_query($conn, "SELECT * FROM profileimg WHERE userid=$show[userID]");
             $IMG = mysqli_fetch_assoc($sql);
             if ($IMG['status'] == 0) {
               $filename = "../uploads/profile".$show["userID"]."*";

               $fileinfo = glob($filename);
               $fileExt = explode(".", $fileinfo[0]);
               $fileActext = $fileExt[1];

               echo "<div><img class='img_profile' src='../uploads/profile".$show["userID"].".".$fileActext."?".mt_rand()."'>";
             } else {
               echo "<div><img class='img_profile' src='../uploads/profileDef.jpg'>";
             }

             echo '<label>In-game Nickname: '.$show["ingameNick"].'</label></div>';
             echo '<label class="rank">Your rank: '.$rankName. ' <img style="width: 30px;" src="../image/ranks/rank'.$rank["rank"].'.png" class="rankImg"></label>';
             if ($show["userclan"] != "none") {
               $sql = mysqli_query($conn, "SELECT clanName FROM userclans WHERE clanTag='$show[userclan]'");
               $clanName = mysqli_fetch_assoc($sql);
               echo '<label>Your alliance: ['.$show["userclan"].'] '.$clanName["clanName"].'</label>';
             } else {
               echo '<label>Your alliance: Freelancer</label>';
             }
             echo '<label>Your position in the leaderboard: '.$rank["leaderboardPos"].'</label>';
             echo '<label>Coordinates of your station: '.$rank["mapLocation"].':'.$rank["pageCoordsX"].':'.$rank["pageCoordsY"].'</label>';
             ; ?>
          </div>
          <div class="home_leaderboard_container">
            <h2>Leaderboard</h2>
            <hr>
            <table class="home_leaderboard_table">
              <tr>
                <th>Username</th>
                <th>Position</th>
                <th>Points</th>
              </tr>
            <?php
            $i = 1;
            $sql = mysqli_query($conn, "SELECT userID, destroyedPoints FROM userfleet ORDER BY destroyedPoints DESC LIMIT 0,10");
            while ($row = mysqli_fetch_assoc($sql)) {
              $getNick = mysqli_query($conn, "SELECT username FROM users WHERE userID=$row[userID]");
              $nick = mysqli_fetch_assoc($getNick);
              echo "<tr>";
              echo "<td>$nick[username]</td>";
              echo "<td>$i</td>";
              echo "<td>$row[destroyedPoints]</td>";
              echo "</tr>";
              $i++;
            }
             ?>
             </table>
             <a href="internalLeaderboard.php">See more</a>
          </div>
          <div class="home_news_container">
            <div class="news_header">
              <?php
                $sql = mysqli_query($conn, "SELECT * FROM news ORDER BY idManual ASC");
                $checkNews = mysqli_num_rows($sql);
                $i = 1;
                $r = 0;
                $newsImages = [];
                while ($news = mysqli_fetch_assoc($sql)) {
                  if ($i == 1) {
                    echo "<input type=\"radio\" name=\"news_switch\" class='radio_news' id=\"radio$r\" checked=\"true\">";
                  } else {
                    echo "<input type=\"radio\" name=\"news_switch\" class='radio_news' id=\"radio$r\">";
                  }

                  array_push($newsImages, $news["news_img"]);
                  $i++;
                  $r++;
                }
               ?>
            </div>
            <div class="news_image">
              <?php
              if ($checkNews > 0) {
                echo "<div class='img_news_wrapper' style='height: 100%;'><img src=\"../image/news/news".$newsImages[0].".png\"></div>";
              }
               ?>
            </div>
            <div class="news_desc_wrapper">
              <div class="news_description">
                <?php
                if ($checkNews > 0) {
                  $sql = mysqli_query($conn, "SELECT news_desc, news_title FROM news WHERE idManual=1");
                  $newsDesc = mysqli_fetch_assoc($sql);
                  echo "<h2>$newsDesc[news_title]</h2>";
                  echo "<p>$newsDesc[news_desc]</p>";
                }

                 ?>
              </div>
              <div class="news_timer">
                <?php
                if ($checkNews > 0) {
                  $sql = mysqli_query($conn, "SELECT news_time FROM news WHERE idManual=1");
                  $newsDesc = mysqli_fetch_assoc($sql);
                  if ($newsDesc["news_time"] > 0) {
                    echo "Time left: ";
                  }
                }
                 ?>
              </div>
            </div>
          </div>
        </section>
    </main>
    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
