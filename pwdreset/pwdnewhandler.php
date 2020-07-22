<?php
session_start();
if (isset($_POST["reset-password-submit"])) {

  $selector = $_POST["id"];
  $validator = $_POST["token"];
  $password = $_POST["newPwd"];
  $passwordRepeat = $_POST["repeatPwd"];

  if (empty($password) || empty($passwordRepeat)) {
    header("location: pwdnew.php?error=emptypwd&&validator=$validator&&selector=$selector");
    exit();
  } else if ($password != $passwordRepeat) {
    header("location: pwdnew.php?error=nomatch&&validator=$validator&&selector=$selector");
    exit();
  }

$currentDate = date("U");

require '../include/dbh.inc.php';

$sql = "SELECT * FROM pwdreset WHERE pwdResetSelector=? AND pwdResetExpires>=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  $_SESSION["newpwdreset"] = "Unfortunately, an error has occured!";
  header("location:pwdnew.php");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "si" , $selector, $currentDate);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  if (!$row = mysqli_fetch_assoc($result)) {
    $_SESSION["newpwdreset"] = "Unfortunately your password reset request has expired, request a new one <a href='../resetPassword.php'>here</a>!";
    header("location:pwdnew.php");
    exit();
  } else  {

    $tokenBin = hex2bin($validator);
    $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

    if ($tokenCheck === FALSE) {
    $_SESSION["newpwdreset"] = "Unfortunately your password reset request has expired, or some mysterious forces have played with your token, either way you can request a new password reset <a href='../resetPassword.php'>here</a>!";
    header("location:pwdnew.php");
      exit();
    } else if ($tokenCheck === TRUE) {

      $tokenEmail = $row["pwdResetEmail"];

      $sql= "SELECT * FROM users WHERE Email=?;";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        $_SESSION["newpwdreset"] = "Unfortunately, an error has occured!";
        header("location:pwdnew.php");
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (!$row = mysqli_fetch_assoc($result)) {
          $_SESSION["newpwdreset"] = "Unfortunately, an error has occured!";
          header("location:pwdnew.php");
          exit();
        } else  {

          $sql = "UPDATE users SET Password=? WHERE Email=?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            $_SESSION["newpwdreset"] = "Unfortunately, an error has occured!";
            header("location:pwdnew.php");
            exit();
          } else {
            $hashedPwd= password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $tokenEmail);
            mysqli_stmt_execute($stmt);

            $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              $_SESSION["newpwdreset"] = "Unfortunately, an error has occured!";
              header("location:pwdnew.php");
              exit();
            } else {

              mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
              mysqli_stmt_execute($stmt);
              header("location: ../index.php");
              $_SESSION["newpwdreset"] = "success";
              exit();
              }

}
          }
        }

    }
  }
}
}




else {
    header("location: index.php?error=click");
    exit();
}

 ?>
