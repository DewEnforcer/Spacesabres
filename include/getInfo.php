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
        exit();
    } else if ($action == "searchPlayer" && isset($_POST["data"])) {
        $returnArr = [];
        $data = mysqli_real_escape_string($conn, "%".$_POST["data"]."%");
        $sql = "SELECT ingameNick, userID FROM users WHERE ingameNick LIKE ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "error";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $data);
        mysqli_stmt_execute($stmt);
        $queryRes = mysqli_stmt_get_result($stmt);
        while ($player = mysqli_fetch_assoc($queryRes)) {
            $sql = mysqli_query($conn, "SELECT rank, leaderboardPos, pageCoordsX,pageCoordsY, mapLocation FROM userfleet WHERE userID=$player[userID]");
            $playerFleetInfo = mysqli_fetch_assoc($sql);
            array_unshift($playerFleetInfo, $player["ingameNick"]);
            array_push($returnArr, $playerFleetInfo);
        }
        echo json_encode($returnArr);
        exit();
    }
}