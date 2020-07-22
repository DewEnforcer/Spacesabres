<?php
session_start();
if (isset($_POST["reset-request-submit"])) {

require "../include/dbh.inc.php";

$emailCheck = $_POST["email"];

$sql = "SELECT userID, ingameNick FROM users WHERE Email=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $emailCheck);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resultFetched = mysqli_fetch_assoc($result);
if ($resultFetched["userID"] < 1) {
  $_SESSION["pwdreset"] = "invalidemail";
  header("Location: ../resetPassword.php");
  exit();
}

$id = bin2hex(random_bytes(8));
$token = random_bytes(32);

$url = "https://spacesabres.com/pwdreset/pwdnew.php?id=$id&token=" .bin2hex($token);

$expires = date("U") + 1800;


$userEmail = $_POST["email"];

$sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: ../resetPassword.php?error=sql");
  exit();
} else {
  mysqli_stmt_bind_param($stmt, "s" , $userEmail);
  mysqli_stmt_execute($stmt);
}

$sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: ../resetPassword.php?error=sql");
  exit();
} else {
  $hashedToken = password_hash($token, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($stmt, "sssi" , $userEmail, $id, $hashedToken, $expires);
  mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

$to = $userEmail;


$subject = 'Password reset for your Spacesabres account!';

$message= '<p>Hi '.$resultFetched["ingameNick"].' ,</p>';
$message .= '<p>You have requested a password reset for your account. Click on the link we sent you below to continue.</p>';
$message .= "<p>Did not make this request? Just ignore this e-mail!</p>";
$message .= '<p>Here is your password reset link: </br>';
$message .= '<a href="' . $url . '">' . $url . '<a/></p>';

 $headers = "From: Spacesabres <noreply@spacesabres.com>\r\n";
$headers .= "Content-type: text/html\r\n";

mail($to, $subject, $message, $headers);

header("location: ../resetPassword.php");
$_SESSION["pwdreset"] = "success";

} else {
  header("location: ../index.php");
}
