<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Free browser MMO space game , explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <?php include "include/font.php"; ?>
      <link rel="stylesheet" href="../css/stylegame.css">
      <link rel="stylesheet" href="../css/styleStart.css">
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
    <script src="../js/news.js"></script>
    <script src="../js/backgroundmanager.js"></script>
    <script src="../js/navigation.js"></script>
    <script src="../js/fleetbarNav.js"></script>
    <script src="../js/dailyLoginHandler.js"></script>
    <title>SpaceSabres||Home</title>
  </head>
  <body>
        <?php require "include/header.php"; ?>
      <main>
      <img src="./image/graphics/navbarbtn.png" id="btn_navbar" alt="navbar_btn">
      <div class="navigation">
        <?php require "include/nav.php" ?>
      </div>
      <div id="btn_fleetbar">
      </div>
      <div class="fleet_bar">
      </div>
      <section class="searchPopup">
      </section>
      <?php // handles the daily login window
      if ($userInfo["claimed"] == 0) {
        echo '<section class="daily_login">';
        echo '<h2>Daily Login Bonus</h2>';
        $sql = mysqli_query($conn, "SELECT * FROM dailybonus");
        echo '<div class="day_bonus_wrapper">';
        while ($row = mysqli_fetch_assoc($sql)) {
          if ($row["day"] == $userInfo["loginBonusDay"]) {
          echo '<div class="day_bonus" id='.$row["day"].' style="border: 2px solid white;"><h2>Day '.$row["day"].'</h2><div class="day_bonus_items">';
          } else {
          echo '<div class="day_bonus" id='.$row["day"].'><h2>Day '.$row["day"].'</h2><div class="day_bonus_items">';
          }
          echo '<img src="image/dailybonusicons/item'.$row["itemID1"].'.png" class="img_daily" id="'.$row["itemID1"].'"><span>'.number_format($row["item1Ammount"], '0', '.',' ').'</span>';
          echo "</div>";
          echo "</div>";
        }
        echo "</div>";
        echo '<button type="button" name="claim_login_reward" class="claim_login_reward">Claim your bonus</button>
        </section>';

      }
       ?>
        <section class="infoContainer">
          <div class="UserInfo">
          <h2>Commanders info</h2>
          <hr>
            <?php
            $sql = mysqli_query($conn, "SELECT rank, leaderboardPos, pageCoordsX, pageCoordsY ,mapLocation FROM userfleet WHERE userID=$userInfo[userID]");
            $rank = mysqli_fetch_assoc($sql);
            $rankArr = ["Unknown", "Ensign", "Basic Lieutenant", "Lieutenant", "Lieutenant Commander", "Commander", "Captain", "Rear Admiral", "Vice Admiral", "Admiral", "Fleet Admiral", "Administrator"];
            $rankName = $rankArr[$rank["rank"]];
             echo "<div class='wrapper_info'>";
             $sql = mysqli_query($conn, "SELECT * FROM profileimg WHERE userid=$userInfo[userID]");
             $IMG = mysqli_fetch_assoc($sql);
             if ($IMG['status'] == 0) {
               $filename = "uploads/profile".$userInfo["userID"]."*";

               $fileinfo = glob($filename);
               $fileExt = explode(".", $fileinfo[0]);
               $fileActext = $fileExt[1];

               echo "<div><img class='img_profile' src='./uploads/profile".$userInfo["userID"].".".$fileActext."?".mt_rand()."'></div>";
             } else {
               echo "<div><img class='img_profile' src='./uploads/profileDef.jpg'></div>";
             }

             echo '<label>In-game Nickname: '.$userInfo["ingameNick"].'</label></div>';
             echo '<label class="rank">Your rank: '.$rankName. ' <img style="width: 30px;" src="../image/ranks/rank'.$rank["rank"].'.png" class="rankImg"></label>';
             if ($userInfo["userclan"] != "none") {
               $sql = mysqli_query($conn, "SELECT clanName FROM userclans WHERE clanTag='$userInfo[userclan]'");
               $clanName = mysqli_fetch_assoc($sql);
               echo '<label>Your alliance: ['.$userInfo["userclan"].'] '.$clanName["clanName"].'</label>';
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
