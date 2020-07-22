<?php
session_start();
if (isset($_SESSION["sid"])) {
      require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
  } else {
    session_unset();
    session_destroy();
    header("location: ../index.php?error=10");
    exit();
  }
  if (isset($_POST["action"]) && isset($_POST["msgs"])) {
    $action = $_POST["action"];
    try {
      $msgs = json_decode($_POST["msgs"]);
    } catch (\Exception $e) {
      echo "error";
      exit();
    }
    if ($action == "delete") {
      $sql = "DELETE FROM usermsg WHERE toUserID=? AND token=?";
    } else if ($action == "read") {
      $sql = "UPDATE usermsg SET viewed=1 WHERE toUserID=? AND token=?";
    } else {
      echo "error";
      exit();
    }
    foreach ($msgs as $token) {
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "is", $userInfo["userID"], $token);
      mysqli_stmt_execute($stmt);
    }
    echo "success";
    exit();
  } else {
    echo "error";
    exit();
  }
} else {
  header("Location: ../index.php");
  exit();
}
 ?>
