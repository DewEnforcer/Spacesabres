<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create new password</title>
  </head>
  <body>
    <main>
      <div class="wrapper-main">
        <section class="section-default">

          <?php

            $selector = $_GET["selector"];
            $validator = $_GET["validator"];

            if (empty($selector) || empty($validator)) {
              echo "We couldn't validate your request!";
            } else {
              if (ctype_xdigit($selector) !== FALSE && ctype_xdigit($validator) !== false) {
                ?>
                <form action="include/reset-password.inc.php" method="post">
                  <input type="hidden" name="selector" value="<?php  echo $selector; ?>">
                  <input type="hidden" name="selector" value="<?php  echo $validator; ?>">
                  <input type="password" name="newPwd" placeholder="Enter your new password!">
                  <input type="password" name="repeatPwd" placeholder="Repeat your new password!">
                  <button type="submit" name="reset-password-submit">Reset password!</button>
                </form>
                <?php
              }
            }

           ?>


        </section>
      </div>
    </main>
  </body>
</html>
