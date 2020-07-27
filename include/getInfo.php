<?php 
require "accessSecurity.php";
function evaluateDiplo($diplomacy, $userClan) {
    foreach ($diplomacy as $key => $value) {
       if ($value[0] == $userClan) {
        return $value[1];
       }
    }
    return 0;
}
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
    } else if ($action == "getSystemData" && isset($_POST["data"])) {
        $sql = mysqli_query($conn, "SELECT clanID from userclans WHERE clanTag='$userInfo[userclan]'");
        $userClanID = mysqli_fetch_assoc($sql)["clanID"];
        $data = $_POST["data"];
        if (!is_numeric($data) || $data < 1 || $data > 999) {
            echo "error";
            exit();
        }
        $sql = "SELECT userID, pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE mapLocation=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "error";
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $data);
        mysqli_stmt_execute($stmt);
        $queryRes = mysqli_stmt_get_result($stmt);
        $returnArr = [];
        while ($player = mysqli_fetch_assoc($queryRes)) {
            $isPlayer = 0;
            $sql = mysqli_query($conn, "SELECT ingameNick, userclan FROM users WHERE userID=$player[userID]");
            $pData = mysqli_fetch_assoc($sql);
            if ($player["userID"] == $userInfo["userID"]) {
                $isPlayer = 1;
            }
            $diploStatus = 0;
            if ($pData["userclan"] != "none") {
                $sql = mysqli_query($conn, "SELECT clanID FROM userclans WHERE clanTag='$pData[userclan]'");
                $clanID = mysqli_fetch_assoc($sql)["clanID"];
                if ($clanID != $userClanID) {
                    $sql = mysqli_query($conn, "SELECT clanDiplo FROM userclans WHERE clanID=$clanID");
                    $clanDiplo = unserialize(mysqli_fetch_assoc($sql)["clanDiplo"]);
                    $diploStatus = evaluateDiplo($clanDiplo, $userClanID);
                } else {
                    $diploStatus = 1;
                }
            }
            array_push($returnArr, [$pData["ingameNick"], $player["pageCoordsX"], $player["pageCoordsY"], $player["mapLocation"], $diploStatus, $isPlayer]); 
        }
        echo json_encode($returnArr);
        exit();
    }
}