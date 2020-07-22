<?php
  session_start();
if (isset($_GET["id"]) && isset($_SESSION["sid"])) {
  require "dbh.inc.php";
  $userNick = mysqli_real_escape_string($conn, $_SESSION["sid"]);
       $sql = "SELECT * FROM users WHERE sessionID='$userNick'";
       $getID = mysqli_query($conn, $sql);
       $ID = mysqli_fetch_assoc($getID);
     $getQuests = mysqli_query($conn, "SELECT * FROM userquests WHERE userID=$ID[userID]");
     $currentQuest=mysqli_fetch_assoc($getQuests);
     $currentUserQuests = $currentQuest["currentQuest"];
     if ($currentUserQuests == 0) {
       $_SESSION["mission_result"] = "nomiss";
       header("location: ../internalMissions.php");
      exit();
     }
     else {
     $cancelActive = "UPDATE userquests SET userObjectives='a:24:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;}', currentQuest=0, questAccomplished=0  WHERE userID=$ID[userID]";
     if ($conn->query($cancelActive) === TRUE ){
     $_SESSION["mission_result"] = "successCancel";
     header("location: ../internalMissions.php");
     exit();
     }
     else {
     $_SESSION["mission_result"] =  "sql";
     header("location: ../internalMissions.php");
     exit();
     }
     }



} else {
  $_SESSION["mission_result"] =  "wrongid";
  header("location: ../internalMissions.php");
  exit();
}



 ?>
