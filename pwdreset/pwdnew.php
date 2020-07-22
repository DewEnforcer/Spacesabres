<?php
session_start();
if (isset($_GET["id"]) && isset($_GET["token"]) && empty($_GET["id"]) === FALSE && empty($_GET["token"]) === FALSE) {
  $selector = $_GET["id"];
  $validator = $_GET["token"];

    if (ctype_xdigit($selector) !== FALSE && ctype_xdigit($validator) !== false) {
      "";
    } else {
      header("location: ../index.php");
      exit();
    }
} else {
  header("location: ../index.php");
  exit();
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Free browser MMO space game , explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
    <meta name=viewport content="width=device-width, initial-scale=1">
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
      background-image: url("../image/bg/supportbg.jpg");
      background-position: top center;
      background-repeat: no-repeat;
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      height: 100%;
    }
    .section-default  {
      width: 40%;
      border: 2px solid white;
      border-radius: 8px;
      background-color: rgb(50,50,50,0.9);
      color: white;
      margin-top: 15%;
      text-align: center;
      min-height: 150px;
    }
    .section_default h1 {
      text-align: center;
      align-self: center;
    }
    .change_pwd_box {
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      width: 100%
    }
    .change_pwd_box div {
      margin: 3px 0;
      width: 100%;
      display: flex;
      flex-flow: row nowrap;
      justify-content: center;
    }
    .change_pwd_box label {
      padding: 3px;
    }
    .change_pwd_box input {
      width: 25%;
      padding: 3px;
      background-color: rgb(40,40,40);
      color: white;
      border: 2px solid black;
      transition: .2s ease-in-out;
    }
    .change_pwd_box input:focus {
      border: 2px solid white;
    }
    .change_pwd_box button {
      background-color: rgb(60,60,60);
      border: 2px solid white;
      color: white;
      transition: 0.25s ease-in-out;
      padding: 3px;
      margin: 5px 0;
      cursor: pointer;
    }
    .change_pwd_box button:hover {
      background-color: rgb(40,40,40);
    }
    .result {
      margin: 5px 0;
    }
    </style>
    <title>Spacesabres||Password change</title>
  </head>
  <body>
      <a href="../index.php"><img src="../image/graphics/logoNew.png" alt="logo spacesabres"></a>
        <section class="section-default">

          <form class="change_pwd_box" action="pwdnewhandler.php" method="post">
              <h1>Choose a new password</h1>
              <input type="hidden" name="id" value="<?php  echo $selector; ?>">
              <input type="hidden" name="token" value="<?php  echo $validator; ?>">
              <div><label>Enter your new password: </label><input type="password" name="newPwd" placeholder="Enter your new password!"></div>
              <div><label>Repeat the new password: </label><input type="password" name="repeatPwd" placeholder="Repeat your new password!"></div>
              <button type="submit" name="reset-password-submit">Reset password!</button>
            </form>
              <?php
              if (isset($_SESSION["newpwdreset"])) {
                echo "<p class='result'>".$_SESSION["newpwdreset"]."</p>";
                unset($_SESSION["newpwdreset"]);
              }
               ?>
        </section>
  </body>
</html>
