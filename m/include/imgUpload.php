<?php
session_start();

include_once "dbh.inc.php";

$Nickname = $_SESSION["sid"];
$selectID = "SELECT userID, userclan FROM users WHERE sessionID=?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $selectID);
mysqli_stmt_bind_param($stmt, "s", $Nickname);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ResultID = mysqli_fetch_assoc($result);
$ID = $ResultID["userID"];
if (isset($_POST["submitbutton"])) {
  $file = $_FILES['img'];

  $fileName = $_FILES['img']['name'];
  $fileTmpName = $_FILES['img']['tmp_name'];
  $fileSize = $_FILES['img']['size'];
  $fileError = $_FILES['img']['error'];
  $fileType = $_FILES['img']['type'];

  $fileExt = explode('.', $fileName); // explode = rozdělí string podle toho kde chceme
  $fileActExt = strtolower(end($fileExt)); //vezme konec stringu z explode

  $allowed = array('jpg', 'jpeg', 'png');
  $imageSize = getimagesize($fileTmpName);
  if (in_array($fileActExt, $allowed)) { //to kde chcem ověřit jestli to je stejný co v array nebo ne, pak samotná array
    if ($fileError === 0) {
      if ($imageSize[0] < 200 && $imageSize[1] < 200) {
      if ($fileSize < 2000000) {
        $fileNameNew = "profile".$ResultID["userID"].".".$fileActExt;
        $fileDestination = '../uploads/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination); //přesune soubor do určené složky , první kde momentálně je , druhý kam má jít
        $sql = "UPDATE profileimg SET status= 0 WHERE userid=$ID";
        $result = mysqli_query($conn, $sql);
        header("location: ../internalProfile.php?success=upload");
        exit();
      } else {
        header("location: ../internalProfile.php?error=toobig");
        exit();
      }
    } else {
      header("location: ../internalProfile.php?error=sizetoobig");
      exit();
    }
    } else {
      header("location: ../internalProfile.php?error=uploadfail");
      exit();
    }
  } else {
    header("location: ../internalProfile.php?error=wrongext&&ext=$fileExt");
    exit();
  }
} elseif (isset($_POST["btn_submit_ally_logo"])) {
  $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanID FROM userclans WHERE clanTag='$ResultID[userclan]'");
  $clanInfo = mysqli_fetch_assoc($sql);

  $membersList = unserialize($clanInfo["clanMembers"]);
  $permissions = unserialize($clanInfo["clanMembersPerms"]);
  $getPermissionUser = array_search($ResultID["userID"], $membersList);
  $permissionUser = $permissions[$getPermissionUser];
  if ($permissionUser[8] != 5) {
    header("location: ../internalAlliance.php?error=noperms");
    exit();
  }

  $file = $_FILES['img'];

  $fileName = $_FILES['img']['name'];
  $fileTmpName = $_FILES['img']['tmp_name'];
  $fileSize = $_FILES['img']['size'];
  $fileError = $_FILES['img']['error'];
  $fileType = $_FILES['img']['type'];

  $fileExt = explode('.', $fileName); // explode = rozdělí string podle toho kde chceme
  $fileActExt = strtolower(end($fileExt)); //vezme konec stringu z explode

  $allowed = array('jpg', 'jpeg', 'png');
  $imageSize = getimagesize($fileTmpName);

  if (in_array($fileActExt, $allowed)) { //to kde chcem ověřit jestli to je stejný co v array nebo ne, pak samotná array
    if ($fileError === 0) {
      if ($imageSize[0] < 200 && $imageSize[1] < 200) {
      if ($fileSize < 2000000) {
        $fileNameNew = "clanavtr".$clanInfo["clanID"].".".$fileActExt;
        $fileDestination = '../uploads/clanavtr/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination); //přesune soubor do určené složky , první kde momentálně je , druhý kam má jít
        $sql = "UPDATE userclans SET clanImgStatus=1, clanImgID=$clanInfo[clanID] WHERE clanID=$clanInfo[clanID]";
        $result = mysqli_query($conn, $sql);
        header("location: ../internalAlliance.php?success=upload");
        exit();
      } else {
        header("location: ../internalAlliance.php?error=toobig");
        exit();
      }
    } else {
      header("location: ../internalAlliance.php?error=sizetoobig");
      exit();
    }
    } else {
      header("location: ../internalAlliance.php?error=uploadfail");
      exit();
    }
  } else {
    header("location: ../internalAlliance.php?error=wrongext&&ext=$fileExt");
    exit();
  }
} else {
  header("location: ../index.php");
  exit();
}


 ?>
