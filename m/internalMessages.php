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
    <link rel="stylesheet" href="css/messages.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="../js/msgHandler.js"></script>
    <title>SpaceSabres||Messages</title>
  </head>
  <body>
    <?php
    require 'include/nav.php';
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
      <?php
      if (isset($_GET["username"])) {
        $value = $_GET["username"];
      } else {
        $value="";
      }
     ?>
      <section class="message_container">
        <h2>Send a message</h2>
        <form class="message" action="internalMessages.php" method="post">
          <div class="msg_head_wrapper">
          <div class="msg_to_wrapper">
         <label for="user">To:</label>
         <input type="text" name="user" id="user"placeholder="Enter name of the receiver" value="<?php echo $value ?>">
        </div>
        <div class="msg_subject_wrapper">
         <label for="subject">Subject:</label>
         <input type="text" name="subject" id="subject"placeholder="Enter your subject">  <br><br>
         </div>
       </div>
         <label class="label_msg" for="msg">Your message goes here:</label><br>
         <textarea name="msg" id="msg" rows="8" cols="80" maxlength="600"></textarea>
        <input type="hidden" name="userID" id="userID"value="<?php echo $show["userID"];?>"> <br>
        <button type="submit" name="submit-msg" class="submit_msg">Submit your message!</button>
        </form>
        <div id="message-result">

        </div>
      </section>
    </main>

    <footer>
  <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
