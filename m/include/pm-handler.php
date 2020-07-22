<?php
session_start();
if (isset($_POST["to"]) && isset($_POST["subject"]) &&isset($_POST["message"]) &&isset($_POST["id"]) && isset($_SESSION["sid"]) && !empty($_SESSION["sid"])) {
require "dbh.inc.php";
$session = $_SESSION["sid"];
$getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
$ID = mysqli_fetch_assoc($getID);
$to = $_POST["to"]; //select all params
$subject = $_POST["subject"];
$message = $_POST["message"];
$id = $_POST["id"];
if (!empty($to) && !empty($subject) && !empty($message) && !empty($id)) { //check if one of them isnt empty
$removeHTMLto = htmlspecialchars($to);
$removeHTMLsubject = htmlspecialchars($subject, ENT_COMPAT, 'UTF-8');
$removeHTMLmessage = htmlspecialchars($message, ENT_COMPAT, 'UTF-8'); // cross scripting protection
$escapeNick = mysqli_real_escape_string($conn, $to);
$getToID = mysqli_query($conn, "SELECT userID FROM users WHERE ingameNick='$escapeNick'");
if (mysqli_num_rows($getToID) == 0) { // check if any user with this nickname exits, if not
  echo "No user with such nickname found!"; //send error , end script
  exit();
} elseif(mysqli_num_rows($getToID) == 1) { //if yes , will equal 1, check for this users msgs
  $toID = mysqli_fetch_assoc($getToID);
  $sql = mysqli_query($conn, "SELECT * FROM blockedusers WHERE userID=$toID[userID] AND blockedUserID=$ID[userID]");
  if (mysqli_num_rows($sql) <= 0){
  $sql = mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$toID[userID] ORDER BY sentTime ASC");// check for all msgs to receiver of this msg
  if (mysqli_num_rows($sql) > 29) { // if he has more than 30 in his inbox archived
    $getMsgID = mysqli_fetch_assoc($sql); //get ID of the oldest msg
    $sql= mysqli_query($conn, "DELETE FROM usermsg WHERE id=$getMsgID[id]"); //delete the oldest one
    if ($sql === TRUE) {
      $currentTime = date("U"); //get sent time
      $viewed = 0; //default param
      $bytes = bin2hex(random_bytes(10));
      $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, viewed, token) VALUES (?, ?, ?, ?, ?, ?, ?)"; //inserting msg
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "iiissss", $toID["userID"], $id, $currentTime, $removeHTMLsubject, $removeHTMLmessage, $viewed, $bytes);
      if (  mysqli_stmt_execute($stmt) === TRUE) { //if succeeded , return user
        echo "Success! The message has been sent!";
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
      $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, viewed, token) VALUES (?, ?, ?, ?, ?, ?, ?)"; //inserting msg
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "iiissss", intval($toID["userID"]), intval($id), $currentTime, $removeHTMLsubject, $removeHTMLmessage, $viewed, $bytes);
      if (  mysqli_stmt_execute($stmt) === TRUE) { //if succeeded , return user
        echo "Success! The message has been sent!";
        exit();
  } else {
    echo mysqli_error($conn);
  }
}
} else {
  echo "This user has blocked you and you cannot contact him!";
  exit();
}
} else {
  echo "SQL error";
  exit();
}


} else {
  echo "Error, you haven't filled out all the data!";
  exit();
}
} elseif (isset($_POST["btn_submit_allymsg"]) && isset($_POST["subject"]) && isset($_POST["msg"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $getID = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);
  $subject = $_POST["subject"];
  $message = $_POST["msg"];

  if ($ID["userclan"] == "none") {
    $_SESSION["send_ally_msg"] = "noally";
    header("location: ../internalAlliances.php");
    exit();
  }
  $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanID FROM userclans WHERE clanTag='$ID[userclan]'");
  $clanInfo = mysqli_fetch_assoc($sql);

  $membersList = unserialize($clanInfo["clanMembers"]);
  $permissions = unserialize($clanInfo["clanMembersPerms"]);

  $getKey = array_search($ID["userID"], $membersList);
  $userPermissions = $permissions[$getKey];
  if ($userPermissions[6] == 0) {
    $_SESSION["send_ally_msg"] = "noperms";
    header("location: ../internalAllianceMsg.php");
    exit();
  }

  if (empty($subject) || empty($message)) { //check if one of them isnt empty
    $_SESSION["send_ally_msg"] = "missingparam";
    header("location: ../internalAllianceMsg.php");
    exit();
  }

  $removeHTMLsubject = htmlspecialchars($subject, ENT_COMPAT, 'UTF-8');
  $removeHTMLmessage = htmlspecialchars($message, ENT_COMPAT, 'UTF-8'); // cross scripting protection
  $token = bin2hex(random_bytes(10));
  $date = date("U");
  $viewed = 0;
  foreach ($membersList as $key) {
    $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, viewed, token) VALUES (?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "iiissis", $key, $clanInfo["clanID"], $date, $removeHTMLsubject, $removeHTMLmessage, $viewed, $token);
    mysqli_stmt_execute($stmt);
  }
    echo mysqli_error($conn);
    $_SESSION["send_ally_msg"] = "success";
    header("location: ../internalAllianceMsg.php");
    exit();

} else {
  echo "You haven't entered all data!";
  exit();
}


 ?>
