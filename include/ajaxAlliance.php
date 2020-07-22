<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  if (isset($_POST["action"]) && $_POST["action"] == "showAlly" && isset($_POST["index"])) {
    $index = $_POST["index"];
    $sql = "SELECT  clanName, clanTag, clanLeader, clanImgID, totalMembers, clanContact, totalPoints, clanDetail FROM userclans ORDER BY clanName LIMIT ?,1";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $index);
    mysqli_stmt_execute($stmt);

    $getResult = mysqli_stmt_get_result($stmt);
    $allyInfo = mysqli_fetch_assoc($getResult);


    $sql = mysqli_query($conn, "SELECT clanLeader FROM userclans ORDER BY totalPoints DESC");
    $position = 1;
    while ($row = mysqli_fetch_assoc($sql)) {
      if ($row["clanLeader"] == $allyInfo["clanLeader"]) {
        break;
      } else {
        $position++;
      }
    }
    $allyInfo["position"] = $position;
    $allyInfo["index"] = $_POST["index"];
    print json_encode($allyInfo);
    exit();
  } elseif (isset($_POST["action"]) && $_POST["action"] == "showAllyJoin" && isset($_POST["index"])) {
    $index = $_POST["index"];
    $sql = "SELECT clanName, clanTag, clanLeader FROM userclans WHERE clanName=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $index);
    mysqli_stmt_execute($stmt);

    $getResult = mysqli_stmt_get_result($stmt);
    $allyInfo = mysqli_fetch_assoc($getResult);

    print json_encode($allyInfo);
    exit();
  } elseif (isset($_POST["action"])) {
    $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$_SESSION[sid]'");
    $userInfo = mysqli_fetch_assoc($sql);

    if ($userInfo["userclan"] == "none") {
      header("location: ../index.php");
      exit();
    }

    $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiploPending, clanID FROM userclans WHERE clanTag='$userInfo[userclan]'");
    $clanInfo = mysqli_fetch_assoc($sql);

    $membersList = unserialize($clanInfo["clanMembers"]);
    $permissions = unserialize($clanInfo["clanMembersPerms"]);
    $getPermissionUser = array_search($userInfo["userID"], $membersList);
    $permissionUser = $permissions[$getPermissionUser];
    $diplomacyPending = unserialize($clanInfo["clanDiploPending"]);

    if ($permissionUser[1] != 1) {
      echo "You don't have permissions to see pending requests!";
      exit();
    }
    $i = 0;
    if (empty($diplomacyPending) == FALSE) {
    foreach ($diplomacyPending as $key) {
      $sql = mysqli_query($conn, "SELECT clanName, clanTag FROM userclans WHERE clanID=$key[0]");
      $allyInfo = mysqli_fetch_assoc($sql);

      switch ($key[1]) {
        case '1':
          $type = "<p style=\"color: rgb(80,220,100)\">Alliance</p>";
        break;
        case '2':
          $type = "<p style=\"color: rgb(252,209,42)\">Non-agression pact</p>";
        break;

      }

      echo "<tr class='pending_diplo_box'>";
      echo "<td>[$allyInfo[clanTag]] $allyInfo[clanName]</td>";
      echo "<td>$type</td>";
      echo "<td><a href='../include/allianceHandler.php?action=cancelreq&&index=$i'>Cancel request</a></td>";
      echo "</tr>";
      $i++;
    }
  } else {
    echo "nopendings";
  }

  } else {
    header("location: ../index.php");
    exit();
  }
} else {
  header("location: ../index.php");
  exit();
}
 ?>
