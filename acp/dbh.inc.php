<?php

$servername = "localhost";
$dbUsername = "spacesab_admin";
$dbPassword = "oskarjefrajer24";
$dbName = "spacesab_spacesabres";

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

if(!$conn){
  die("Connection failed:".mysqli_connect_error());
}
