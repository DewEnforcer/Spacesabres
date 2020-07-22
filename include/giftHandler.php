<?php
session_start();
if (isset($_SESSION["sid"])) {
  require 'dbh.inc.php';
  $sql = mysqli_query($conn, "SELECT userID, tutorial, natium, credits, hyperid FROM users WHERE sessionID='$_SESSION[sid]'");
  $userInfo = mysqli_fetch_assoc($sql);
  if ($userInfo["tutorial"] == 0 && $userInfo["userID"] > 0) {
    $credits = $userInfo["credits"]+150000;
    $hyperid = $userInfo["hyperid"]+50000;
    $natium = $userInfo["natium"]+1000;
  $sql = mysqli_query($conn, "UPDATE users SET credits=$credits, hyperid=$hyperid, natium=$natium, tutorial=1 WHERE userID=$userInfo[userID]");
  if ($sql !== FALSE) {
    header("location: ../internalMissions.php?claim=success");
     exit();
  }
} else {
  header("location: ../index.php");
  exit();
}
}
else {
  header("location: ../index.php");
  exit();
}
 ?>
