<?php
  session_start();
if (isset($_GET["id"]) && isset($_GET["type"])) {
  $_SESSION["lobbyID"] = $_GET["id"];
  $_SESSION["typeLobby"] = $_GET["type"];
  header("location: ../internalBattlelobbies.php");
  exit();
} elseif (isset($_GET["shipID"]) && isset($_GET["shipType"])) {
  $_SESSION["shipEquipmentID"] = $_GET["shipID"];
  $_SESSION["shipEquipmentType"] = $_GET["shipType"];
  $_SESSION["dockNumber"] = $_GET["dock"];
  header("location: ../internalEquipment.php");
  exit();
} elseif (isset($_GET["action"]) && isset($_GET["coordsX"])  && isset($_GET["coordsY"])  && isset($_GET["map"])) {
  $_SESSION["coordsX"] = $_GET["coordsX"];
  $_SESSION["coordsY"] = $_GET["coordsY"];
  $_SESSION["map"] = $_GET["map"];
  header("location: ../internalBriefing.php");
} else {
  header("location: index.php");
  exit();
}
 ?>
