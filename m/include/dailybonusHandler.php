<?php
session_start();
if (isset($_SESSION["sid"]) && $_SESSION["sid"] != "") {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, claimed, loginBonusDay FROM users WHERE sessionID='$session'");
  echo mysqli_num_rows($sql);
  if (mysqli_num_rows($sql)>0) {
    $claimedLogin = mysqli_fetch_assoc($sql);
    "";
  } else {
    $_SESSION["sid"] = "";
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
  exit();
}
if (isset($_POST["action"]) && $_POST["action"] == "claimLogin") {


if ($claimedLogin["claimed"] == 1) {
  header("location: ../index.php");
  exit();
} elseif ($claimedLogin["claimed"] == 0) {

  $sql = mysqli_query($conn, "SELECT * FROM dailybonus WHERE day=$claimedLogin[loginBonusDay]");
  $dayBonusDetails = mysqli_fetch_assoc($sql);


  function updateItems($conn, $item, $ammount, $userID, $tablename) {
    $sql = mysqli_query($conn,"SELECT $item FROM $tablename WHERE userID=$userID");
    $resultItems = mysqli_fetch_assoc($sql);

    $newItems = $resultItems[$item] + $ammount;

    $sql = "UPDATE $tablename SET $item=? WHERE userID=?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $newItems, $userID);
    if (mysqli_stmt_execute($stmt) !== FALSE) {
      $return = "Items have been succesfully updated";
    } else {
      mysqli_error($conn);
      $return = "An database error has occured";
    }
    return $return;
  }

  function decideItem ($conn, $itemID, $userID, $ammount) {
    if ($itemID == 1) {
       updateItems($conn, "hornet", $ammount, $userID, "userfleet");
    } elseif($itemID == 2) {
       updateItems($conn, "spacefire", $ammount, $userID, "userfleet");
    } elseif($itemID == 3) {
       updateItems($conn, "starhawk", $ammount, $userID, "userfleet");
    } elseif($itemID == 4) {
       updateItems($conn, "peacemaker", $ammount, $userID, "userfleet");
    } elseif($itemID == 5) {
       updateItems($conn, "centurion", $ammount, $userID, "userfleet");
    } elseif($itemID == 6) {
       updateItems($conn, "nathalis", $ammount, $userID, "userfleet");
    } elseif($itemID == 7) {
       updateItems($conn, "inventoryMod1", $ammount, $userID, "userbase");
    } elseif($itemID == 8) {
       updateItems($conn, "inventoryMod2", $ammount, $userID, "userbase");
    } elseif($itemID == 9) {
       updateItems($conn, "inventoryMod3", $ammount, $userID, "userbase");
    } elseif($itemID == 10) {
       updateItems($conn, "inventoryMod4", $ammount, $userID, "userbase");
    } elseif($itemID == 11) {
       updateItems($conn, "researchDmg", $ammount, $userID, "userresearch");
    } elseif($itemID == 12) {
       updateItems($conn, "researchHp", $ammount, $userID, "userresearch");
    } elseif($itemID == 13) {
       updateItems($conn, "researchShd", $ammount, $userID, "userresearch");
    } elseif($itemID == 14) {
       updateItems($conn, "researchSpeed", $ammount, $userID, "userresearch");
    } elseif($itemID == 15) {
       updateItems($conn, "researchSubspace", $ammount, $userID, "userresearch");
    } elseif($itemID == 16) {
       updateItems($conn, "credits", $ammount, $userID, "users");
    } elseif($itemID == 17) {
       updateItems($conn, "hyperid", $ammount, $userID, "users");
    } elseif($itemID == 18) {
       updateItems($conn, "natium", $ammount, $userID, "users");
    }
  }

  $updateItem1 = decideItem($conn, $dayBonusDetails["itemID1"], $claimedLogin["userID"], $dayBonusDetails["item1Ammount"]);
  $updateItem2 = decideItem($conn, $dayBonusDetails["itemID2"], $claimedLogin["userID"], $dayBonusDetails["item2Ammount"]);
  $updateItem3 = decideItem($conn, $dayBonusDetails["itemID3"], $claimedLogin["userID"], $dayBonusDetails["item3Ammount"]);
  $updateItem4 = decideItem($conn, $dayBonusDetails["itemID4"], $claimedLogin["userID"], $dayBonusDetails["item4Ammount"]);

  $newLoginDay = $claimedLogin["loginBonusDay"]+1;
  $sql = mysqli_query($conn, "UPDATE users SET claimed=1,loginBonusDay=$newLoginDay WHERE userID=$claimedLogin[userID]");
  if ($sql !== FALSE) {
    echo "success";
    exit();
  } else {
    echo "error";
    exit();
  }

}
} elseif (isset($_POST["action"]) && $_POST["action"] == "getNewVal") {

  $sql = mysqli_query($conn, "SELECT credits, hyperid, natium FROM users WHERE userID=$claimedLogin[userID]");
  if ($sql !== FALSE) {
  $userValute = mysqli_fetch_assoc($sql);

  $array= [$userValute["credits"], $userValute["hyperid"], $userValute["natium"], $claimedLogin["userID"]];
  print json_encode($array);
  exit();
} else {
  $array= ["error"];
  print json_encode($array);
  exit();
}
} else {
  header("location: ../index.php");
  exit();
}

 ?>
