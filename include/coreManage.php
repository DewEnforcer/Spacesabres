<?php
  session_start();
  if (isset($_SESSION["sid"])) {
    if (isset($_POST["index"]) && isset($_POST["amount"])) {
      $points = $_POST["amount"];
      $type = $_POST["index"];
      if ($type == "hp") {
        $resource = "credits";
        $query = "coreHealth";
        $research = "researchHp";
      } elseif ($type == "shd") {
        $resource = "hyperid";
        $query = "coreShields";
        $research = "researchShd";
      } else {
        echo "error";
        exit();
      }
      $price = ["hp"=>2, "shd"=>3];
      $stats = ["hp"=>1000000, "shd"=>500000];
      require "dbh.inc.php";
      $array = [];
      $sql = mysqli_query($conn, "SELECT userID, $resource FROM users WHERE sessionID='$_SESSION[sid]'");
      if (mysqli_num_rows($sql) > 0) {
        $userID = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT $query FROM userbase WHERE userID=$userID[userID]");
        $userBase = mysqli_fetch_assoc($sql);

        $sql = mysqli_query($conn, "SELECT $research FROM userresearch WHERE userID=$userID[userID]");
        $userResearch = mysqli_fetch_assoc($sql);
        $newPoints = $userBase[$query]+$points;
        $maxPoints = $stats[$type]*($userResearch[$research]*0.1+1);
        if ($newPoints <= $maxPoints) {
          $countPrice = $userID[$resource] - ($points * $price[$type]); // maybe change?
          if ($countPrice < 0) {
            echo "notenougres";
            exit();
          }
          $sql = mysqli_query($conn, "SELECT userID FROM usermovement WHERE targetUserID=$userID[userID] AND missionType=1 AND rounds > 1");
          if (mysqli_num_rows($sql) == 0) {
            $sql = mysqli_query($conn, "UPDATE users SET $resource=$countPrice WHERE userID=$userID[userID]");
            $fix = mysqli_query($conn, "UPDATE userbase SET $query=$newPoints WHERE userID=$userID[userID]");
            if ($sql !== FALSE && $fix !== FALSE) {
              echo "success";
              exit();
            } else {
              echo "error";
              exit();
            }
          } else {
            echo "attack";
            exit();
          }
        } else {
          echo "error";
          exit();
        }
      } else {
        echo "error";
        exit();
      }
    } else {
      echo "error";
      exit();
    }
  } else {
    exit();
  }
 ?>
