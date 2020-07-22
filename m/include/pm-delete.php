<?php
session_start();
if (isset($_POST["from"]) && isset($_POST["random"]) && isset($_SESSION["sid"])) {
require "dbh.inc.php";
$userFrom = str_replace(" ", "", $_POST["from"]);
$fromExplode = explode(":", $userFrom);
$token = $_POST["random"];
$escapeNick = mysqli_real_escape_string($conn, $fromExplode[1]);
$sql = mysqli_query($conn, "SELECT userID FROM users WHERE ingameNick='$escapeNick'");
if (mysqli_num_rows($sql) == 1) {
  $fromID = mysqli_fetch_assoc($sql);
  $sql = "DELETE FROM usermsg WHERE fromUserID=? AND token=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "is", $fromID["userID"], $token);
  if (mysqli_stmt_execute($stmt) === TRUE) {
    echo "The message has been successfully deleted! Refresh your inbox to see changes";
    exit();
  } else {
    exit();
  }

}
}

elseif (isset($_POST["index"]) && !empty($_POST["index"])) {
  if ($_POST["index"] == "deleteAll") {
    require "dbh.inc.php";
    $session = $_SESSION["sid"];
    $getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
    $ID = mysqli_fetch_assoc($getID);
    $sql = mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$ID[userID]");
    if (mysqli_num_rows($sql) > 0) {
    $sql = mysqli_query($conn, "DELETE FROM usermsg WHERE toUserID=$ID[userID]");
    if ($sql === TRUE) {
      echo "Success, all messages have been deleted! Refresh your page!";
    } else {
      echo "Error!";
      exit();
    }
  } else {
    echo "You don't have any messages to delete!";
    exit();
  }
} else {
  exit();
}
} elseif (isset($_POST["randStrs"]) && !empty($_POST["randStrs"])) {
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
      $sql = mysqli_query($conn, "DELETE FROM usermsg WHERE toUserID=$ID[userID] AND token='$item'");
      $i++;
    }else {;
      $i++;

    }
  }
  echo "Success, all selected messages have been deleted!";
} else {
  echo "An error has occured ID#12";
  exit();
}

 ?>
