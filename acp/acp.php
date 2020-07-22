<?php
session_start();
if (isset($_SESSION["sidAdmin"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sidAdmin"];
  $sql = mysqli_query($conn, "SELECT sessionIDExpire FROM admins WHERE sessionID='$session'");
  if (mysqli_num_rows($sql) > 0) {
    $sessionExpire = mysqli_fetch_assoc($sql);
    if ($sessionExpire["sessionIDExpire"] > date("U")) {
      "";
    } else {
      echo $session;
      exit();
    }
  } else {
    session_unset();
    session_destroy();
    header("location: acplogin.php");
    exit();
  }
} else {
  header("location: acplogin.php");
  exit();
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="acpmanager.js"></script>
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>
  <main>
    <form class="acp_main" action="acphandler.php" method="post">
    Command: <input type="text" name="console" class="console" placeholder="Enter console command">
      <button type="submit" name="button_acp_submit">Execute</button>
    </form>

    <div class="result">

    </div>
   <br>
    <form action="deleteUser.php" method="post">
      <input type="number" name="idDelete" class="idDelete" id="delete" placeholder="ID of an user!"><br><br>
      <button type="submit" name="submitDelete">Delete the user!</button>
    </form>

    <div class="itemids_box">
      <?php
      $arrayItems = [1=>"credits", 2=> "hyperid", 3=> "natium", 4=> "hornet", 5=> "spacefire", 6=> "starhawk", 7=> "peacemaker", 8=> "centurion", 9=> "nathalis", 10=> "light laser module", 11=> "heavy laser module", 12=> "rocket module", 13=> "shield module", 14=> "research dmg", 15=> "research hp", 16=> "research shield", 17=> "research speed", 18=> "fuel" ];
      $index = 1;
      foreach ($arrayItems as $item) {
        echo "ID: ".$index."-".$item."<br>";
        $index++;
      }
       ?>
    </div>
  </main>
  </body>
</html>
