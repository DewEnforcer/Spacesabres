<?php
require "dbh.inc.php";

$sql = mysqli_query($conn, "UPDATE users SET loginBonusDay=1, claimed=0");
exit();
 ?>
