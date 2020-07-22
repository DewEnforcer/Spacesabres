<?php
session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Spacesabres</title>
  <meta name="description" content="Free browser MMO space game , explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
  <meta name=viewport content="width=device-width, initial-scale=1">
  <?php include "./include/font.php"; ?>
  <link rel="stylesheet" href="css/loginpage.css">
  <!-- Cookie Consent by https://www.PrivacyPolicies.com -->
  <script type="text/javascript" src="//www.privacypolicies.com/public/cookie-consent/3.0.0/cookie-consent.js"></script>
  <script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
  cookieconsent.run({"notice_banner_type":"headline","consent_type":"express","palette":"dark","language":"en","website_name":"Spacesabres"});
  });
  </script>
  <noscript><a href="https://www.PrivacyPolicies.com/cookie-consent/">Cookie Consent by PrivacyPolicies.com</a></noscript>
  <!-- End Cookie Consent -->
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143336464-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-143336464-1');
</script>
  <script>
  $(document).ready(function(){
  $(".button_confirm_result").click(function(hideResult){
    $(".popup_result").html("");
    $(".popup_result").css("display", "none");
  });
});
  </script>
  <script src="../js/gameinfo.js" charset="utf-8"></script>
  <meta property="og:title" content="Spacesabres">
  <meta property="og:image" content="https://spacesabres.com/image/graphics/ogimg.png">
  <meta property="og:url" content="https://www.spacesabres.com/"/>
  <meta property="og:description" content="Spacesabres is a free, space MMO browser game where you can explore the galaxy under the rhein of the company , build massive fleets and fight againts the deadly threats!">
  <meta property="og:image:width" content="200"/>
  <meta property="og:image:height" content="200"/>
  <meta property="og:image:alt" content="Spacesabres logo"/>
