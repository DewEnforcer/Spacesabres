<?php

  if (isset($_POST["login-submit"])) {

    require "dbh.inc.php";

    $mailuid = $_POST["mailuid"];
    $password = $_POST["pwd"];
    $device = "";
    if (isset($_POST["device"])) {
      $device = $_POST["device"];      
    }
    $header = "";
    switch ($device) {
      case 'mobile':
        $header = "m/";
        break;
    }
    if (empty($mailuid) || empty($password)) {
      header("Location: ../".$header."index.php?error=emptyfields");
      exit();
    }
    else{
      $sql = "SELECT * FROM users WHERE Username=? OR Email=?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../".$header."index.php?error=11");
        exit();
      }
      else {

        mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
          $pwdCheck = password_verify($password, $row["Password"]);
          if ($pwdCheck == false) {
            header("Location: ../".$header."index.php?error=wrongpwd");
            exit();
          }
          else if($pwdCheck == true){
            function checkSids($conn, $randStr) {
              $sql = "SELECT * FROM users";
              $result = mysqli_query($conn, $sql);

              while($row = mysqli_fetch_assoc($result)) {
                if ($row['sessionID'] == $randStr) {
                  $idExists = true;
                  break;
                } else {
                  $idExists = false;
                }
              }

              return $idExists;
            }

            function generateSID($conn) {
              $keyLength = 12;
              $str = "1234567890abcdefghijklmnopqrstuvwxyz";
              $randID = substr(str_shuffle($str), 0 , $keyLength);
              $checkId = checkSids($conn, $randID);

              while ($checkId == true) {
                $randID = substr(str_shuffle($str), 0 , $keyLength);
                $checkId = checkSids($conn, $randID);
              }

              return $randID;
            }
            $SID = generateSID($conn);
            $date = date("U");
            $realIP = $_SERVER['REMOTE_ADDR'];
            $proxyIP = "No proxy IP detected";
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
              $proxyIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            $sql = "UPDATE users SET sessionID='$SID', lastUpdate=$date WHERE userID=$row[userID]";
            $updateSID = mysqli_query($conn, $sql);
            $sql = mysqli_query($conn, "INSERT INTO loginlogs (userID, time, loginIP, loginIPproxy) VALUES ($row[userID], $date, '$realIP', '$proxyIP')");
            session_start();
            $_SESSION["sid"] = $SID;
            header("Location: ../".$header."internalStart.php?login=success");
            exit();
          }
          else {
            header("Location: ../".$header."index.php?error=wrongpwd");
            exit();
          }
        }
        else {
          header("Location: ../".$header."index.php?error=wrongpwd");
          exit();
        }
      }
    }

  }
else {
  header("Location: ../index.php");
  exit();

}
