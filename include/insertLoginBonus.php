<?php
require "dbh.inc.php";

//itemID1, itemID2, itemID3, itemID4, item1ammout, item2ammount,item3ammout,item4ammount,day
$array = [[1,2,4,18,100000,25000,50,10000,1], [3,5,10,11,100,20,2,2,2], [10,11,12,13,1,1,1,1,3], [0,0,0,18,0,0,0,30000,4], [0,4,5,6,0,50,25,10,5], [2,7,0,0,30000,5,0,0,6], [1,0,0,0,300000,0,0,0,7], [0,0,0,14,0,0,0,1,8], [4,0,0,0,1000,0,0,0,9], [8,0,0,0,10,0,0,0,10], [1,2,3,0,100000,35000,500,0,11], [17,0,0,0,1,0,0,0,12], [5,0,0,18,50,0,0,10000,13], [7,8,0,0,20,15,0,0,14], [1,0,0,4,150000,0,0,50,15], [3,0,0,13,250,0,0,1,16], [1,2,0,0,250000,50000,0,0,17], [4,7,0,0,100,25,0,0,18], [6,0,0,0,40,0,0,0,19], [15,0,0,0,1,0,0,0,20], [0,0,0,18,0,0,0,50000,21], [8,0,0,0,35,0,0,0,22], [4,5,0,0,75,75,0,0,23], [3,18,9,0,1000,25000,2,0,24]];
$index = 0;
$sql = mysqli_query($conn, "DELETE FROM dailybonus");
while ($index <=23) {
  $item1 = $array[$index][0];
  $item2 = $array[$index][1];
  $item3 = $array[$index][2];
  $item4 = $array[$index][3];
  $item1Ammount = $array[$index][4];
  $item2Ammount = $array[$index][5];
  $item3Ammount = $array[$index][6];
  $item4Ammount = $array[$index][7];
  $day = $array[$index][8];

  $sql = mysqli_query($conn, "INSERT INTO dailybonus (itemID1, itemID2, itemID3, itemID4, item1Ammount, item2Ammount, item3Ammount, item4Ammount,day) VALUES ($item1, $item2, $item3, $item4, $item1Ammount, $item2Ammount, $item3Ammount, $item4Ammount, $day)");
  echo mysqli_error($conn)."<br>";
  $index++;
}

exit();
 ?>
