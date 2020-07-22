<?php
require "dbh.inc.php";
$id = $_POST["idDelete"];

$deleteUser = mysqli_query($conn, "DELETE FROM users WHERE userID=$id");
$deleteUser1 = mysqli_query($conn, "DELETE FROM userfleet WHERE userID=$id");
$deleteUser2 = mysqli_query($conn, "DELETE FROM userbase WHERE userID=$id");
$deleteUser3 = mysqli_query($conn, "DELETE FROM userresearch WHERE userID=$id");
$deleteUser4 = mysqli_query($conn, "DELETE FROM userquests WHERE userID=$id");
$deleteUser5 = mysqli_query($conn, "DELETE FROM companydefense WHERE userID=$id");
$deleteUser6 = mysqli_query($conn, "DELETE FROM blockedusers WHERE userID=$id");
$deleteUser7 = mysqli_query($conn, "DELETE FROM profileimg WHERE userID=$id");

if ($deleteUser === TRUE && $deleteUser1 === TRUE && $deleteUser2 === TRUE && $deleteUser3 === TRUE && $deleteUser4 === TRUE && $deleteUser5 === TRUE && $deleteUser6 === TRUE && $deleteUser7 === TRUE) {
  header("location: acp.php?success=userdeleted");
}

 ?>
