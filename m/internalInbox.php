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
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="css/game.css">
    <link rel="stylesheet" href="css/inbox.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="js/inboxHandler.js"></script>
    <title>SpaceSabres||Inbox</title>
  </head>
  <body>
    <?php
    require 'include/nav.php';
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

        handleObjectives($conn, $show["userID"], 15);
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
      <section class="searchPopup">

      </section>
      <?php
      $sql = mysqli_query($conn, "SELECT clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
      $userClan = mysqli_fetch_assoc($sql);
      $sql=mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$show[userID] ORDER BY sentTime DESC");
      $check = mysqli_num_rows($sql);

       ?>
      <section class="inbox_container">
          <h2>Inbox</h2>
        <section class="inbox_container_header">
          <button type="button" name="inbox" class="inbox">Inbox</button>
          <button type="button" class="sendmsg">Send message</button>
          <button type="button" name="sentmsg" class="sentmsg">Outbox</button>
          <button type="button" name="friends" class="friends">Friendlist</button>
          <button type="button" name="blockedusers" class="blockedusers">Blacklist</button>
        </section>
        <?php
        if ($check > 0) {
          echo '<div class="allMsgs">
            <button name="deleteall" class="button-all" id="deleteAll" href="#">Delete all messages</button>
            <button name="markall" class="button-all button_mark_all" id="markAll" href="#">Mark all messages as seen</button>
          </div>';
        } else {
          "";
        }

         ?>
        <section class="inbox_container_main">
          <?php
          if ($check > 0){
            $index = 1;
          while ($getMsg = mysqli_fetch_assoc($sql)) {
            $num_length = strlen((string)$getMsg["fromUserID"]);
            if ($getMsg["fromUserID"] == 1 ) {
              $system = "System";
            }
            if ($num_length == 10) {
              $from["ingameNick"] = "Alliance";
            } else {
              $sql1 = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$getMsg[fromUserID]");
              $from = mysqli_fetch_assoc($sql1);
            }
            if($getMsg["viewed"] == 0) {
              $color = "orange";
            } elseif ($getMsg["viewed"] == 1) {
              $color = "grey";
            }

            echo "<div class='msg_wrapper'><div class='msg_inbox_left'><img width='20px' src='../image/graphics/deletemsg.png' class='image' alt='msgdelete' id='image".$index."'></div><div class='msg' id=".$index." style='background-color:$color'>
            <p class='from' id='from".$index."' id=".$index.">From: ";
            if ($getMsg["fromUserID"] == 1 ) {
                $system = "System"; echo $system;
              } else {
                echo $from["ingameNick"];
              }
              echo "</p>
            <p class='subject' id=".$index.">Subject: ".$getMsg["subject"]."</p>
            <p class='date' id=".$index.">Sent on: ".date("Y-m-d G:i:s", $getMsg["sentTime"])."</p>
            <input type='hidden' id='randstr".$index."' value='$getMsg[token]'</a></div>";
            echo "</div>";
            $index++;
          }
        } else {
            echo "<p class='nomsg'>You have no messages!</p>";
          }
          ?>
        </section>

     </section>

     <div id="result" style="display:none;">

     </div>
    </main>

    <footer>
      <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
