<?php
session_start();
if (isset($_GET["action"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, defenseCooldown from users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($sql);

    $getCompDef = mysqli_fetch_assoc($sql);
    $missonType = 2;
    $returnTime = date("U") + 660;
    $sql = mysqli_query($conn, "UPDATE usermovement SET travelTime=$returnTime, reward=0 WHERE userID=$ID[userID]");

    if ($sql === TRUE) {
        $defenseCD= date("U") + 50400;
        $sql2 = mysqli_query($conn, "UPDATE users SET defenseCooldown=$defenseCD WHERE userID=$ID[userID]");
        if ($sql2=== TRUE) {
          header("location: ../internalCompanyDefense.php?status=success");
          exit();
        } else {
        header("location: ../internalCompanyDefense.php?status=error");
        }

    } else {
    header("location: ../internalCompanyDefense.php?status=error");
    }

} else {
  header("location: ../index.php");
  exit();
}
 ?>
