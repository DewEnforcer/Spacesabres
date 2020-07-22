<?php
session_start();
if (isset($_POST["index"]) && isset($_SESSION["sid"]) && isset($_POST["action"])) {
  $action = $_POST["action"];
require "dbh.inc.php";
$session = $_SESSION["sid"];
$sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
if (mysqli_num_rows($sql) == 0) {
  echo "error";
  exit();
}
$userInfo = mysqli_fetch_assoc($sql);
$index = $_POST["index"];
if ($action == "recieved") {
  $sql = "SELECT msg, fromUserID, token, replymsg FROM usermsg WHERE toUserID=? ORDER BY sentTime DESC LIMIT ?,1"; //getID of the sender
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "is", $userInfo["userID"], $index);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $msg = mysqli_fetch_assoc($result);

  $sql = mysqli_query($conn, "UPDATE usermsg SET viewed=1 WHERE toUserID=$userInfo[userID] AND token='$msg[token]'");

  echo json_encode([gzdecode($msg["msg"]), gzdecode($msg["replymsg"])]);
} elseif ($action == "sent") {
  $sql = "SELECT msg, fromUserID FROM usermsg WHERE toUserID=? ORDER BY sentTime DESC LIMIT ?,1"; //getID of the sender
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "is", $userInfo["userID"], $index);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $msg = mysqli_fetch_assoc($result);

  echo json_encode([gzdecode($msg["msg"])]);
}

} else {
  echo "An error has occured!";
  exit();
}
 ?>
