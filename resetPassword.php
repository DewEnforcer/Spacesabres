<?php
session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Free browser MMO space game , explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>Space Sabres||Reset password</title>
    <style>
    * {
      margin: 0;
      padding: 0;
    }
    html {
      height: 100%;
      font-family: sans-serif;
    }
    body {
      background-color: black;
      background-image: url("./image/bg/supportbg.jpg");
      background-position: top center;
      background-repeat: no-repeat;
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
    }
    .forgot_pwd_box {
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      width: 60%;
      border: 2px solid white;
      border-radius: 8px;
      background-color: rgb(50,50,50,0.9);
      color: white;
    }
    .reset_pwd_form {
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      width: 30%;
      margin: 5px 0;
    }
    .reset_pwd_form button, .reset_pwd_form input, .reset_pwd_form div {
      width: 100%;
      margin: 3px 0;
      padding: 3px;
      font-family: sans-serif;
    }
    .reset_pwd_form button, .reset_pwd_form input {
      background-color: rgb(60,60,60);
      border: 2px solid white;
      color: white;
      transition: 0.25s ease-in-out;
    }
    .reset_pwd_form button {
      cursor: pointer;
    }
    .reset_pwd_form button:hover {
      background-color: rgb(40,40,40);
    }
    .reset_pwd_form div {
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
    }
    .reset_pwd_form button {
      width: 50%;
    }
    a {
      text-decoration: none;
      background-color: rgb(60,60,60);
      border: 2px solid white;
      color: white;
      transition: 0.25s ease-in-out;
      padding: 3px 5px;
      font-size: 14px;
      margin-bottom: 15px;
    }
    a:hover {
      background-color: rgb(40,40,40);
    }
    .reset_result {
      margin-bottom: 10px;
    }
    </style>
  </head>
  <body>
    <a href="../index.php"><img src="./image/graphics/logoNew.png" alt="logo spacesabres"></a>
    <section class="forgot_pwd_box">
      <h2>Password reset / forgotten password</h2>
      <form class="reset_pwd_form" action="pwdreset/pwdrequest.php" method="post">
           <div><label>Enter an e-mail adress linked to your account: </label><input type="text" name="email" placeholder="Enter your e-mail adress..."></div>
           <button type="submit" name="reset-request-submit">Request a password reset</button>
      </form>
    <a href="./index.php">Back to the game</a>
    <?php
    if (isset($_SESSION["pwdreset"])) {
      if ($_SESSION["pwdreset"] == "invalidemail") {
        echo "<p class='reset_result' style='color: red'>No account has been found with this e-mail adress linked to it!</p>";
        unset($_SESSION["pwdreset"]);
      } elseif ($_SESSION["pwdreset"] == "success") {
        echo "<p class='reset_result' style='color: green'>We have sent you an email with password reset confirmation!</p>";
        unset($_SESSION["pwdreset"]);
      }
    }
     ?>
    </section>
</body>
</html>
