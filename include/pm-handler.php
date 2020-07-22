<?php
session_start();
if (isset($_SESSION["sid"])) {
    require "dbh.inc.php";
    $session = $_SESSION["sid"];
    $getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
    if (mysqli_num_rows($getID) == 0) {
      header("location: ../index.php?error=10");
      exit();
    }
    $userInfo = mysqli_fetch_assoc($getID);

    if (isset($_POST["msg"]) && isset($_POST["subject"]) && isset($_POST["to"]) && isset($_POST["replyto"])) {
      // TODO: check if user has blocked him
      if (strlen($_POST["msg"]) == 0 || strlen($_POST["subject"]) == 0 || strlen($_POST["to"]) == 0) {
        echo "notfilled";
        exit();
      }
      $message = gzencode(htmlspecialchars($_POST["msg"]), 9);
      $subject = htmlspecialchars($_POST["subject"]);
      if (strlen($subject) > 30) {
        echo "longsubj";
        exit();
      }
      $to = htmlspecialchars($_POST["to"]);
      $replyto = gzencode(htmlspecialchars($_POST["replyto"]),9);
      $sql = "SELECT userID, blockedUsers FROM users WHERE ingameNick=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "error";
        exit();
      }
      mysqli_stmt_bind_param($stmt, "s", $to);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $recieverID = mysqli_fetch_assoc($result);
      if ($recieverID["userID"] <= 0) {
        echo "notfound";
        exit();
      }

      $blockedUsers = unserialize(gzdecode($recieverID["blockedUsers"]));
      foreach ($blockedUsers as $blocked) {
        if ($blocked == $userInfo["userID"]) {
          echo "blocked";
          exit();
        }
      }

      $currentTime = date("U"); //get sent time
      $viewed = 0; //default param
      $token = bin2hex(random_bytes(10));
        $sql = "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, replymsg, viewed, token) VALUES (?,?,?,?,?,?,?,?)";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "error";
        exit();
      }
      mysqli_stmt_bind_param($stmt, "iiisssis", $recieverID["userID"], $userInfo["userID"], $currentTime, $subject, $message, $replyto, $viewed, $token);
      mysqli_stmt_execute($stmt);

      echo "success";
      exit();
    } else {
      echo "notfilled";
      exit();
    }
} elseif (isset($_POST["btn_submit_allymsg"]) && isset($_POST["subject"]) && isset($_POST["msg"])) {
  $session = $_SESSION["sid"];
  $getID = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);
  $subject = $_POST["subject"];
  $message = gzencode(htmlspecialchars($_POST["msg"]), 9);

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
  echo "error";
  exit();
}

 ?>