</head>
  <body>

      <header>
        <img src="../image/graphics/logo.png" alt="logo">
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
          <section class="login_form">
            <h2>Log-In</h2>
             <form action="../include/login.inc.php" method="post">
                  <input type="text" name="mailuid" placeholder="Username">
                  <input type="password" name="pwd" placeholder="Password">
                  <input type="hidden" name="device" value="mobile">
                  <button type="submit" name="login-submit">Login</button>
                  <a href="resetPassword.php">Forgot your password?</a>
                </form>

              </section>
              <?php
              if (isset($_GET["error"]) ) {
                if ($_GET["error"] == "emptyfields") {
                  echo '<div class="popup_result">
                      <h2 style="color:rgb(237,41,57);">Error!</h2>
                      <p>You haven´t entered all the required info!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                else if($_GET["error"] == "invalidmailuid"){
                  echo '<div class="popup_result">
                      <h2 style="color:rgb(237,41,57);">Error!</h2>
                      <p>Invalid e-mail!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                else if($_GET["error"] == "invalidmail"){
                  echo '<div class="popup_result">
                      <h2 style="color:rgb(237,41,57);">Error!</h2>
                      <p>Invalid e-mail!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                else if($_GET["error"] == "passwordcheck"){
                  echo '<div class="popup_result">
                      <h2 style="color:rgb(237,41,57);">Error!</h2>
                      <p>Your passwords don´t match up!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                elseif ($_GET["error"] == "usertaken") {
                echo '<div class="popup_result">
                    <h2 style="color:rgb(237,41,57);">Error!</h2>
                    <p>The username is already taken!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
                elseif ($_GET["error"] == "11") {
                echo '<div class="popup_result">
                    <h2 style="color:rgb(237,41,57);">Error!</h2>
                    <p>Unfortunately an error has occured:(, try again!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
                elseif ($_GET["error"] == "wrongpwd") {
                echo '<div class="popup_result">
                    <h2 style="color:rgb(237,41,57);">Error!</h2>
                    <p>No account with the username and password combination you entered has been found!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
                elseif ($_GET["error"] == "tos") {
                echo '<div class="popup_result">
                    <h2 style="color:rgb(237,41,57);">Error!</h2>
                    <p>You have not checked that you agree with our terms of service!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
            } elseif (isset($_GET["success"])) {
              if ($_GET["success"] == "1") {
              echo '<div class="popup_result">
                  <p>You have been successfully registered! Activate your account by going to your email and following the instructions that we sent you there!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
            }
            } elseif (isset($_SESSION["activateacc"])) {
              if ($_SESSION["activateacc"] == "link") {
              echo '<div class="popup_result">
              <h2 style="color:rgb(237,41,57);">Error!</h2>
                  <p>An error has occured, you have entered incorrect link!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            } elseif ($_SESSION["activateacc"] == "sql") {
              echo '<div class="popup_result">
              <h2 style="color:rgb(237,41,57);">Error!</h2>
                  <p>Unfortunately an error has occured , please try again, or contact our support (errorID = 11)</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            } elseif ($_SESSION["activateacc"] == "tokentimeerror") {
              echo '<div class="popup_result">
              <h2 style="color:rgb(237,41,57);">Error!</h2>
                  <p>Unfortunately , your account activation link has already expired , please try registering again!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            } elseif ($_SESSION["activateacc"] == "success") {
              echo '<div class="popup_result">
              <h2 style="color:rgb(80,220,100);">Success!</h2>
                  <p>You have successfully activated your account , now you can login and start playing!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            }
          } elseif (isset($_SESSION["newpwdreset"]) && $_SESSION["newpwdreset"] != "") {
            if ($_SESSION["newpwdreset"] == "success") {
              echo '<div class="popup_result">
              <h2 style="color:rgb(80,220,100);">Success!</h2>
                  <p>Your password has been successfully changed, use it to log into your account and play!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                $_SESSION["newpwdreset"] = "";
            }
          }
              ?>

          <section class="signup">
            <h2>Sign-up</h2>
            <form action="../include/signup.inc.php" method="post">
              <input type="text" name="Username" placeholder="Username">
              <input type="text" name="Mail" placeholder="E-mail">
              <input type="password" name="password" placeholder="Password">
              <input type="password" name="password-repeat" placeholder="Repeat password">
              <div><input type="checkbox" name="rule_agreement" value="true"><p align="center">I confirm that i have read and accept <a href="tos.php" target="_blank">Terms of service</a> and <a href="#" target="_blank">Data Privacy Policy</a>*</p></div>
              <div><input type="checkbox" name="newsletter_agreement" value="true"><p align="center">I wan't to recieve newsletters from the game</p></div>
              <button type="submit" name="signup-submit">Signup</button>
              </form>
          </section>

          <section class="about">
            <h1>about the game</h1>
            <p>Spacesabres is a free to play browser game. If you have ever wanted to be an admiral or have your own galactic fleet , this game is perfect for you! Build your fleet and engage in strategical battles againts other players , or the feared xamons. Help the mining company by completing galactic missions and recieve great in-game rewards that will help you grow your fleet even more! So what are you waiting for? Register now and become the greatest admiral in the galaxy of spacesabres!</p>
          </section>
          <?php if (isset($_SESSION["activateacc"])) {
            echo $_SESSION["activateacc"];
          } ?>
      </main>

      <footer>
        <nav>
          <ul class="info">
            <li><a href="#" class="authors">Credits</a></li>
            <li><a href="https://forum.spacesabres.com" class="info1" target="_blank">Forum</a></li>
            <li><a href="./support/index.php" target="_blank">Support</a></li>
            <li><a href="tos.php" class="info1" target="_blank">Terms of service</a></li>
          </ul>
          <ul class="social">
          <li><a href="https://www.facebook.com/spacesabresofficial/" target="_blank"><img src="../image/graphics/fbicon.png"></a></li>
          <li><a href="https://www.youtube.com/channel/UCIQZ5s1Cvkc2-mCaBqI0AkQ" target="_blank" ><img src="../image/graphics/yticon.png"></a></li>
          <li><a href="https://discord.gg/RuHydpd" target="_blank"><img src="../image/graphics/discordicon.png"></a></li>
          </ul>
        </nav>

      </footer>
