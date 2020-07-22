<?php
session_start();
if (isset($_GET["action"]) && $_GET["action"] == "activate" && isset($_GET["id"])) {

  $id = $_GET["id"];
  if (empty($id)) {
    $_SESSION["activateacc"] = "link";
    header("location: index.php");
    exit();
  }

  $currentDate = date("U");

  require "include/dbh.inc.php";

  $sql = "SELECT * FROM notactiveusers WHERE idActivate=? AND idExpire>=?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    $_SESSION["activateacc"] = "sql";
    header("location: index.php");
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "si", $id, $currentDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$row = mysqli_fetch_assoc($result)) {
      $_SESSION["activateacc"] = "tokentimeerror";
      header("location: index.php?");
      exit();
    } else {
      $sql = mysqli_query($conn, "SELECT COUNT(userID) as numberofplayers FROM users");
      $usersAmmmount = mysqli_fetch_assoc($sql);
      $usersAmmmount["numberofplayers"] += 1;
      $insertUsers = mysqli_query($conn, "INSERT INTO users (userID, Username, ingameNick, Email, Password, regDate, registerIPreal, registerIPproxy, newsletter) VALUES ($row[userID], '$row[Username]', '$row[Username]', '$row[Email]', '$row[Password]', $row[regDate] , '$row[IPreal]', '$row[IPproxy]', $row[newsletter])");
      if ($insertUsers !== FALSE) {
        $insertFleet = mysqli_query($conn, "INSERT INTO userfleet (userID, pageCoordsX, pageCoordsY, mapLocation, leaderboardPos) VALUES ($row[userID], $row[coordsX], $row[coordsY], $row[mapLocation], $usersAmmmount[numberofplayers])");
        $docks = 'a:0:{}';
        $compress = gzencode($docks,9);
        $sql = "UPDATE userfleet SET dock1=?, dock2=?, dock3=?, dock4=?, dock5=?, dock6=?, dock7=?, dock8=?, dock9=?, dock10=?, templates=?, temporaryDock=? WHERE userID=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssssssi", $compress, $compress, $compress, $compress, $compress, $compress, $compress, $compress, $compress, $compress, $compress, $compress, $row["userID"]);
        mysqli_stmt_execute($stmt);
        $insertBase = mysqli_query($conn, "INSERT INTO userbase (userID) VALUES ($row[userID])");
        $insertResearch = mysqli_query($conn, "INSERT INTO userresearch (userID) VALUES ($row[userID])");
        $insertMissions = mysqli_query($conn, "INSERT INTO userquests (userID) VALUES ($row[userID])");
        $insertProfile = mysqli_query($conn, "INSERT INTO profileimg (userid, status) VALUES ($row[userID], 1)");

        if ($insertBase !== FALSE && $insertResearch !== FALSE && $insertMissions !== FALSE) {
          $sql = mysqli_query($conn, "DELETE FROM notactiveusers WHERE userID=$row[userID]");
          $date = date("U");
          $subject = "Welcome commander!";
          $msg = "<h2>Hi $row[Username]!</h2>";
          $msg .= "<p style=\"text-align: center;\">Welcome to the galaxy of spacesabres, galaxy ready to be explored by fearless commanders like you!</p>";
          $msg .= "<p style=\"text-align: center;\">Now, let me quickly give you some tips, before you head off to the adventures of spacesabres.</p>";
          $msg .= "<p style=\"text-align: center;\">Your battlestation core , is the centre that manages all your fleet movement, if your core is destroyed, you will not be able to explore the galaxy with your fleet!</p>";
          $msg .= "<p style=\"text-align: center;\">Of course, you can repair your core hull, by visiting core center which can be found under \"Battlestation\" in your navigation.</p>";
          $msg .= "<p style=\"text-align: center;\">If you want to be more agressive, you can visit briefing and select your fleet for the engagement! Though remember, you need yours target coordinates in order to send the attack!</p>";
          $msg .= "<h3>Of course , there is much more for you to explore, i would recommend you checking out ours tips <a style=\"color: white;\"href=\"./forum/index.php\">>Here<</a>.</h3>";
          $msg .= "<p style=\"text-align: center;\">Okay, this is all from me ... oh nevermind, i almost forgot to give you a gift , just claim it by clicking on the button below! .. and don\'t forget to check out missions before heading out into the galaxy!</p>";
          $msg .= "<a href=\"./include/giftHandler.php\" style=\"align-self: center; padding: 3px; background-color: rgb(40,40,40); cursor: pointer; border: 2px solid white; font-family: \"Kanit\", sans-serif; color: white; margin: 5px 0; text-decoration: none;\" class\"btn_claim_gift\">Claim the gift!</a>";
          $msg .= "<p style=\"text-align: center;\">Good luck out there commander!</p>";
          $token = bin2hex(random_bytes(10));
          $sql = mysqli_query($conn, "INSERT INTO usermsg (toUserID, fromUserID, sentTime, subject, msg, token) VALUES ($row[userID], 1, $date, '$subject', '$msg', '$token')");
          $_SESSION["activateacc"] = "success";
          header("location: index.php");
          exit();
        } else {
          $_SESSION["activateacc"] = "sql";
          header("location: index.php");
          exit();
        }
      } else {
        $_SESSION["activateacc"] = "sql";
        header("location: index.php");
        exit();
      }
    }


  }

} else {
  header("location: index.php");
  exit();
}

 ?>
