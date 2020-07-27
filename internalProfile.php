<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="../css/stylegame.css">
    <link rel="stylesheet" href="../css/styleProfile.css">
    <?php require "include/scripts.php" ?>
    <script src="./js/profile.js"></script>
    <title>SpaceSabres||Profile</title>
  </head>
  <body>
        <?php require "include/header.php"; ?>
    <main>
      <?php
      require "include/bars.php";
      if (isset($_GET["error"])) {
        if ($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "toobig") {
          echo ' <div class="popup_result">
              <p>Your file size is too big to upload!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "sizetoobig") {
          echo ' <div class="popup_result">
              <p>Your file dimensions size is too big! Maximum is 200x200 pixels.</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"] == "uploadfail") {
          echo ' <div class="popup_result">
              <p>The upload has failed.</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"] == "wrongext") {
          echo ' <div class="popup_result">
              <p>You cannot upload files with this extension '.$_GET["ext"].'!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
      if (isset($_GET["success"])) {
        if ($_GET["success"] == 1) {
        echo '<div class="popup_result">
            <p>Your avatar has been successfully deleted!</p>
            <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
          </div>';
        } elseif ($_GET["success"] == "upload") {
          echo '<div class="popup_result">
              <p>The picture has been successfully uploaded!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
      $position = 1;
      $sql = mysqli_query($conn, "SELECT userID FROM userfleet ORDER BY destroyedPoints DESC LIMIT 0,10");
      while ($row = mysqli_fetch_assoc($sql)) {
          if ($row["userID"] == $userInfo["userID"]) {
          break;
          }
        $position++;
      }
      $sql = mysqli_query($conn, "SELECT * FROM profileimg where userid = $userInfo[userID]");
      $searchedProfilePic = mysqli_fetch_assoc($sql);
      $sql = mysqli_query($conn, "SELECT rank FROM userfleet WHERE userID=$userInfo[userID]");
      $rank = mysqli_fetch_assoc($sql)["rank"];
       ?>
      <section class="profile_main_container">
        <div class="profile_header_wrapper">
          <a href="#" class="btn_profile_abs">Battle Statistics</a>
        </div>
        <section class="profile_box_main">
          <div class="profile_user_img">
            <h2><?php echo $userInfo["ingameNick"]; ?></h2>
            <hr style="width: 100%; margin-bottom: 2vh;">
            <?php
            $rankArr = ["Unknown", "Ensign", "Basic Lieutenant", "Lieutenant", "Lieutenant Commander", "Commander", "Captain", "Rear Admiral", "Vice Admiral", "Admiral", "Fleet Admiral", "Administrator"];
            if ($searchedProfilePic['status'] == 0) {
              $filename = "uploads/profile".$userInfo["userID"]."*";
              $fileinfo = glob($filename);
              $fileExt = explode(".", $fileinfo[0]);
              $fileActext = $fileExt[1];

              echo "<div><img class='img_profile' src='./uploads/profile".$userInfo["userID"].".".$fileActext."?".mt_rand()."'></div>";
            } else {
              echo "<div><img class='img_profile' src='./uploads/profileDef.jpg'></div>";
            }
             ?>
             <form action="include/imgUpload.php" class="profile_upload_form" method="post" enctype="multipart/form-data">
              <h3>Change your profile picture</h3>
              <input type="file" name="img" placeholder="IMG" >
              <button type="submit" name="submitbutton">Upload</button>
            </form>
          </div>
          <div class="profile_user_info">
            <h2>User Information</h2>
            <?php
            echo '<label class="rank">Rank: '.$rankArr[$rank]. ' <img style="width: 16px;" src="../image/ranks/rank'.$rank.'.png" class="rankImg"></label>';
            echo '<label><a href="internalUsersLeaderboard.php">Leaderboard position: '.$position.'</a></label>';
            echo '<label>Alliance: '.$userInfo["userclan"].'</label>';
            echo '<label>Registration date: '.date('Y-m-d G:i:s', $userInfo["regDate"]).'</label>';
             ?>
          </div>
        </section>
        <section class="profile_change_info_wrapper">
          <div class="profile_img_reset">
            <form action="include/deleteAvatar.php" class="profile_delete_form" method="post">
              <h3>Reset your profile picture</h3>
              <button type="submit" name="deletebutton">Reset</button>
            </form>
          </div>
          <div class="profile_info_change">
            <a href="internalInfochange.php">Change your account information!</a>
          </div>
        </section>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
