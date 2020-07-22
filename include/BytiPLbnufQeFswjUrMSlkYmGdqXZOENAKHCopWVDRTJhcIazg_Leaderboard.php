<?php
require "dbh.inc.php";

$sql = mysqli_query($conn, "SELECT userID FROM userfleet ORDER BY destroyedPoints DESC");
$position = 1;
while ($pos = mysqli_fetch_assoc($sql)) {
 $updatePosition = mysqli_query($conn, "UPDATE userfleet SET leaderboardPos=$position WHERE userID=$pos[userID]");
 $position++;
}
 ?>
