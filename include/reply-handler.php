<?php
session_start();
if (isset($_POST["to"]) && isset($_POST["subject"]) &&isset($_POST["message"])&& isset($_POST["replymsg"])&& isset($_SESSION["sid"]) ) {
require "dbh.inc.php";
$to = $_POST["to"]; //select all params
$subject = $_POST["subject"];
$message = $_POST["message"];
$sessionID = $_SESSION["sid"];
$replymsg = $_POST["replymsg"];
$getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$sessionID'");
$id = mysqli_fetch_assoc($getID);
if (!empty($to) && !empty($subject) && !empty($message) && !empty($id)) { //check if one of them isnt empty
$removeHTMLto = htmlspecialchars($to);
$removeHTMLsubject = htmlspecialchars($subject);
$removeHTMLmessage = htmlspecialchars($message); // cross scripting protection
$escapeNick = mysqli_real_escape_string($conn, $to);
$getToID = mysqli_query($conn, "SELECT userID FROM users WHERE username='$escapeNick'");
if (mysqli_num_rows($getToID) == 0) { // check if any user with this nickname exits, if not
  echo "No user with such nickname found!"; //send error , end script
  exit();
} elseif(mysqli_num_rows($getToID) == 1) { //if yes , will equal 1, check for this users msgs
  $toID = mysqli_fetch_assoc($getToID);
  $sql = mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$toID[userID] ORDER BY sentTime ASC");// check for all msgs to receiver of this msg
  if (mysqli_num_rows($sql) > 29) { // if he has more than 30 in his inbox archived
    $getMsgID = mysqli_fetch_assoc($sql); //get ID of the oldest msg
    $sql= mysqli_query($conn, "DELETE FROM usermsg WHERE id=$getMsgID[id]"); //delete the oldest one
    if ($sql === TRUE) {
      $currentTime = date("U"); //get sent time
      $viewed = 0; //default param
      $bytes = bin2hex(random_bytes(10));
      $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, viewed, replymsg, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; //inserting msg
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "iiisssss", $toID["userID"], $id["userID"], $currentTime, $removeHTMLsubject, $removeHTMLmessage, $viewed, $replymsg , $bytes);
      if (  mysqli_stmt_execute($stmt) === TRUE) { //if succeeded , return user
        echo "Success! $id[userID], $_POST[replymsg] The message has been sent!<br>";
        exit();
      } else {
        echo "Error , message hasn't been sent due to SQL error!";
      }
    } else {
      echo "There has been an SQL error!";
      exit();
    }
  } else {
    $getMsgID = mysqli_fetch_assoc($sql);
    $currentTime = date("U"); //get sent time
    $bytes = bin2hex(random_bytes(10));
      $viewed = 0; //default param
      $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, viewed, replymsg, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; //inserting msg
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "iiisssss", $toID["userID"], $id["userID"], $currentTime, $removeHTMLsubject, $removeHTMLmessage, $viewed, $replymsg, $bytes);
      if (  mysqli_stmt_execute($stmt) === TRUE) { //if succeeded , return user
        echo "Success! The message has been sent!";
        exit();
  }}
} else {
  echo "SQL error";
}


} else {
  echo "Error, you haven't filled out all the data!";
  exit();
}
} else {
  echo "You haven't entered all data!";
}


 ?>
