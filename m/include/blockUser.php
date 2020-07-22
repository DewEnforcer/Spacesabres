<?php
session_start();
if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_SESSION["sid"]) && !empty($_SESSION["sid"])) {
  require "dbh.inc.php";
  $username = $_POST["username"];
  $session = $_SESSION["sid"];
  $getID = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  $ID = mysqli_fetch_assoc($getID);

  $sql = "SELECT userID FROM users WHERE ingameNick=?";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $userID);
  mysqli_stmt_fetch($stmt);

  $sql = "SELECT userID, blockedUserID, blockedUserNickname FROM blockedusers WHERE userID=? AND blockedUserID=?";
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "ii", $ID["userID"], $userID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $usersid, $blockedUsersID, $blockedUsersNickname);
  mysqli_stmt_fetch($stmt);
  if ($blockedUsersID <= 0) {
    $sql = "INSERT INTO blockedusers (userID, blockedUserID, blockedUserNickname) VALUES (?,?,?)";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $ID["userID"], $userID, $username);
    if (mysqli_stmt_execute($stmt) === TRUE ) {
      echo "User has been successfully blocked and cannot contact you anymore!";
      exit();
    } else {
      echo "There has been an error1!";
      exit();
    }
  } else {
    $sql = "DELETE FROM blockedusers WHERE userID=? AND blockedUserID=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $ID["userID"], intval($userID));
    if (mysqli_stmt_execute($stmt) === TRUE) {
      echo "User has been successfully unblocked and can contact you again!";
      exit();
    } else {
      echo "There has been an error!";
      exit();
    }
  }
} else {
  header("location: index.php");
  exit();
}

 ?>
