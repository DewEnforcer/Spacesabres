<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin control panel</title>
  </head>
  <body>
    <h1>Enter your admin info to log into the control panel</h1>
    <form class="login" action="acplogin.inc.php" method="post">
      <input type="text" name="username" placeholder="Enter your username"><br>
      <input type="text" name="e-mail" placeholder="Enter your e-mail"><br>
      <input type="password" name="password" placeholder="Enter your password"><br>
      <input type="text" name="key" placeholder="Enter your key"><br>
      <button type="submit" name="button_submit_login">Login!</button>
    </form>
  </body>
</html>
