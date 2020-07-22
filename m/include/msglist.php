<?php
session_start();
if (isset($_SESSION["sid"]) && isset($_POST["list"])) {
require "dbh.inc.php";
$session = $_SESSION["sid"];
$sql = mysqli_query($conn, "SELECT userID FROM users WHERE sessionID='$session'");
$show = mysqli_fetch_assoc($sql);
$sql=mysqli_query($conn, "SELECT * FROM usermsg WHERE toUserID=$show[userID] ORDER BY sentTime DESC");
$check = mysqli_num_rows($sql);
echo '<div class="inbox_container_main_header"><div class="selected" style="cursor:auto;">
  <button name="delete" class="button-selected" id="delete" href="#">Delete all selected messages</button>
  <button name="mark" class="button-selected" id="mark" href="#">Mark all selected messages as seen</button>
</div><div class="allMsgs" style="cursor:auto;">
  <button name="deleteall" class="button-all" id="deleteAll" href="#">Delete all messages</button>
  <button name="markall" class="button-all button_mark_all" id="markAll" href="#">Mark all messages as seen</button>
</div></div>';
if ($check > 0){
  $index = 1;
while ($getMsg = mysqli_fetch_assoc($sql)) {
  $num_length = strlen((string)$getMsg["fromUserID"]);
  if ($getMsg["fromUserID"] == 1 ) {
    $system = "System";
  }
  if ($num_length == 10) {
    $from["ingameNick"] = "Alliance";
  } else {
    $sql1 = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$getMsg[fromUserID]");
    $from = mysqli_fetch_assoc($sql1);
  }
  if($getMsg["viewed"] == 0) {
    $color = "orange";
  } elseif ($getMsg["viewed"] == 1) {
    $color = "grey";
  }
  echo"<div class='msg_wrapper'><div class='msg_inbox_left'><input type='checkbox' name='check$index' value='$index' id='checkbox$index' class='checkbox'><img src='../image/graphics/deletemsg.png' width='20px'alt='msgdelete' class='image' id='image".$index."'></div><div class='msg' id=".$index." style='background-color:$color'>
  <p class='from' id='from".$index."' id=".$index.">From: ";
  if ($getMsg["fromUserID"] == 1 ) {
      $system = "System"; echo $system;
    } else {
      echo $from["ingameNick"];
    }
    echo "</p>
  <p class='subject' id=".$index.">Subject: ".$getMsg["subject"]."</p>
  <p class='date' id=".$index.">Sent on: ".date("Y-m-d G:i:s", $getMsg["sentTime"])."</p>
  <input type='hidden' id='randstr".$index."' value='$getMsg[token]'</a></div>";
  echo "</div>";
  $index++;
}
} else {
  echo "<p class='nomsg'>You have no messages!</p>";
}} else {
  echo "An error has occured ID#12";
}
?>
