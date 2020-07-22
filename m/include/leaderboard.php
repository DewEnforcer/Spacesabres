<?php
session_start();
if (isset($_POST["page"]) && isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $startPage = 1;
  $page = $_POST["page"];
  if ($page > 1) {
    $begging = 1;
    $limit = 50;
    $start = ($limit * ($page-1))+$begging;
    $border = $limit * $page;
    $getNewPage = "SELECT * FROM userfleet WHERE userID ORDER BY destroyedPoints DESC LIMIT ?,?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $getNewPage);
    mysqli_stmt_bind_param($stmt, "ii", $start, $border);
    mysqli_stmt_execute($stmt);
    $getresult = mysqli_stmt_get_result($stmt);
    if ($getresult !== FALSE) {
      echo '<tr>
        <th align="left">Position</th>
        <th align="left">Nickname</th>
        <th align="left">Destruction Points</th>
      </tr>';
      while ($ResultData = mysqli_fetch_assoc($getresult)) {
        $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$ResultData[userID]");
        $idGet = mysqli_fetch_assoc($sql);
        echo '<tr>
        <td>'.$ResultData["leaderboardPos"].'</td>
        <td>'.$idGet["ingameNick"].'</td>
        <td>'.number_format($ResultData["destroyedPoints"], '0', '.', ' ').'</td>
        </tr>';
      }
} else {
  echo "An error has occured ID#11";
  exit();
}
  } else {
    echo '<tr>
      <th align="left">Position</th>
      <th align="left">Nickname</th>
      <th align="left">Destruction Points</th>
      <th align="left">Fleet points</th>
    </tr>';
    $getAll = mysqli_query($conn, "SELECT leaderboardPos, userID, destroyedPoints, fleetPoints FROM userfleet WHERE userID>100000 ORDER BY leaderboardPos ASC LIMIT 50");
    while ($All = mysqli_fetch_assoc($getAll)) {
      $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$All[userID]");
      $idGet = mysqli_fetch_assoc($sql);


      echo '<tr>
      <td>'.$All["leaderboardPos"].'</td>
      <td>'.$idGet["ingameNick"].'</td>
      <td>'.number_format($All["destroyedPoints"], '0', '.', ' ').'</td>
      <td>'.number_format($All["fleetPoints"], '0', '.', ' ').'</td>
      </tr>';
    }
  }
} else {
  echo "An error has occured ID#10";
  exit();
}
 ?>
