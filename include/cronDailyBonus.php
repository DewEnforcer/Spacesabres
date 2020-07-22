<?php
require "dbh.inc.php";

$sql = mysqli_query($conn, "SELECT userID, claimed FROM users");
while ($row = mysqli_fetch_assoc($sql)) {
  $resetClaimed = mysqli_query($conn, "UPDATE users SET claimed=0 WHERE userID=$row[userID]");
}
 ?>
