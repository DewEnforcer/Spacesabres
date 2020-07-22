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
        <link rel="stylesheet" href="css/profile.css">
        <?php
        require 'include/head.php';
         ?>
    <title>SpaceSabres||Profile</title>
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
      if (isset($_GET["error"])) {
        if ($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "toobig") {
          echo ' <div class="popup_result">
              <p>Your file size is too big to upload!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "sizetoobig") {
          echo ' <div class="popup_result">
              <p>Your file dimensions size is too big! Maximum is 200x200 pixels.</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"] == "uploadfail") {
          echo ' <div class="popup_result">
              <p>The upload has failed.</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"] == "wrongext") {
          echo ' <div class="popup_result">
              <p>You cannot upload files with this extension '.$_GET["ext"].'!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
      if (isset($_GET["success"])) {
        if ($_GET["success"] == 1) {
        echo '<div class="popup_result">
            <p>Your avatar has been successfully deleted!</p>
            <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
          </div>';
        } elseif ($_GET["success"] == "upload") {
          echo '<div class="popup_result">
              <p>The picture has been successfully uploaded!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
       ?>
      <section class="profile_main_container">
          <h2 class="main_title">Your account center</h2>
          <div class="profile_avatar_container">

          <h3 class="title_profilepicture">Your profile picture</h3>
          <?php

          $sid = $_SESSION["sid"];
          $selectID = "SELECT userID, Username FROM users WHERE sessionID=?";
          $stmt = mysqli_stmt_init($conn);
          mysqli_stmt_prepare($stmt, $selectID);
          mysqli_stmt_bind_param($stmt, "s", $sid);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $ResultID = mysqli_fetch_assoc($result);
          $ID = $ResultID["userID"];
              $sqlimg = "SELECT * FROM profileimg where userid = $ID";
              $resultImg = mysqli_query($conn, $sqlimg);
              $IMG = mysqli_fetch_assoc($resultImg);

                    if ($IMG['status'] == 0) {
                      $filename = "../uploads/profile".$ID."*";

                      $fileinfo = glob($filename);
                      $fileExt = explode(".", $fileinfo[0]);
                      $fileActext = $fileExt[1];

                      echo "<img src='../uploads/profile".$ID.".".$fileActext."?".mt_rand()."'>";
                    } else {
                      echo "<img src='../uploads/profileDef.jpg'>";
                    }
                    echo $ID['Username'];


           ?>
        </div>
        <div class="profile_forms">
        <div class="profile_upload_form">
          <h3>Choose your profile picture image</h3>
          <form action="include/imgUpload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="img" placeholder="IMG" >
            <button type="submit" name="submitbutton">Upload</button>
          </form>
        </div>
        <div class="profile_delete_form">
          <h3>Delete your profile picture</h3>
          <form action="include/deleteAvatar.php" method="post">
            <button type="submit" name="deletebutton">Delete</button>
        </form>
        </div>
        </div>

        <div class="profile_user_info">
          <?php
          echo "<h4>Your username: ".$show["ingameNick"]."</h4>";
          echo "<h4>Date of registration (YYYY/MM/DD): ".date('Y-m-d G:i:s', $show["regDate"])."</h4>";
          echo "<h4>Play time: ".floor($show["playTime"]/3600)." Hour(s)</h4>";
          ?>
        </div>
        <div class="userinfo_change_box">
          <a href="internalInfochange.php">Change your account information!</a>
        </div>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
