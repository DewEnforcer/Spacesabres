<?php
session_start();
if (isset($_SESSION["sid"])) {
  $session = $_SESSION["sid"];
  require "dbh.inc.php";
  if (isset($_POST["action"]) && isset($_POST["user"])) {
    $action = $_POST["action"];
    $user = $_POST["user"];
    $sql = mysqli_query($conn, "SELECT userID, blockedUsers from users WHERE sessionID='$session'");
    if (mysqli_num_rows($sql) == 0) {
      echo "error";
      exit();
    }
    $userInfo = mysqli_fetch_assoc($sql);
    if ($action == "block") {
      $sql = "SELECT userID FROM users WHERE ingameNick=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "s", $user);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $blockUser = mysqli_fetch_assoc($result);

      if ($blockUser["userID"] == 0) {
        echo "notfound";
        exit();
      }

      $blockedUsers = unserialize(gzdecode($userInfo["blockedUsers"]));
      $status = false;
      foreach ($blockedUsers as $blocked) {
        if ($blocked == $blockUser["userID"]) {
          $status = true;
          break;
        }
      }

      if ($status == true) {
        echo "alreadyblocked";
        exit();
      } elseif ($status == false) {
        array_push($blockedUsers, $blockUser["userID"]);
        $blockedUsers = gzencode(serialize($blockedUsers),9);
        $sql = "UPDATE users SET blockedUsers=? WHERE userID=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "si", $blockedUsers, $userInfo["userID"]);
        mysqli_stmt_execute($stmt);
        echo "success";
      }
    } else if ($action == "list") {
      $sql = mysqli_query($conn, "SELECT blockedUsers FROM users WHERE userID=$userInfo[userID]");
      $blockedUsers = mysqli_fetch_assoc($sql);
      $blockedNicks = [];
      $blockedUsers = unserialize(gzdecode($blockedUsers["blockedUsers"]));
      foreach ($blockedUsers as $user) {
        $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$user");
        $userBlocked = mysqli_fetch_assoc($sql);
        array_push($blockedNicks, $userBlocked["ingameNick"]);
      }

      if (empty($blockedNicks) === true) {
        echo "empty";
        exit();
      }
      echo json_encode($blockedNicks);
      exit();
    } else if ($action == "unblock") {
      $sql = mysqli_query($conn, "SELECT blockedUsers FROM users WHERE userID=$userInfo[userID]");
      $blockedUsers = mysqli_fetch_assoc($sql);

      $blockedUsers = unserialize(gzdecode($blockedUsers["blockedUsers"]));

      array_splice($blockedUsers, $user, 1);
      $blockedUsers = gzencode(serialize($blockedUsers),9);
      $sql = "UPDATE users SET blockedUsers=? WHERE userID=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "error";
        exit();
      }
      mysqli_stmt_bind_param($stmt, "si", $blockedUsers, $userInfo["userID"]);
      mysqli_stmt_execute($stmt);

      echo "success";
      exit();
    }
  } else {
    echo "error";
    exit();
  }
} else {
  header("location: ../index.php");
  exit();
}

 ?>
