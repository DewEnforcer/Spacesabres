<div class="header_left_logo">
  <a href="internalStart.php"><img src="../image/graphics/logo.png" alt="spacesabreslogo"></a>
  <p class="server_time">Server time: <?php  echo date("G:i:s"); ?></p>
</div>
<div class="header_center_res">
  <p>Credits:
  <?php
  require "include/dbh.inc.php";
$SESSION = $_SESSION['sid'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE sessionID='$SESSION'");
$show=mysqli_fetch_assoc($result);
echo "".number_format($show['credits'], '0', '.',' ')."</p>";
 ?>
 <p> Hyperid:
 <?php
echo "".number_format($show['hyperid'], '0', '.',' ')."</p>";
?>
<p> Natium:
<?php
echo "".number_format($show['natium'], '0', '.',' ')."</p>";
?>
<p> Your userID:
<?php
echo "".$show['userID']."</p>"; ?>
</div>

<div class="header_right_buttons">
  <?php
  $sql = mysqli_query($conn, "SELECT viewed FROM usermsg WHERE toUserID=$show[userID] AND viewed=0");
  if (mysqli_num_rows($sql) > 0) {
    echo '<img src="../image/graphics/newmsg.png" alt="newmsgs" style="position:absolute; left:3.5vw;top:-5px;z-index:5;">';
  }
   ?>
<a href="internalInbox.php"><img src="../image/graphics/mail.jpg" alt="inbox"></a>
<a href="internalInfochange.php"><img src="../image/graphics/settingsicon.png" alt="settings" class="settings"></a>
<a href="#"><img src="../image/graphics/supporticon.png" alt="support" class="support"></a>
<a href="include/logout.inc.php"><img src="../image/graphics/logouticon.png" alt="logout" class="logout"></a>
</div>
<section class="navbar">
<nav>
  <ul>
    <?php include "nav.php"; ?>
  </ul>
</nav>
</section>
<a class="attack_alert" href="internalFleets.php">
</a>
<section class="attackdisplay">
  <?php
  $sql = mysqli_query($conn, "SELECT missionType FROM usermovement WHERE userID=$show[userID]");
  $countAttacks = 0;
  $countDefense = 0;
  $countReturns = 0;
  while ($userAttacks = mysqli_fetch_assoc($sql)) {
    if ($userAttacks["missionType"] == 1) {
      $countAttacks += 1;
    } else if ($userAttacks["missionType"] == 2 || $userAttacks["missionType"] == 3) {
      $countReturns += 1;
    }
  }


  $sql = mysqli_query($conn, "SELECT missionType FROM usermovement WHERE targetUserID=$show[userID]");
  while ($userDefense = mysqli_fetch_assoc($sql)) {
    $countDefense += 1;
  }
  echo '<div class="attacks_header"><a href="internalFleets.php"><p id="attack_p">Attacking fleets '.$countAttacks.'</p></a></div>';
  echo '<div class="returns_header"><a href="internalFleets.php"><p id="return_p">Returning fleets '.$countReturns.'</p></a></div>';
  echo '<div class="defense_header"><a href="internalFleets.php"><p id="defense_p">Incoming fleets '.$countDefense.'</p></a></div>';

   ?>
</section>
