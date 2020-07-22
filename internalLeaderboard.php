<?php
  require "./include/accessSecurity.php";
?>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Spacesabres||Leaderboard</title>
  <?php include "include/font.php"; ?>
  <link rel="stylesheet" href="../css/stylegame.css">
  <link rel="stylesheet" href="../css/styleLeaderboard.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="../js/leaderboard.js"></script>
  <script src="../js/countDownPage.js" charset="utf-8"></script>
  <script src="../js/gameinfo.js" charset="utf-8"></script>
  <script src="../js/backgroundmanager.js" charset="utf-8"></script>
  <script src="../js/search-player.js"></script>
</head>
  <body>

    <header>
      <?php require "include/header.php"; ?>

</header>
<main>
    <section class="searchPopup">

  </section>

  <section class="leaderboard_container">
    <div class="top_container_base_title">
      <h2 align="center">Leaderboard</h2>
    </div>
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
