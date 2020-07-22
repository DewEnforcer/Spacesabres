<?php
session_start();
if (isset($_POST["from"]) && isset($_POST["random"]) && !empty($_POST["from"]) && !empty($_POST["random"])) {
require "dbh.inc.php";
$session = $_SESSION["sid"];
$sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
$ID = mysqli_fetch_assoc($sql);
$viewed = 1;
$nick = $_POST["from"];
$token = $_POST["random"];
$stripped =str_replace(" ", "", $nick);
$exploded = explode (":", $stripped); //remove FROM:
$sql = "SELECT userID FROM users WHERE username=?"; //getID of the sender
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $exploded[1]);
if (mysqli_stmt_execute($stmt) === TRUE) {
  mysqli_stmt_bind_result($stmt, $userID);
  mysqli_stmt_fetch($stmt);
  // set msg as viewed
  if ($exploded[1] == "System") {
    $userID = 1;
  } elseif ($exploded[1] == "Alliance") {
    $sql = mysqli_query($conn, "SELECT clanID FROM userclans WHERE clanTag='$ID[userclan]'");
    $clanID = mysqli_fetch_assoc($sql);
    $userID = $clanID["clanID"];
  }
  $sql = "UPDATE usermsg SET viewed=? WHERE toUserID=? AND token=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "iis", $viewed, $ID["userID"], $token);
  mysqli_stmt_execute($stmt);
  // get all msg params
  $sql = "SELECT sentTime, subject, msg, replymsg FROM usermsg WHERE fromUserID=? AND token=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "is", $userID, $token);
  if (mysqli_stmt_execute($stmt) === TRUE) {
    mysqli_stmt_bind_result($stmt, $sentTime, $subject, $msg, $replymsg);
    mysqli_stmt_fetch($stmt);
        echo "<img src='../image/graphics/closeMsg.png' id='closemsg' class='close_msg' alt='close'>";
    echo "<div class='msgInfoContainer'>";
    echo "<p id='from'> From: ".$exploded[1]."<p>";
    echo "<p> Sent on: ".date("Y-m-d G:i:s", $sentTime)."<p>";
    echo "<p id='subject'> Subject: ".$subject."</p>";
    echo "</div>";
    if (!empty($replymsg)){
    echo "<p id='replyingto'> Replying To: ".$replymsg."</p></div><br>"; }
    echo "<div id='msgTextContainer'> <p>Message: ".$msg."</p></div>";
    if ($exploded[1] != "System" && $exploded[1] != "Alliance") {
    echo "<button type='button' id='reply' class='reply-msg-button'>Reply to the message!</button>";
} else {
  "";
}
}
else {
  echo "An SQL error has occured!11";
  exit();
}

} else {
  echo "An SQL error has occured!";
  exit();
}


} else {
  echo "An error has occured!";
  exit();
}
 ?>
