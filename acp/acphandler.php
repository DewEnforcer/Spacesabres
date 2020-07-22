<?php

if (isset($_POST["console"])) {
  $command = $_POST["console"];
  $array = explode("/", $command);

  if ($array[1] === "add") {
    if ($array[2] === "item") {
      if ($array[3] === "single") {
      if ($array[4] != 0 && empty($array[4]) === FALSE && $array[4] < 19) {
        if ($array[5] === "ammount") {
          if ($array[6] != 0) {
            if ($array[7] === "userid") {
              if ($array[8] != 0 && empty($array[8]) === FALSE && $array[8] > 100000) {
                $itemID = $array[4];
                $ammount = $array[6];
                $userID = $array[8];

                function updateItems($conn, $item, $ammount, $userID, $tablename) {
                  $sql = "SELECT $item FROM $tablename WHERE userID=?";
                  $stmt = mysqli_stmt_init($conn);
                  mysqli_stmt_prepare($stmt, $sql);
                  mysqli_stmt_bind_param($stmt, "s", $userID);
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
                  $resultItems = mysqli_fetch_assoc($result);

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

                require "dbh.inc.php";

                $sql = "SELECT userID FROM users WHERE userID=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql);
                mysqli_stmt_bind_param($stmt, "s", $userID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $userFound = mysqli_fetch_assoc($result);
                if (empty($userFound["userID"]) === FALSE) {
                  if ($itemID == 1) {
                    echo updateItems($conn, "credits", $ammount, $userID, "users");
                  } elseif($itemID == 2) {
                    echo updateItems($conn, "hyperid", $ammount, $userID, "users");
                  } elseif($itemID == 3) {
                    echo updateItems($conn, "natium", $ammount, $userID, "users");
                  } elseif($itemID == 4) {
                    echo updateItems($conn, "hornet", $ammount, $userID, "userfleet");
                  } elseif($itemID == 5) {
                    echo updateItems($conn, "spacefire", $ammount, $userID, "userfleet");
                  } elseif($itemID == 6) {
                    echo updateItems($conn, "starhawk", $ammount, $userID, "userfleet");
                  } elseif($itemID == 7) {
                    echo updateItems($conn, "peacemaker", $ammount, $userID, "userfleet");
                  } elseif($itemID == 8) {
                    echo updateItems($conn, "centurion", $ammount, $userID, "userfleet");
                  } elseif($itemID == 9) {
                    echo updateItems($conn, "nathalis", $ammount, $userID, "userfleet");
                  } elseif($itemID == 10) {
                    echo updateItems($conn, "inventoryMod1", $ammount, $userID, "userbase");
                  } elseif($itemID == 11) {
                    echo updateItems($conn, "inventoryMod2", $ammount, $userID, "userbase");
                  } elseif($itemID == 12) {
                    echo updateItems($conn, "inventoryMod3", $ammount, $userID, "userbase");
                  } elseif($itemID == 13) {
                    echo updateItems($conn, "inventoryMod4", $ammount, $userID, "userbase");
                  } elseif($itemID == 14) {
                    echo updateItems($conn, "researchDmg", $ammount, $userID, "userresearch");
                  } elseif($itemID == 15) {
                    echo updateItems($conn, "researchHp", $ammount, $userID, "userresearch");
                  } elseif($itemID == 16) {
                    echo updateItems($conn, "researchShd", $ammount, $userID, "userresearch");
                  } elseif($itemID == 17) {
                    echo updateItems($conn, "researchSpeed", $ammount, $userID, "userresearch");
                  } elseif($itemID == 18) {
                    echo updateItems($conn, "fuel", $ammount, $userID, "userfleet");
                  }
                   else {
                    echo "An error has occured1";
                    exit();
                  }
                }
                else {
                  echo "No user with this ID exists!";
                  exit();
                }

              }
            } else {
              echo "An error has occured2";
              exit();
            }
          } else {
            echo "An error has occured3";
            exit();
          }
        } else {
          echo "An error has occured4";
          exit();
        }
      } else {
        echo "An error has occured5";
        exit();
      }
    } else {
      echo "An error has occured6";
      exit();
    }
    } else {
      echo "An error has occured7";
      exit();
    }
  } elseif ($array[1] == "remove") {

  } else {
    echo "An error has occured8";
    exit();
  }

} else {
  echo "An error has occured9";
  exit();
}
?>
