<?php 
function handleObjectives($conn, $userID, $index) {
    $sql = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$userID");
    $currentQuest = mysqli_fetch_assoc($sql);
        if ($currentQuest["currentQuest"] > 0) {
        $unserializeUser = unserialize($currentQuest["userObjectives"]);
        $sql = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$currentQuest[currentQuest]");
        $objective = mysqli_fetch_assoc($sql);
        $unserializeTemplate = unserialize($objective["objectives"]);
        if ($unserializeTemplate[$index]>0) {
            $unserializeUser[$index] += 1;
            $serialize = serialize($unserializeUser);
            $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
        }
    } 
}
function checkSID($conn) {
    if (isset($_SESSION["sid"])) {
        $session = $_SESSION["sid"];
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE sessionID='$session'");
        if (mysqli_num_rows($sql)>0) {
          return $userInfo = mysqli_fetch_assoc($sql);
        } else {
          session_unset();
          session_destroy();
          header("location: ../index.php?error=10");
          exit();
        }
      } else {
        header("location: ../index.php?error=10");
        exit();
      }
}
require "dbh.inc.php";
session_start();
$userInfo = checkSID($conn);