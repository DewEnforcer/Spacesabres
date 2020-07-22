<?php
session_start();

include_once "dbh.inc.php";

$sid = $_SESSION["sid"];
$selectID = "SELECT userID FROM users WHERE sessionID=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $selectID);
mysqli_stmt_bind_param($stmt, "s", $sid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ResultID = mysqli_fetch_assoc($result);
$ID = $ResultID["userID"];

$filename = "uploads/profile".$ID."*";
$fileinfo = glob($filename);
$fileExt = explode(".", $fileinfo[0]);
$fileActext = $fileExt[1];

$file = "uploads/profile".$ID.".$fileActext";

if (!unlink($file)) { //unlink = deletes the file
  echo "file was not deleted";
} else {
  echo "file was deleted";
}

$status = 1;

$profileImg = "UPDATE profileimg SET status=$status WHERE userID=?";
$stmt1 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt1, $profileImg)) {
  header("Location: ../internalProfile.php?error=sql");
  exit();
}
else {
mysqli_stmt_bind_param($stmt1, "i", $ID);
mysqli_stmt_execute($stmt1);
header("location: ../internalProfile.php?success=1");
exit();
}
 ?>
