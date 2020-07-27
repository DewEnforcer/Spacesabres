<?php 
require "accessSecurity.php";
if (isset($_POST["action"])) {
    $action = $_POST["action"];
    if ($action == "getBattleStats") {
        $sql = mysqli_query($conn, "SELECT bStats, destroyedPoints FROM userfleet WHERE userID=$userInfo[userID]");
        $stats = mysqli_fetch_assoc($sql);
        $destructionPoints = $stats["destroyedPoints"];
        $stats = json_decode($stats["bStats"], true);
        array_unshift($stats, $destructionPoints);
        echo json_encode($stats);
    }
}