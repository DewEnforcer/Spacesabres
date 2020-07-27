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
  <link rel="shortcut icon" href="/image/graphics/favicon.ico">
  <?php include "include/font.php"; ?>
  <!-- Cookie Consent by https://www.PrivacyPolicies.com -->
  <script type="text/javascript" src="//www.privacypolicies.com/public/cookie-consent/3.0.0/cookie-consent.js"></script>
  <script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
  cookieconsent.run({"notice_banner_type":"headline","consent_type":"express","palette":"dark","language":"en","website_name":"Spacesabres"});
  });
  </script>
  <noscript><a href="https://www.PrivacyPolicies.com/cookie-consent/">Cookie Consent by PrivacyPolicies.com</a></noscript>
  <!-- End Cookie Consent -->
  <link rel="stylesheet" href="css/style-startpage.css">
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
  <script src="js/gameinfo.js"></script>
  <script src="./js/homepage.js"></script>
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
        <ul class="info">
          <li><a href="#" class="authors">Credits</a></li>
          <li><a href="tos.html" class="info1" target="_blank">TOS</a></li>
          <li><a href="privacypolicy.html" class="info2" target="_blank">Privacy Policy</a></li>
        </ul>
        <div class="header_wrapper">
          <img src="../image/graphics/logo.png" alt="logo">
        </div>
        <button type="login" name="button" class="btn_login">Log-In</button>
      </header>

      <main>
              <?php
              if (isset($_GET["error"]) ) {
                if ($_GET["error"] == "emptyfields") {
                  echo '<div class="popup_result">
                      <p>You haven´t entered all the required info!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                else if($_GET["error"] == "invalidmailuid"){
                  echo '<div class="popup_result">
                      <p>Invalid e-mail!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                else if($_GET["error"] == "invalidmail"){
                  echo '<div class="popup_result">
                      <p>Invalid e-mail!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                else if($_GET["error"] == "passwordcheck"){
                  echo '<div class="popup_result">
                      <p>Your passwords don´t match up!</p>
                      <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                    </div>';
                }
                elseif ($_GET["error"] == "usertaken") {
                echo '<div class="popup_result">
                    <p>The username is already taken!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
                elseif ($_GET["error"] == "11") {
                echo '<div class="popup_result">
                    <p>Unfortunately an error has occured:(, try again!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
                elseif ($_GET["error"] == "wrongpwd") {
                echo '<div class="popup_result">
                    <p>No account with the username and password combination you entered has been found!</p>
                    <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                  </div>';
                }
                elseif ($_GET["error"] == "tos") {
                echo '<div class="popup_result">
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
                  <p>An error has occured, you have entered incorrect link!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            } elseif ($_SESSION["activateacc"] == "sql") {
              echo '<div class="popup_result">
                  <p>Unfortunately an error has occured , please try again, or contact our support (errorID = 11)</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            } elseif ($_SESSION["activateacc"] == "tokentimeerror") {
              echo '<div class="popup_result">
                  <p>Unfortunately , your account activation link has already expired , please try registering again!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            } elseif ($_SESSION["activateacc"] == "success") {
              echo '<div class="popup_result">
                  <p>You have successfully activated your account , now you can login and start playing!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                unset($_SESSION["activateacc"]);
            }
          } elseif (isset($_SESSION["newpwdreset"]) && $_SESSION["newpwdreset"] != "") {
            if ($_SESSION["newpwdreset"] == "success") {
              echo '<div class="popup_result">
                  <p>Your password has been successfully changed, use it to log into your account and play!</p>
                  <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
                </div>';
                $_SESSION["newpwdreset"] = "";
            }
          }
              ?>

            <form action="include/signup.inc.php" class="signup_form" name="signup" method="post">
              <h2>Become a galactic admiral right now!</h2>
              <input type="text" name="Username" placeholder="Username">
              <input type="text" name="Mail" placeholder="E-mail">
              <input type="password" name="password" placeholder="Password">
              <input type="password" name="password-repeat" placeholder="Repeat password">
              <div><input type="checkbox" name="rule_agreement" value="true"><span>I confirm that i have read and accept <a href="#">Terms & Conditions</a> and <a href="#">Data Privacy Policy</a> *</span></div>
              <div><input type="checkbox" name="newsletter_agreement" value="true">I want to recieve newsletters from the game</div>
              <button type="submit" name="signup-submit">Register Now!</button>
              </form>
          <section class="social_wrapper">
            <div class="box_about">
              <img src="../image/graphics/gameInfo.jpg" alt="Game info" class="img_gameinfo">
              <span>Find out more about the game!</span>
            </div>
            <div class="box_trailer">
              <a href="https://www.youtube.com/watch?v=uQBZWvzuS30" target="_blank"><img src="../image/graphics/trailerHome.jpg" alt="Trailer" style="width: 100%; height:100%;" class="img_trailer"></a>
              <span>Launch trailer</span>
            </div>
            <div class="box_gallery">
              <img src="../image/graphics/homeGalery.jpg" alt="Gallery" class="img_gallery">
              <span>Game gallery</span>
            </div>
          </section>
          <?php if (isset($_SESSION["activateacc"])) {
            echo $_SESSION["activateacc"];
          } ?>
      </main>

      <footer>
        <div class="footer_text">
          <p>&copy;2019 Spacesabres. All rights reserved. All trademarks are the property of their respective owners. For all legal matters, please contact — spacesabres.legal@spacesabres.com .</p>
        </div>
        <ul class="social">
          <li><a href="https://www.facebook.com/spacesabresofficial/" target="_blank" bis_skin_checked="1"><img src="../image/graphics/fbicon.png"></a></li>
          <li><a href="https://twitter.com/SpacesabresGame" target="_blank"><img src="../image/graphics/twittericon.png"></a></li>
          <li><a href="https://www.youtube.com/channel/UCIQZ5s1Cvkc2-mCaBqI0AkQ" target="_blank" bis_skin_checked="1"><img src="../image/graphics/yticon.png"></a></li>
          <li><a href="https://discord.gg/RuHydpd" target="_blank" bis_skin_checked="1"><img src="../image/graphics/discordicon.png"></a></li>
          </ul>
      </footer>
