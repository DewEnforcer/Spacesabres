<header>
  <div class="header_left_box">
    <span id="span_credits"></span>
    <span id="span_hyperids"></span>
    <span id="span_natiums"></span>
    <span id="span_id"></span>
  </div>
  <div class="header_mid_box">
    <img src="../image/graphics/logo.png" alt="spacesabreslogo">
  </div>
  <div class="header_right_box">

  </div>
</header>
<script>
<?php 
  $userValArr = [$userInfo["credits"], $userInfo["hyperid"], $userInfo["natium"], $userInfo["userID"]];
?>
let userValues = <?php echo json_encode($userValArr); ?>;
updateUserValutes(...userValues);
</script>
