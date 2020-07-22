<?php
session_start();
require "dbh.inc.php";
$array = [];
if (isset($_POST["map"]) && isset($_POST["dataX"]) && isset($_POST["dataY"]) && isset($_POST["type"]) && isset($_SESSION["sid"]) && isset($_POST["index"]) && $_POST["index"] == "attemptHack") {
  if ($_POST["type"] == "player") {
    $mapLocation = $_POST["map"];
    $coordsX = $_POST["dataX"];
    $coordsY = $_POST["dataY"];
    $session = $_SESSION["sid"];
    // ↓ get users ID
    $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
    $ID = mysqli_fetch_assoc($sql);
    // ↓ get users research level
    $sql = mysqli_query($conn, "SELECT researchSubspace FROM userresearch WHERE userID=$ID[userID]");
    $userResearch = mysqli_fetch_assoc($sql);
    //↓
    if ($userResearch["researchSubspace"] > 0) { // control if the user has the actual research on at least lvl 1;
      // ↓ get target userID
      $sql = "SELECT userID FROM userfleet WHERE pageCoordsX=? AND pageCoordsY=? AND mapLocation=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "sss", $coordsX, $coordsY ,$mapLocation);
      mysqli_stmt_execute($stmt);
      $getResult = mysqli_stmt_get_result($stmt);
      $ResultID = mysqli_fetch_assoc($getResult);
      echo mysqli_error($conn);

      $sql = mysqli_query($conn, "SELECT researchSubspace FROM userresearch WHERE userID=$ResultID[userID]");
      $targetResearch = mysqli_fetch_assoc($sql);

      $countResearchDifference = ($userResearch["researchSubspace"] - $targetResearch["researchSubspace"])*10;
      $baseChance = 50;
      $chanceWithResearch = $baseChance + $countResearchDifference;

      $randomGenerator = rand(1,100);
      if ($randomGenerator > $chanceWithResearch) {
        $array[0] = "attemptfailed";
        print json_encode($array);
        exit();
      } elseif ($randomGenerator < $chanceWithResearch) {
        $sql = mysqli_query($conn, "SELECT attack1, attack2, attack3, attack4, attack5, attack6, travelTime, attackedUserX, attackedUserY, targetMapLocation FROM usermovement WHERE userID=$ResultID[userID] ORDER BY travelTime ASC");
        if (mysqli_num_rows($sql) > 0) {
          $index = 0;
          while ($targetFleetMovement = mysqli_fetch_assoc($sql)) {
            $targetFleetMovement["travelTime"] = date('Y-m-d G:i:s', ($targetFleetMovement["travelTime"] ));
            $array[$index] = $targetFleetMovement;
            $index++;
          }
          print json_encode($array);
          exit();
        } else {
          $array[0] = "nofleetsdetected";
          print json_encode($array);
          exit();
        }
      }

    } else {
      $array[0] = "researcherror";
      print json_encode($array);
      exit();
    }

} else {
  $array[0] = "inputerror";
  print json_encode($array);
  exit();
}
} else {
  header("location: ../index.php");
  exit();
}
 ?>
