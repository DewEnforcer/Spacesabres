<?php
/*
require "dbh.inc.php";
$pwd = "oskarjefrajer24";
$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
$sql = mysqli_query($conn, "UPDATE admins SET Password='$hashedPwd' WHERE Username='DewEnforcer'");
if ($sql !== FALSE) {
  echo "success";
} */
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
echo "".getUserIpAddr()."";
 ?>
