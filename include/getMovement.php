<?php
require "accessSecurity.php";
if (isset($_POST["action"])) {
  $action = $_POST["action"];
  if ($action == "checkAttack") {
    $sql = mysqli_query($conn, "SELECT userID FROM usermovement WHERE targetUserID=$userInfo[userID]");
    $status = 1;
    if (mysqli_num_rows($sql) == 0) {
      $status = 0;
    }
    echo $status;
    exit();
  } else if ($action == "fleetbar_data") {
    $dataArr = [];
    $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE targetUserID=$userInfo[userID] OR userID=$userInfo[userID] ORDER BY travelTime ASC");
    while ($row = mysqli_fetch_assoc($sql)) {
      array_push($dataArr, ["missionType"=>$row["missionType"], "arrival"=>$row["travelTime"], "x"=>$row["attackedUserX"], "y"=>$row["attackedUserY"], "map"=>$row["targetMapLocation"], "ships"=>0]);
    }
    echo json_encode($dataArr);
    exit();
  } else {
    echo "error";
    exit();
  }
} else {
  echo "error";
  exit();
}


if (isset($_POST["index"]) && $_POST["index"]=="get_mov") {
$timeCurrent = date("Y-m-d G:i:s");
$time = mysqli_query($conn, "SELECT attackedUserX,	attackedUserY,	targetMapLocation,	attack1,	attack2,	attack3,	attack4,	attack5,	attack6, travelTime FROM usermovement WHERE userID=$ID[userID] AND travelWay=1 ORDER BY travelTime ASC");
$array = [[],[]];
if (mysqli_num_rows($time) > 0) {
$getTravel = mysqli_fetch_assoc($time);
$index = 0;
foreach ($getTravel as $Travel) {
  if ($index == 9) {
    $array[0][$index] = date('Y-m-d G:i:s',$Travel);
    $index++;
  } else {
  $array[0][$index] = $Travel;
  $index++;
}
}
} else {
  $array[0][0] = "empty";
}

$sql = mysqli_query($conn, "SELECT attackedUserX,	attackedUserY,	targetMapLocation,	attack1,	attack2,	attack3,	attack4,	attack5,	attack6,	travelTime FROM usermovement WHERE userID=$ID[userID] AND returnWay=1 ORDER BY travelTime ASC");
if (mysqli_num_rows($sql) > 0) {
  $getReturn = mysqli_fetch_assoc($sql);
  $index = 0;
  foreach ($getReturn as $Return) {
    if ($index == 9) {
      $array[1][$index] = date('Y-m-d G:i:s',$Return);
      $index++;
    } else {
    $array[1][$index] = $Return;
    $index++;
  }
  }
} else {
  $array[1][0] = "empty";
}

$sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE userID=$ID[userID]");
$userCoords = mysqli_fetch_assoc($sql);
$sql = mysqli_query($conn, "SELECT userID , attack1,	attack2,	attack3,	attack4,	attack5,	attack6 FROM usermovement WHERE attackedUserX=$userCoords[pageCoordsX] AND attackedUserY=$userCoords[pageCoordsY] AND targetMapLocation=$userCoords[mapLocation]");
if (mysqli_num_rows($sql)> 0) {
  $array[2][0] = "You are under attack";
} else {
  $array[2][0] = "no attacks";
}

print json_encode($array);
} elseif (isset($_SESSION["sid"]) && isset($_POST["index"]) && isset($_POST["id"]) && $_POST["index"]=="getspecificdetailsuser") {
  $movementID = mysqli_real_escape_string($conn, $_POST["id"]);
  $sql = mysqli_query($conn, "SELECT attack1, attack2, attack3, attack4, attack5, attack6 FROM usermovement WHERE userID=$ID[userID] LIMIT $movementID,0 ");
  $movementDetails = mysqli_fetch_assoc($sql);
  print json_encode($movementDetails);
  exit();
} else {
  echo "An error has occured ID#11";
  exit();
}
 ?>
