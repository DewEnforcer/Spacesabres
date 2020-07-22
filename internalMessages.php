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
    <link rel="stylesheet" href="../css/styleMessages.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="../js/msgHandler.js"></script>
    <script src="../js/countDownPage.js" charset="utf-8"></script>
    <script src="../js/gameinfo.js" charset="utf-8"></script>
    <script src="../js/backgroundmanager.js" charset="utf-8"></script>
    <script src="../js/search-player.js"></script>
    <title>SpaceSabres||Messages</title>
  </head>
  <body>

      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <section class="searchPopup">

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
        <div class="letter_count">

        </div>
        <div id="message-result">

        </div>
      </section>
    </main>

    <footer>
  <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
