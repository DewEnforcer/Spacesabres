<?php
session_start();
$rewardsArray = ["Credit(s)", "Hyperid(s)", "Natium(s)", "Hornet(s)", "Spacefire(s)", "Starhawk(s)", "Peacemaker(s)", "Centurion(s)", "Na-Thalis destroyer(s)", "Level(s) to the weapon systems research", "Level(s) to the hull durability research", "Level(s) to the shield energy capacity research", "Level(s) to the engine and fuel efficiency research"];
$taskDescsPart1 = ["Collect", "Collect", "Collect", "Build", "Build", "Build", "Build", "Build", "Build", "Win", "Win", "Visit <a href=\"internalBase.php\">battlestation center</a>", "Visit <a href=\"internalShipyard.php\">shipyard</a></li>", "Visit <a href=\"internalResearch.php\">Katrips laboratory</a>", "Look around in <a href=\"internalGalaxy.php\">galaxy</a>", "Check your <a href=\"internalInbox.php\">inbox</a>", "Visit your <a href=\"internalFleets.php\">fleet center</a>", "Complete", "Research", "Research", "Research", "Research", "Win", "Stay online for "];
$taskDescsPart2 = ["credit(s)", "hyperid(s)", "natium(s)", "Hornet(s)", "Spacefire(s)", "Starhawk(s)", "Peacemaker(s)", "Centurion(s)", "Na-Thalis destroyer(s)", "battle(s) againts other commanders", "battle(s) againts pirates", "", "", "", "", "", "", "company defense(s)", "level(s) of Weapon system research", "level(s) of Hull durability research", "level(s) of Shield energy capacity research", "level(s) of Engine and fuel efficiency research", "battle(s) againts Xamons", "Hours"];
if (isset($_POST["id"]) && isset($_SESSION["sid"]) && isset($_POST["status"])) {
  require "dbh.inc.php";
  $sql = mysqli_query($conn, "SELECT userID, playTime from users WHERE sessionID='$_SESSION[sid]'");
  if (mysqli_num_rows($sql) == 0) {
        $_SESSION["sid"] = "";
    header("location: ../index.php");
    exit();
  }
  $ID = mysqli_fetch_assoc($sql);
  $session = $_SESSION["sid"];
  $id = $_POST["id"];
  $sql = "SELECT * FROM quests WHERE questID=?";
  $stmt1 = mysqli_stmt_init($conn);
  mysqli_stmt_prepare($stmt1, $sql);
  mysqli_stmt_bind_param($stmt1, "i", $id);
  mysqli_stmt_execute($stmt1);
  $result = mysqli_stmt_get_result($stmt1);
  $questParams = mysqli_fetch_assoc($result);

  $missionObjectives = unserialize($questParams["objectives"]);
  $missionRewards = unserialize($questParams["rewards"]);
  if ($_POST["status"] == "true") {
    $sql = mysqli_query($conn, "SELECT userObjectives, timeLeft FROM userquests WHERE userID=$ID[userID]");
    $userObjectives = mysqli_fetch_assoc($sql);
    $unserializedObj = unserialize($userObjectives["userObjectives"]);
  }
  $i = 0;
  $objectivesFetch = [];
  $rewardsFetch = [];
  $check = 0;
  foreach ($missionObjectives as $key) {
    if ($_POST["status"] == "true") {
      if ($unserializedObj[$i] >= $key) {
        $check++;
      }
      if ($i == 23) {
        $unserializedObj[$i] = floor($unserializedObj[$i]/3600);
      }
    }
    if ($key > 0) {
      if ($_POST["status"] == "true") {
        $constructObjective = "<li>$taskDescsPart1[$i] ".number_format($unserializedObj[$i], '0', '.', ' ')."/".number_format($key, '0', '.', ' ')." $taskDescsPart2[$i]</li>";
        array_push($objectivesFetch, $constructObjective);
      } else {
        $constructObjective = "<li>$taskDescsPart1[$i] ".number_format($key, '0', '.', ' ')." $taskDescsPart2[$i]</li>";
        array_push($objectivesFetch, $constructObjective);
      }
    }
    $i++;
  }
  $i = 0;
  foreach ($missionRewards as $key) {
    if ($key > 0) {
      $constructReward = "<li>".number_format($key, '0', '.', ' ')." $rewardsArray[$i]</li>";
      array_push($rewardsFetch, $constructReward);
    }
    $i++;
  }
  if ($check == 24) {
    $buttons = '<a href="../include/questcancel.php?id='.$id.'">Cancel the mission</a><a style="margin-left: 35px;" href="../include/questRewards.php?id='.$id.'">Claim your rewards</a>';
  } else {
    $buttons = '<a href="../include/questcancel.php?id='.$id.'">Cancel the mission</a>';
  }
  if ($_POST["status"] == "true"){
  if ($userObjectives["timeLeft"] > 0) {
    $push = "<li id='mission_countdown'></li>";
    array_push($objectivesFetch, $push);
  }
} else {
  if ($questParams["timer"] > 0) {
  array_push($objectivesFetch, "<li>You will have ".$questParams["timer"]." hours to finish this mission</li>");
  }
}
$sql = mysqli_query($conn, "SELECT questsCompleted FROM userquests WHERE userID=$ID[userID]");
$questCompleted = mysqli_fetch_assoc($sql);
$arrayFetchMain = ["objectives"=>$objectivesFetch, "rewards"=>$rewardsFetch, "completed"=>$questCompleted["questsCompleted"], "buttons"=>$buttons];
print json_encode($arrayFetchMain);
} else {
  echo "An error has occured ID#11";
  exit();
}
 ?>
