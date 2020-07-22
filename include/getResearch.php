<?php
session_start();
if (isset($_POST["index"]) && $_POST["index"] === "get_res" && isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $session = $_SESSION["sid"];

  $sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql) > 0 && $sql !== FALSE) {
    $ID = mysqli_fetch_assoc($sql);
    $convertID = intval($ID["userID"]);
    $sql = mysqli_query($conn, "SELECT researchTime FROM userresearch WHERE userID=$convertID");
    $getResTime = mysqli_fetch_assoc($sql);

    $ResTime = date("Y-m-d G:i:s", intval($getResTime["researchTime"]));
    echo $ResTime;
    exit();
  } else {
      $_SESSION["sid"] = "";
    header("Location: ../index.php");
    exit();
  }

} else {
  echo "An error has occured! ID#11";
  exit();
}
 ?>
