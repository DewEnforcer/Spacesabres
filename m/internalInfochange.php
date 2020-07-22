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
    <link rel="shortcut icon" href="/image/graphics/favicon.ico">
    <?php require "include/font.php" ?>
    <link rel="stylesheet" href="css/game.css">
    <?php
    require 'include/head.php';
     ?>
    <title>SpaceSabres||New account info</title>
    <style>
    main {
      display: flex;
      justify-content: center;
    }
    .main_change_box {
      width: 100vw;
      border: 2px solid grey;
      border-radius: 5px;
      background-color: rgb(50,50,50,0.9);
      color: rgb(240,240,240);
      text-align: center;
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      justify-content: space-evenly;
      margin: 10px 0;
    }
    .form_nick_change, .form_pwd_change {
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      width: 100%;
    }
    .form_pwd_change input {
      width: 60%;
    }
    .main_change_box input, .main_change_box button, .main_change_box a {
      background-color: rgb(65,65,65);
      color: white;
      font-family: "Kanit", sans-serif;
      border: 2px solid black;
      padding: 3px;
      width: 60%;
      margin: 5px;
    }
    .main_change_box button {
      transition: 0.25s ease-in-out;
      cursor: pointer;
    }
    .main_change_box button:hover {
      background-color: rgb(45,45,45);
    }
    .main_change_box a {
      text-decoration: none;
      transition: 0.25s ease-in-out;
      margin-top: 25px;
    }
    .main_change_box a:hover {
      background-color: rgb(45,45,45);
    }
    </style>
  </head>
  <body>
    <?php
    require 'include/nav.php';
     ?>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "usernameTaken") {
          echo ' <div class="popup_result">
              <p>The username is already taken!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "cooldown") {
          echo ' <div class="popup_result">
              <p>You cannot change your username till '.$_GET["time"].'!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"]== "emptyform") {
          echo "You haven´t filled in your new username!";
        }
        }
        if (isset($_GET["success"])) {
          if ($_GET["success"]=="nickchanged") {
            echo ' <div class="popup_result">
                <p>Your username has been successfully changed!</p>
                <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
              </div>';
          }
      }
      if (isset($_SESSION["newpwd"]) && $_SESSION["newpwd"] != "") {
        if ($_SESSION["newpwd"] == "success") {
          echo ' <div class="popup_result">
              <p>We have sent you instructions to your e-mail linked to this account!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_SESSION["newpwd"] == "wrongpwd") {
          echo ' <div class="popup_result">
              <p>You have entered incorrect password!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        $_SESSION["newpwd"] = "";
      }
       ?>

      <section class="main_change_box">
        <h1>Here you can change your account information</h1>
        <form class="form_nick_change" action="include/nickChange.inc.php" method="post">
          <h2>Change your username</h2>
          <label for="username">Enter your new in-game nickname (48 Hours cooldown, you will still have to login with username used in registration)</label>
          <input type="text" name="username" placeholder="Enter your new ingame username...">
          <button type="submit" name="submit-new-nick">Change your username!</button>
        </form>
          <form class="form_pwd_change" action="include/requestPwdChange.inc.php" method="post">
            <h2>Change your password</h2>
            <label for="oldpwd">In order to request password change, enter your current password: </label>
            <input type="password" name="oldpwd" placeholder="Enter your old password...">
            <button type="submit" name="request_pwd_change">Change your Password!</button>
          </form>
        <a href="internalProfile.php">Return to account center</a>
      </section>


    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
