<?php
if(isset($_POST["signup-submit"])){

  require 'dbh.inc.php';

  $username = $_POST["uid"];
  $email = $_POST["mail"];
  $pwd = $_POST["pwd"];
  $pwdRepeat = $_POST["pwd-repeat"];
  $tosAgreement = $_POST["tos_agreement"];
  if ($tosAgreement != "true") {
    header("Location: ../index.php?error=tos");
    exit();
  }

  if (empty($username) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
    header("Location: ../index.php?error=emptyfields&uid=".$username."&mail=".$email);
    exit();
  }
  else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../index.php?error=invalidmailuid=.$username");
    exit();
  }

  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header("Location: ../index.php?error=invalidmail&uid=".$username);
  exit();
  }

  elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
  header("Location: ../index.php?error=invalidusername&mail=".$email);
  exit();
  }

  elseif ($pwd !== $pwdRepeat){
    header("Location: ../index.php?error=passwordcheck&uid=".$username."&mail=".$email);
    exit();
  } elseif (strlen($pwd)<6) {
    header("Location: ../index.php?error=passwordlength&uid=".$username."&mail=".$email);
    exit();
  }
  else {

    $sql = "SELECT Username FROM users WHERE Username=? OR Email=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../index.php?error=11");
      exit();

    }
    else {
      mysqli_stmt_bind_param($stmt, "ss", $username, $email);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      if ($resultCheck > 0) {
        header("Location: ../index.php?error=usertaken&mail=".$email);
        exit();
      }
      else {

        $sql = "SELECT Username FROM notactiveusers WHERE Username=? OR Email=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../index.php?error=".mysqli_error($conn)."");
          exit();

        }
        else {
          mysqli_stmt_bind_param($stmt, "ss", $username, $email);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);
          $resultCheck = mysqli_stmt_num_rows($stmt);
          if ($resultCheck > 0) {
            header("Location: ../index.php?error=usertaken&mail=".$email);
            exit();
          }
          else {

            function checkForSunY ($varToCheck) {
              if ($varToCheck <= 300) {
                if ($varToCheck >= 150) {
                  $result = false;
                } else {
                  $result = true;
                }
              }else {
                $result = true;
              }
              return $result;
            }
            function checkForSunX ($varToCheck) {
              if ($varToCheck <= 350) {
                if ($varToCheck >= 500) {
                  $result = false;
                } else {
                  $result = true;
                }
              } else {
                $result = true;
              }
              return $result;
            }
            function checkCoords($conn, $randX, $randY, $maximalMap) {
              if ($randX <770 && $randY < 400 && checkForSunX($randX) == true && checkForSunY($randY) == true ) {
              $sql = "SELECT * FROM userfleet WHERE mapLocation='$maximalMap'";
              $result = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_assoc($result)) {
                if ($row['pageCoordsX'] == $randX || $row['pageCoordsY'] == $randY) {
                  $coordExists = true;
                  break;
                } else {
                  if ($randX < $row["pageCoordsX"]-35 || $randX > $row["pageCoordsX"]+35) {
                    if ($randY < $row["pageCoordsY"]-35 || $randY > $row["pageCoordsY"]+35) {
                      $coordExists = false;
                    } else {
                      $coordExists = true;
                      break;
                    }
                  } else {
                    $coordExists = true;
                    break;
                  }
                }
              }
            } else {
              $coordExists = true;
              return $coordExists;
            }
            }

            function generateCoords($conn, $maximalMap) {
              $keyLength = 3;
              $str = "1234567890";
              $randX = substr(str_shuffle($str), 0 , $keyLength);
              $randY = substr(str_shuffle($str), 0 , $keyLength);
              $checkCoords= checkCoords($conn, $randX, $randY, $maximalMap);

              while ($checkCoords == true) {
                $randX = substr(str_shuffle($str), 0 , $keyLength);
                $randY = substr(str_shuffle($str), 0 , $keyLength);
                $checkCoords = checkCoords($conn, $randX, $randY, $maximalMap);
              }
              $coords = [$randX+30, $randY+30];
              return $coords;
            }

        function generateMap($conn) {
          $checkMap = checkMap($conn);
          while ($checkMap == false) {
            $checkMap = checkMap($conn);
          }
          return $checkMap;
        }

        function checkMap($conn) {

          $randomMap = rand(1,999);

          $getMaxMap = mysqli_query($conn, "SELECT MAX(userID) as players FROM userfleet WHERE mapLocation=$randomMap");
          $maxMap =  mysqli_fetch_assoc($getMaxMap);

          if ($maxMap["players"] < 10 && $maxMap["players"] != 0) {
            return $randomMap;
          } else {
            if ($maxMap["players"] >= 10) {
              return false;
            } else if ($maxMap["players"] == 0) {
              $sql = mysqli_query($conn, "SELECT MAX(mapLocation) as maxMap FROM userfleet");
              $maxMapPlayers = mysqli_fetch_assoc($sql);
              if ($maxMapPlayers["maxMap"] + 50 < $randomMap || $randomMap - 50 > $maxMapPlayers["maxMap"]) {
                return false;
              } else {
                return $randomMap;
              }
            }
          }
        }

        $maximalMap = generateMap($conn);
        $realIP = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $proxyIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
          $proxyIP = "No proxy IP detected.";
        }

        $sql = "INSERT INTO notactiveusers (Username, Email, Password, userID, regDate, idActivate, idExpire, coordsX, coordsY, mapLocation, IPreal, IPproxy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../index.php?error=".mysqli_error($conn)."");
          exit();

      }
      else {
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        function checkIds($conn, $randID) {
          $sql = "SELECT * FROM users";
          $result = mysqli_query($conn, $sql);

          while($row = mysqli_fetch_assoc($result)) {
            if ($row['userID'] == $randID) {
              $idExists = true;
              break;
            } else {
              $idExists = false;
            }
          }

          return $idExists;
        }

        function generateID($conn) {
          $keyLength = 8;
          $str = "1234567890";
          $randID = substr(str_shuffle($str), 0 , $keyLength);
          $checkId = checkIds($conn, $randID);

          while ($checkId == true) {
            $randID = substr(str_shuffle($str), 0 , $keyLength);
            $checkId = checkIds($conn, $randID);
          }

          return $randID;
        }

        $generatedID = generateID($conn);
        $date = date("U");
        $expireDate = $date + 86400;
        $id = bin2hex(random_bytes(16));
        $coordsPage = generateCoords($conn, $maximalMap);
        mysqli_stmt_bind_param($stmt, "sssiisiiiiss", $username, $email, $hashedPwd, $generatedID, $date, $id, $expireDate, $coordsPage[0], $coordsPage[1], $maximalMap, $realIP, $proxyIP);
        $regAcc = mysqli_stmt_execute($stmt);

        $url = "https://spacesabres.com/accountActivate.php?action=activate&id=$id";
        $to = $email;
        $subject = 'Activate your Spacesabres account!';

        $message  = '<p>Hi '.$username.' ,</p>';
        $message .= '<p>Thank you for joining our game! To activate your account and confirm that this is your email adress, click on "Activate here!" below.</p>';
        $message .= "<p>Did not register? Just ignore this e-mail, you won't receive any more e-mails from us!</p>";
        $message .= '<p>Here is your account activation link: </br>';
        $message .= '<a href="' . $url . '">Activate here!</a></p>';
        $message .= '<p>Good luck out there commander!</p>';

         $headers = "From: Spacesabres <noreply@spacesabres.com>\r\n";
        $headers .= "Content-type: text/html\r\n";

        mail($to, $subject, $message, $headers);




        if ($regAcc !== FALSE) {
          header("location: ../index.php?success=1");
          exit();
        } else {
          header("location: ../index.php?error");
          exit();
        }


        }
      }
      }
    }
    }
    }
    }
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
