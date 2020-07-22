<?php

if (isset($_POST["reset-password-submit"])) {

  $selector = $_POST["selector"];
  $validator = $_POST["validator"];
  $password = $_POST["newPwd"];
  $passwordRepeat = $_POST["repeatPwd"];

  if (empty($password) || empty($passwordRepeat)) {
    header("location: ../create-new-password.php?error=emptypwd&&validator=$validator&&selector=$selector");
    exit();
  } else if ($password != $passwordRepeat) {
    header("location: ../create-new-password.php?error=nomatch&&validator=$validator&&selector=$selector");
    exit();
  }

$currentDate = date("U");

require 'dbh.inc.php';

$sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= $currentDate";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: location: ../create-new-password.php?error=incorrectvalidators");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s" , $selector);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  if (!$row = mysqli_fetch_assoc($result)) {
    echo "You need to resubmit your reset request";
    exit();
  } else  {

    $tokenBin = hex2bin($validator);
    $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

    if ($tokenCheck === FALSE) {
      echo "You need to resubmit your reset request";
      exit();
    } else if ($tokenCheck === TRUE) {

      $tokenEmail = $row["pwdResetEmail"];

      $sql= "SELECT * FROM users WHERE Email=?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: location: ../create-new-password.php?error=incorrectvalidators");
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (!$row = mysqli_fetch_assoc($result)) {
          echo "There was an error";
          exit();
        } else  {

          $sql = "UPDATE users SET pwdUsers=? WHERE Email=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: location: ../create-new-password.php?error=1");
            exit();
          } else {
            $hashedPwd= password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $tokenEmail);
            mysqli_stmt_execute($stmt);

            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "error"
              exit();
            } else {

              mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
              mysqli_stmt_execute($stmt);
              header("location: ../index.php?newpwd=passwordupdated");
              }

}
          }
        }

    }
  }
}
}




else {
    header("location: ../index.php");

}

 ?>
