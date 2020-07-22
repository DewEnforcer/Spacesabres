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
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Leaderboard</title>
  <link rel="stylesheet" href="./css/game.css">
  <link rel="stylesheet" href="./css/leaderboard.css">
  <?php require "include/head.php" ?>
  <script src="../js/leaderboard.js"></script>
</head>
  <body>
    <?php require 'include/nav.php'; ?>
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

  <section class="leaderboard_container">
      <h2>Leaderboard</h2>
  <table class="leaderboardAll">
    <tr>
      <th>Position</th>
      <th>Nickname</th>
      <th>Destruction Points</th>
      <th>Fleet points</th>
    </tr>

  <?php
  $session = $_SESSION["sid"];
  $getID = mysqli_query($conn, "SELECT userID, ingameNick from users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);

  $sql = mysqli_query($conn, "SELECT leaderboardPos, destroyedPoints, fleetPoints FROM userfleet WHERE userID=$ID[userID]");
  $getUserPos = mysqli_fetch_assoc($sql);
  $page = 50;
  $getAll = mysqli_query($conn, "SELECT leaderboardPos, userID, destroyedPoints, fleetPoints FROM userfleet WHERE userID>100000 ORDER BY leaderboardPos ASC LIMIT 50");
  while ($All = mysqli_fetch_assoc($getAll)) {
    $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$All[userID]");
    $idGet = mysqli_fetch_assoc($sql);


    echo '<tr>
    <td>'.$All["leaderboardPos"].'</td>
    <td>'.$idGet["ingameNick"].'</td>
    <td>'.number_format($All["destroyedPoints"], '0', '.',' ').'</td>
    <td>'.number_format($All["fleetPoints"], '0', '.',' ').'</td>
    </tr>';
  }
   ?>
   </table>
   <table class="leaderboardUser">
     <tr>
       <th>Your position</th>
       <th>Nickname</th>
       <th>Your points of destruction</th>
       <th>Fleet points</th>
     </tr>
     <tr>
     <?php
     echo '

     <td>'.$getUserPos["leaderboardPos"].'</td>
     <td>'.$ID["ingameNick"].'</td>
     <td>'.number_format($getUserPos["destroyedPoints"], '0', '.',' ').'</td>
     <td>'.number_format($getUserPos["fleetPoints"], '0', '.',' ').'</td>
     ';

      ?>
      </tr>
   </table>
   <div class="leaderboard_pages">
     <?php
     $getAll = mysqli_query($conn, "SELECT COUNT(userID) as NumberOfIDS FROM userfleet WHERE userID>100000");
     $numberOfusers = mysqli_fetch_assoc($getAll);
     $numberPages = ceil($numberOfusers["NumberOfIDS"]/50);
     $page = 1;
     while ($page <= $numberPages) {
       echo '<button type="button" name="page" class="button_leaderboard" id="'.$page.'" >1</button>';
       $page++;
     }
      ?>
   </div>
    </section>
</main>

<footer>
<?php require "include/footer.php"; ?>
</footer>

  </body>
</html>
