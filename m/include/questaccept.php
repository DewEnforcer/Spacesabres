<?php
  session_start();
  require "dbh.inc.php";
  if(isset($_GET["send"]) && isset($_GET["questID"]) && isset($_SESSION["sid"])) {
    $userNick = mysqli_real_escape_string($conn,$_SESSION['sid']);
    $questID = mysqli_real_escape_string($conn,$_GET["questID"]);
    $getQuests = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$userNick'");
    $id=mysqli_fetch_assoc($getQuests);
    $getQuests1 = mysqli_query($conn, "SELECT * FROM userquests WHERE userID=$id[userID]");
    $currentQuest = mysqli_fetch_assoc($getQuests1);
    $sql = mysqli_query($conn, "SELECT timer FROM quests WHERE questID=$questID");
    $time = mysqli_fetch_assoc($sql);
    if ($time["timer"]>0) {
    $setTime = date("U")+($time["timer"]*60*60);
  } else {
    $setTime = 0;
  }
    $currentUserQuests = $currentQuest["currentQuest"];
    if ($currentUserQuests>0 || $questID <= $currentQuest["questsCompleted"] || $questID>=$currentQuest["questsCompleted"]+2) {
      $_SESSION["mission_result"] = "alreadyon";
      header("Location: ../internalMissions.php"); // error1 = quest already selected
      exit();
    }
    else {
    $setQuest = "UPDATE userquests SET currentQuest=$questID, timeLeft=$setTime WHERE userID=$id[userID]";


    if ($conn->query($setQuest) === TRUE){
      $_SESSION["mission_result"] = "successAccepted";
      header("location: ../internalMissions.php");
      exit();
    }
    else {
    $_SESSION["mission_result"] = "sql";
    header("Location: ../internalMissions.php");
    exit();
    }
}


} else {
  header("location: ../index.php");
  exit();
}
 ?>
