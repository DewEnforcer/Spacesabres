<?php
  session_start();
  if (isset($_SESSION["sid"])) {
    if (isset($_POST["index"]) && $_POST["index"] == "shd") {
      require "dbh.inc.php";
      $array = [];
      $sql = mysqli_query($conn, "SELECT userID, hyperid FROM users WHERE sessionID='$_SESSION[sid]'");
      if (mysqli_num_rows($sql) > 0) {
        $userID = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT coreShields FROM userbase WHERE userID=$userID[userID]");
        $userBase = mysqli_fetch_assoc($sql);

        $sql = mysqli_query($conn, "SELECT researchShd FROM userresearch WHERE userID=$userID[userID]");
        $userResearch = mysqli_fetch_assoc($sql);

        $countPoints = round(((500000*($userResearch["researchShd"]*0.1+1))-($userBase["coreShields"] * ($userResearch["researchShd"]*0.1+1)))/3);
        $countMaxShd = (500000*($userResearch["researchShd"]*0.1+1));
        $countJsShd = $countMaxShd - $userBase["coreShields"];
        $sql = mysqli_query($conn, "SELECT attack1 FROM usermovement WHERE targetUserID=$userID[userID]");
        if ($countPoints <= $userID["hyperid"] && mysqli_num_rows($sql) == 0) {
          $newHyperid = $userID["hyperid"] - $countPoints;
          $sql = mysqli_query($conn, "UPDATE users SET hyperid=$newHyperid WHERE userID=$userID[userID]");
          $fixShd = mysqli_query($conn, "UPDATE userbase SET coreShields=$countMaxShd WHERE userID=$userID[userID]");
          if ($sql !== FALSE && $fixShd !== FALSE) {
            $array[0] = $countJsShd;
            print json_encode($array);
            exit();
          } else {
            echo "error";
            exit();
          }
        } else if (mysqli_num_rows($sql) != 0) {
          echo "attack";
          exit();
        } else {
          echo "price";
          exit();
        }
      } else {
        exit();
      }
    } else if (isset($_POST["index"]) && $_POST["index"] == "hull") {
      require "dbh.inc.php";
      $array = [];
      $sql = mysqli_query($conn, "SELECT userID, credits FROM users WHERE sessionID='$_SESSION[sid]'");
      if (mysqli_num_rows($sql) > 0) {
        $userID = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT coreHealth FROM userbase WHERE userID=$userID[userID]");
        $userBase = mysqli_fetch_assoc($sql);

        $sql = mysqli_query($conn, "SELECT researchHp FROM userresearch WHERE userID=$userID[userID]");
        $userResearch = mysqli_fetch_assoc($sql);

        $countPoints = round(((1000000*($userResearch["researchHp"]*0.1+1))-($userBase["coreHealth"] * ($userResearch["researchHp"]*0.1+1)))/2);
        $countMaxShd = (1000000*($userResearch["researchHp"]*0.1+1));
        $countJsShd = $countMaxShd - $userBase["coreHealth"];
        $sql = mysqli_query($conn, "SELECT attack1 FROM usermovement WHERE targetUserID=$userID[userID]");
        if ($countPoints <= $userID["credits"] && mysqli_num_rows($sql) == 0) {
          $newHyperid = $userID["credits"] - $countPoints;
          $sql = mysqli_query($conn, "UPDATE users SET credits=$newHyperid WHERE userID=$userID[userID]");
          $fixShd = mysqli_query($conn, "UPDATE userbase SET coreHealth=$countMaxShd WHERE userID=$userID[userID]");
          if ($sql !== FALSE && $fixShd !== FALSE) {
            $array[0] = $countJsShd;
            print json_encode($array);
            exit();
          } else {
            echo "error";
            exit();
          }
        } else if (mysqli_num_rows($sql) != 0) {
          echo "attack";
          exit();
        } else {
          echo "price";
          exit();
        }
      } else {
        exit();
      }
    }  else {
      exit();
    }
  } else {
    exit();
  }
 ?>
