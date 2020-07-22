<?php
if(isset($_GET["cancel"]) && isset($_GET["id"])) {
  require "dbh.inc.php";
  session_start();
  $userNick = $_SESSION["sid"];
  $id = $_GET["id"];
  $sql = "SELECT * FROM users WHERE sessionID='$userNick'";
        $getID = mysqli_query($conn, $sql);
        $ID = mysqli_fetch_assoc($getID);
  $sql = "SELECT setAttack, travelTime FROM usermovement WHERE userID=? AND id=?;";

  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql);
  mysqli_stmt_bind_param($stmt, "ii", $ID["userID"], $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $getAttackSet, $travelTime);
  mysqli_stmt_fetch($stmt);
  if ($travelTime > 1557163616 && $travelTime> (date("U"))) {
  $cancelTime = date("U");


  $parsedSet = $getAttackSet;
  $parsedCancel = $cancelTime;

  $getReturnTime = $parsedCancel-$parsedSet;

  $returnTime = $cancelTime + $getReturnTime;
  $updateTravel = 0;
  $updateReturn = 1;
  $missionType = 2;
  $sql2 = "UPDATE usermovement SET travelTime=?, travelWay=?, returnWay=?, missionType=? WHERE userID=? AND id=?;";
  $stmt = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt, $sql2);
  mysqli_stmt_bind_param($stmt, "iiiiii", $returnTime, $updateTravel, $updateReturn ,$missionType , $ID["userID"], $id);




  if (mysqli_stmt_execute($stmt)=== TRUE) { // // BUG: yeah this wont work so it will always give success

    header("location: ../internalFleets.php?success=1");
    exit();

  } else {
    header("location: ../internalFleets.php?error=sql");
    exit();
  }
} else {
  header("location: ../internalFleets.php?error=cannotcancel");
  exit();
}
}

else {
    header("location: ../internalFleets.php?error=wrongparams");
    exit();
  }


 ?>
