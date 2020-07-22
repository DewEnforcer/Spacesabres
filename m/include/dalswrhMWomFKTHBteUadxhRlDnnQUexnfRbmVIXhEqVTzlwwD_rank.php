<?php
require "dbh.inc.php";
$sql = mysqli_query($conn, "SELECT COUNT(*) as totalUsers FROM users");
$totalUsers = mysqli_fetch_assoc($sql);

$maxUsersForRank = [];
$maxUsersForRankPercentage = [1=>0.2, 2=>0.18, 3=>0.16, 4=>0.14, 5=>0.10, 6=>0.08, 7=>0.05, 8=>0.04, 9=>0.03, 10=>0.02];
$i = 1;
while ($i <= 10 ) {
  $maxUsersForRank[$i] = ceil($maxUsersForRankPercentage[$i] * $totalUsers["totalUsers"]);
  $i++;
}
$i = 10;
$sql = mysqli_query($conn, "SELECT userID FROM userfleet WHERE admin=0 ORDER BY destroyedPoints DESC");
while ($row = mysqli_fetch_assoc($sql)) {
  $updateRank = mysqli_query($conn, "UPDATE userfleet SET rank=$i WHERE userID=$row[userID]");
  $maxUsersForRank[$i] -= 1;
  if ($maxUsersForRank[$i] == 0) {
    $i--;
  }
}
 ?>
