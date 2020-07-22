<?php
  if (isset($_POST["username"]) && empty($_POST["username"]) === FALSE) {
  require "dbh.inc.php";
  $search = mysqli_real_escape_string($conn, "%".$_POST["username"]."%");
  $sql = mysqli_query($conn, "SELECT ingameNick, userID FROM users WHERE ingameNick LIKE '$search'");
  $array = []; // stores results
  $index = 0;
  if (mysqli_num_rows($sql) > 0) {
  while ($result = mysqli_fetch_assoc($sql)) {
    $sql1 = mysqli_query($conn, "SELECT pageCoordsX,pageCoordsY, mapLocation, leaderboardPos, rank FROM userfleet WHERE userID=$result[userID]");
    $coords = mysqli_fetch_assoc($sql1);
    $array[$index][0]= $result["ingameNick"];
    $array[$index][1]= $coords["pageCoordsX"];
    $array[$index][2]= $coords["pageCoordsY"];
    $array[$index][3]= $coords["mapLocation"];
    $array[$index][4]= $coords["leaderboardPos"];
    $array[$index][5]= $coords["rank"];
    $sqlimg = "SELECT * FROM profileimg where userid = $result[userID]";
    $resultImg = mysqli_query($conn, $sqlimg);
    $IMG = mysqli_fetch_assoc($resultImg);

          if ($IMG['status'] == 0) {
            $filename = "../uploads/profile".$result["userID"]."*";

            $fileinfo = glob($filename);
            $fileExt = explode(".", $fileinfo[0]);
            $fileActext = $fileExt[3];
            $array[$index][6] = "<img width='95px' src='../uploads/profile".$result["userID"].".".$fileActext."?".mt_rand()."'>";
          } else {
            $array[$index][6] = "<img width='95px' src='../uploads/profileDef.jpg'>";
          }
          $index++;
  }
 print json_encode($array);
  exit();
} else {
  $array[0] = "empty";
  print json_encode($array);
  exit();
}
} else {
  $array[0] = "You haven't entered any username!";
  print json_encode($array);
  exit();
}


 ?>
