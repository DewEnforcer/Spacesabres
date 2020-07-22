<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, Email, Password, ingameNick FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    "";
  } else {
    session_unset();
    session_destroy();
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}

if (isset($_POST["request_pwd_change"]) && isset($_POST["oldpwd"])) {


$userEmail = mysqli_fetch_assoc($sql);

$checkPassword = password_verify($_POST["oldpwd"], $userEmail["Password"]);
if ($checkPassword === FALSE) {
  $_SESSION["newpwd"] = "wrongpwd";
  header("location: ../internalInfochange.php?error=wrongpwd");
  exit();
}


$id = bin2hex(random_bytes(8));
$token = random_bytes(32);

$url = "https://spacesabres.com/include/passwordChange.inc.php?id=$id&token=" .bin2hex($token);

$expires = date("U") + 1800;



$sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: ../internalInfochange.php?error=sql");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s" , $userEmail["Email"]);
  mysqli_stmt_execute($stmt);
}

$sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: ../internalInfochange.php?error=sql");
  exit();
} else {
  $hashedToken = password_hash($token, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($stmt, "sssi" , $userEmail["Email"], $id, $hashedToken, $expires);
  mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

$to = $userEmail["Email"];


$subject = 'Password change request for your Spacesabres account';

$message= '<p>Hi '.$userEmail["ingameNick"].' ,</p>';
$message .= '<p>You have requested a password change for your account. Click on the link we sent you below to continue.</p>';
$message .= "<p>Did not make this request? Just ignore this e-mail!</p>";
$message .= '<p>Here is your password reset link: </br>';
$message .= '<a href="' . $url . '">' . $url . '<a/></p>';

 $headers = "From: Spacesabres <noreply@spacesabres.com>\r\n";
$headers .= "Content-type: text/html\r\n";

mail($to, $subject, $message, $headers);

header("location: ../internalInfochange.php");
$_SESSION["newpwd"] = "success";

} else {
  header("location: ../index.php");
}
 ?>
