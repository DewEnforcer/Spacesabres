<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["index"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql) > 0) {
    $ID = mysqli_fetch_assoc($sql);

    $sql = mysqli_query($conn, "SELECT timeLeft FROM userquests WHERE userID=$ID[userID]");
    $objective = mysqli_fetch_assoc($sql);
    if ($objective["timeLeft"] > 0) {
      echo date('Y-m-d G:i:s', $objective["timeLeft"]);
    } else {
      echo "empty";
      exit();
    }
  } else {
        $_SESSION["sid"] = "";
    header("Location: ../index.php");
    exit();
  }
} else {
  echo "An error has occured ID#11";
  exit();
}
 ?>
