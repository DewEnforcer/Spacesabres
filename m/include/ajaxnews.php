<?php
  session_start();
  if (isset($_SESSION["sid"]) && isset($_POST["index"])) {
    require 'dbh.inc.php';
    if ($_POST["index"] == "all") {
      $newsArray = ["newsImage"=>[],"newsTitle"=>[],"newsDesc"=>[]];

      $sql = mysqli_query($conn, "SELECT * FROM news ORDER BY idManual ASC");
      while ($row = mysqli_fetch_assoc($sql)) {
        array_push($newsArray["newsImage"], $row["news_img"]);
        array_push($newsArray["newsTitle"], $row["news_title"]);
        array_push($newsArray["newsDesc"], $row["news_desc"]);
      }

      print json_encode($newsArray);
      exit();
    } else {
      header("location: ../index.php");
      exit();
    }
  } else {
    header("location: ../index.php");
    exit();
  }
 ?>
