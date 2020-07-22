<?php
session_start();
if (isset($_POST["checker"]) && !empty($_POST["checker"]) && isset($_SESSION["sid"]) && !empty($_SESSION["sid"])) {
  if ($_POST["checker"] == "markAll") {
    require "dbh.inc.php";
    $session = $_SESSION["sid"];
    $getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
    $ID = mysqli_fetch_assoc($getID);

    $sql = mysqli_query($conn, "UPDATE usermsg SET viewed=1 WHERE toUserID=$ID[userID]");
    if ($sql === TRUE) {
      echo "All messages have been marked as seen!";
      exit();
    } else {
      echo "There has been an error!";
      exit();
    }
  } else {
    header("location: index.php");
    exit();
  }
} elseif (isset($_POST["randStrs"]) && !empty($_POST["randStrs"]) && isset($_SESSION["sid"]) && !empty($_SESSION["sid"])) {
  $convert = json_decode($_POST["randStrs"]);
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $items = count($convert);
  $i = 1;
  $getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);
  while ($i < $items) {
    $item = $convert[$i];
    if (strlen($convert[$i]) > 1) {
      $sql = mysqli_query($conn, "UPDATE usermsg SET viewed=1 WHERE toUserID=$ID[userID] AND token='$item'");
      $i++;
    }else {;
      $i++;

    }
  }
  echo "Success, all selected messages have been marked as seen!";
} else {
    header("location: index.php");
    exit();
}
 ?>
