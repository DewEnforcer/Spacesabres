<?php
require 'dbh.inc.php';
if (isset($_POST["submit-new-nick"]) && isset($_POST["username"])) {
session_start();
$SESSION = $_SESSION['sid'];
$result = mysqli_query($conn, "SELECT nickChangeTime FROM users WHERE sessionID='$SESSION'");
$getTime=mysqli_fetch_assoc($result);
$currentTime = date("U");
if ($getTime["nickChangeTime"] < $currentTime) {

  $newNickname = $_POST["username"];
  $sql = "SELECT Username FROM users WHERE Username=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: signup.php?error=sql");
    exit();

  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $newNickname);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $resultCheck = mysqli_stmt_num_rows($stmt);
    if ($resultCheck > 0) {
      header("Location: ../internalInfochange.php?error=usernameTaken");
      exit();
    } else {


  $sql = "UPDATE users SET ingameNick=?, nickChangeTime=? WHERE sessionID=?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../internalInfochange.php?error=sql");
    exit();
  } else {
    $cooldownTime = date("U") + 172800;
    mysqli_stmt_bind_param($stmt, "sis" , $newNickname, $cooldownTime, $SESSION);
    $confirm = mysqli_stmt_execute($stmt);
    if ($confirm === TRUE) {
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      header("location: ../internalInfochange.php?success=nickchanged");
    }
  }
}
}
}
else {
  header("location: ../internalInfochange.php?error=cooldown&&time=".date('Y-m-d G:i:s',$getTime["nickChangeTime"])."");
}

} else {
  header("location: ../internalInfochange.php?error=emptyform");
}
 ?>
