<?php 
require "accessSecurity.php";
if (isset($_POST["item"]) && isset($_POST["amount"])) {
    $itemID = $_POST["item"];
    $amount = $_POST["amount"];
    if (!is_numeric($itemID) || !is_numeric($amount)) {
        echo "error";
        exit();
    }
    $shipData = json_decode(file_get_contents("../js/shipInfo.json"), true)["ships"];
    if (!isset($shipData[$itemID])) {
        echo "item_not_avail";
        exit();
    }
    $shipData = $shipData[$itemID];
    $userInfo["credits"] -= $shipData["price"]["credits"] * $amount;
    $userInfo["hyperid"] -= $shipData["price"]["hyperid"] * $amount;
    $userInfo["natium"] -= $shipData["price"]["natium"] * $amount;
    if ($userInfo["credits"] < 0 || $userInfo["hyperid"] < 0 || $userInfo["natium"] < 0) {
        echo "no_res";
        exit();
    }//fetch user ships
    $sql = mysqli_query($conn, "SELECT fleet FROM userfleet WHERE userID=$userInfo[userID]");
    $fleet = json_decode(mysqli_fetch_assoc($sql)["fleet"], true);
    $fleet[$itemID-1] += $amount; //add the ships
    $fleet = json_encode($fleet);
    //save the new values (ships, valutes)
    $updateValute = mysqli_query($conn, "UPDATE users SET credits=$userInfo[credits], hyperid=$userInfo[hyperid], natium=$userInfo[natium] WHERE userID=$userInfo[userID]");
    if (!$updateValute) {
        echo "error";
        exit();
    }
    $updateFleet = mysqli_query($conn, "UPDATE userfleet SET fleet='$fleet' WHERE userID=$userInfo[userID]");
    if (!$updateFleet) {
        echo "error";
        exit();
    }
    echo json_encode([$userInfo["credits"], $userInfo["hyperid"], $userInfo["natium"]]);
    exit();
} else {
    echo "error";
    exit();
}