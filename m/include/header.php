<img class="navbar_btn" src="../image/graphics/navbarbtn.png" alt="navbar button">
<a href="internalStart.php" class="logo_a"><img class="logo_header" src="../image/graphics/logo.png" alt="spacesabreslogo"></a>
<div class="resources_header">
  <p>Credits:
  <?php
  require "include/dbh.inc.php";
$SESSION = $_SESSION['sid'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE sessionID='$SESSION'");
$show=mysqli_fetch_assoc($result);
echo "".number_format($show['credits'], '0', '.',' ')."</p><hr>";
 ?>
 <p> Hyperid:
 <?php
echo "".number_format($show['hyperid'], '0', '.',' ')."</p><hr>";
?>
<p> Natium:
<?php
echo "".number_format($show['natium'], '0', '.',' ')."</p><hr>";
?>
<p> Your userID:
<?php
echo "".$show['userID']."</p>"; ?>
</div>
