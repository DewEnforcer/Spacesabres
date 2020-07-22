<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["action"])) {
require "dbh.inc.php";
$session = $_SESSION["sid"];
$sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
if (mysqli_num_rows($sql) == 0) {
  echo "error";
  exit();
}
$userInfo = mysqli_fetch_assoc($sql);
$sql = mysqli_query($conn, "SELECT toUserID, subject, sentTime, token FROM usermsg WHERE fromUserID=$userInfo[userID] ORDER BY sentTime DESC");
if (mysqli_num_rows($sql) == 0) {
  echo "nomsgs";
}
$sentMsgs = [];
while($sentMessages = mysqli_fetch_assoc($sql)) {
  $getNick = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$sentMessages[toUserID]");
  $recieverNick = mysqli_fetch_assoc($getNick);
  if (mysqli_num_rows($getNick) == 0) {
    continue;
  }
  $subject = $sentMessages["subject"];
  $sent = $sentMessages["sentTime"];
  array_push($sentMsgs, [$recieverNick["ingameNick"], $subject, date("Y-m-d G:i:s", $sent), $sentMessages["token"]]);
}
  if (empty($sentMsgs) == true) {
    echo "nomsgs";
    exit();
  }
  echo json_encode($sentMsgs);
  exit();

} else {
  header("location: ../index.php");
  exit();
}
?>
