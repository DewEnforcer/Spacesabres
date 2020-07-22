<?php

if (isset($_POST["button_submit_login"]) && isset($_POST["username"]) && isset($_POST["e-mail"]) && isset($_POST["password"]) && isset($_POST["key"])) {

  function getUserIpAddr(){
      if(!empty($_SERVER['HTTP_CLIENT_IP'])){

          $ip = $_SERVER['HTTP_CLIENT_IP'];
      }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){

          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      }else{
          $ip = $_SERVER['REMOTE_ADDR'];
      }
      return $ip;
  }
// add IP protection after start
  require "dbh.inc.php";

  $email = $_POST["e-mail"];
  $password = $_POST["password"];
  $username = $_POST["username"];
  $key = $_POST["key"];

  if (empty($email) || empty($password) || empty($username) || empty($key)) {
    header("Location: acplogin.php?error=emptyfields");
    exit();
  }
  else {
    $sql = "SELECT * FROM admins WHERE Username=? AND Email=? AND adminKey=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo mysqli_error($conn);
      exit();
    }
    else {

      mysqli_stmt_bind_param($stmt, "sss", $username, $email, $key);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
        $pwdCheck = password_verify($password, $row["Password"]);
        if ($pwdCheck == false) {
          echo $row["Password"]."<br>";
          echo $hashedPwd = password_hash("oskarjefrajer24", PASSWORD_DEFAULT);
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
            $checkId = checkSids($conn, $randStr);

            while ($checkId == true) {
              $randID = substr(str_shuffle($str), 0 , $keyLength);
              $checkId = checkSids($conn, $randID);
            }

            return $randID;
          }

          $SID = generateSID($conn);
          $date = date("U")+1800;
          $sql = "UPDATE admins SET sessionID='$SID', sessionIDExpire=$date WHERE Username= '$row[Username]'";
          $updateSID = mysqli_query($conn, $sql);
          session_start();
          $_SESSION["sidAdmin"] = $SID;

          header("Location: acp.php?login=success");
          exit();
        }
        else {
          header("Location: acplogin.php?error=wrongpwd");
          exit();
        }
      }
      else {
        header("Location: acplogin.php?error=wrongpwd");
        exit();
      }
    }
  }

}
else {
header("Location: apclogin.php?error=filledparams");
exit();

}
 ?>
