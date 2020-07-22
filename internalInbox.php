<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="../css/stylegame.css">
    <link rel="stylesheet" href="../css/styleInbox.css">
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
    <script src="../js/inboxHandler.js"></script>
    <script src="../js/gameinfo.js" charset="utf-8"></script>
    <script src="../js/backgroundmanager.js" charset="utf-8"></script>
    <script src="../js/search-player.js"></script>
    <title>SpaceSabres||Inbox</title>
  </head>
  <body>
      <header>
        <?php 
        require "include/header.php"; 
        handleObjectives($conn, $show["userID"], 15);
         ?>
  </header>

    <main>
      <section class="searchPopup">

      </section>
      <section class="inbox_container">
          <h2>Inbox</h2>
        <section class="inbox_container_header">
          <button type="button" name="inbox" class="inbox">Inbox</button>
          <button type="button" name="sentmsg" class="sentmsg">Sent messages</button>
          <button type="button" name="friends" class="friends">Friend list</button>
          <button type="button" name="blockedusers" class="blockedusers">Blocked users</button>
        </section>
        <section class="inbox_container_main">
          <?php
          $sql = mysqli_query($conn, "SELECT clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
          $userClan = mysqli_fetch_assoc($sql);
          $sql=mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$show[userID] ORDER BY sentTime DESC");
          $check = mysqli_num_rows($sql);

           ?>
          <div class="inbox_container_main_header">
            <div class="selected_msgs_action">
              <div class="check_all_wrapper">
                <input type="checkbox" name="checkall" class="check_all">
                <span>All</span>
              </div>
              <button type="button" name="button" class="btn_mng_msg" value="delete">Delete</button>
              <button type="button" name="button" class="btn_mng_msg" value="read">As read</button>
            </div>
            <div class="show_filter_wrapper">
              <span>Show:</span>
              <select class="select_show_msg">
                <option value="all">All</option>
                <option value="0">Unread</option>
                <option value="1">Read</option>
                <option value="user">Commanders</option>
                <option value="ally">Alliance</option>
                <option value="system">System</option>
              </select>
            </div>
            <button type="button" name="button" class="btn_newmsg">New message</button>
          </div>
          <div class="inbox_msg_wrapper">
          <?php
          if ($check > 0){
            $index = 0;
          while ($getMsg = mysqli_fetch_assoc($sql)) {
            $num_length = strlen((string)$getMsg["fromUserID"]);
            if ($getMsg["fromUserID"] == 1 ) {
              $system = "System";
            }
            if ($num_length == 10) {
              $fromUser = "Alliance";
              $identify = "ally";
            } else {
              $sql1 = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$getMsg[fromUserID]");
              $from = mysqli_fetch_assoc($sql1);
            }
            if($getMsg["viewed"] == 0) {
              $read = 0;
              $color = "msgNotRead";
            } elseif ($getMsg["viewed"] == 1) {
              $color = "";
              $read = 1;
            }
            if ($getMsg["fromUserID"] == 1 ) {
                $fromUser = "System";
                $identify = "system";
              } else {
                $fromUser = $from["ingameNick"];
                $identify = "user";
              }
            echo "<div from='$identify' read='$read' class='msg_wrapper msg_wrapper_$index'><div class='msg_inbox_left'><input type='checkbox' token='$getMsg[token]' name='check$index' value='$index' id='checkbox_$index' class='checkbox'></div><div from='$identify' class='msg msg$index $color' id=".$index.">
            <p class='from' id='from".$index."' id=".$index.">From: $fromUser";
            echo "</p>
            <p class='subject' id=".$index.">Subject: <span class='subject_$index'>".$getMsg["subject"]."</span></p>
            <p class='date' id=".$index.">Sent on: ".date("Y-m-d G:i:s", $getMsg["sentTime"])."</p>
            <input type='hidden' id='randstr".$index."' value='$getMsg[token]'></div>";
            echo "</div>";
            $index++;
          }
        } else {
            echo "<p class='nomsg'>You have no messages!</p>";
          }
          ?>
        </div>
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
