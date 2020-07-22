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
<link rel="stylesheet" href="../css/styleBase.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="../js/gameinfo.js"></script>
    <script src="../js/search-player.js"></script>
    <script src="../js/backgroundmanager.js"></script>
    <script src="../js/coreManagement.js"></script>
    <title>SpaceSabres||BattleStation</title>
    <script>
      $(document).ready(function() {
        $(".button_help_base").click(function(openTips) {
          $("main").append("<div class='base_help_box_content'></div>");
          let section = $(".button_help_base").attr("id");
          $.getJSON("../js/game_text_en.json", function(jsonLang) {
        //    $("base_help_box_content").fadeIn(function() {
            $(".base_help_box_content").css("display", "flex");
            $(".base_help_box_content").html(`<img src="../image/graphics/closeMsg.png" class="close_base_tips"><h2 style="color: white;">BattleStation tips</h2><p>${jsonLang[section]["help_text"]}</p><h3>${jsonLang[section]["remember"]}</h3>`);
          //  }, 1000);
        });
        });
        $(document).on("click", ".close_base_tips", function() {
            $(".base_help_box_content").remove();
            $(".base_help_box_content").css("display", "none");
        });
      });
    </script>
  </head>
  <body>

      <header>
<?php  
require "include/header.php";
$getModules = mysqli_query($conn, "SELECT * FROM userbase WHERE userID=$show[userID]");
$modules = mysqli_fetch_assoc($getModules);
$userBase = ["coreHealth"=>$modules["coreHealth"],"coreShields"=>$modules["coreShields"]];
$sql = mysqli_query($conn, "SELECT researchHp, researchShd FROM userresearch WHERE userID=$show[userID]");
$userResearch = mysqli_fetch_assoc($sql);
handleObjectives($conn, $show["userID"], 11);
 ?>
  </header>

    <main>
      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "notenoughmodules") {
          echo ' <div class="popup_result">
              <p>You don´t have enough modules in your inventory to set all the selected modules!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
      if (isset($_GET["success"])) {
        if ($_GET["success"] == "moduleset") {
        echo '<div class="popup_result">
            <p>The modules have been successfully set!</p>
            <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
          </div>';
        }
      }
       ?>
      <section class="searchPopup">

      </section>
      <section class="base_container">
        <div class="top_container_base_title">
          <h2 align="center">Battlestation overview</h2>
        </div>

        <form class="modules_form" action="include/modulesHandler.php" method="post">
          <div class="modules">
            <div class="core">
              <img src="../image/graphics/core.png" alt="core" class="core">
              <div class="hp_wrapper">
                <?php
                echo "<span class='base_hp_txt' points=".$userBase["coreHealth"].">".number_format($userBase["coreHealth"], '0', '.',' ')."</span>";
                $hpWidth = ((($userBase["coreHealth"]))/(1000000 * ($userResearch["researchHp"]*0.1+1))*100);
                $shdWidth = (($userBase["coreShields"])/(500000 * ($userResearch["researchShd"]*0.1+1))*100);
                ?>
                <div class="hp_val" value="<?php echo $userBase["coreHealth"]; ?>" style="width: <?php echo $hpWidth; ?>%;">

                </div>
              </div>
              <div class="shd_wrapper">
                <?php
                echo "<span class='base_shd_txt' points=".$userBase["coreShields"].">".number_format($userBase["coreShields"], '0', '.',' ')."</span>";
                 ?>
                <div class="shd_val" value="<?php echo $userBase["coreShields"]; ?>" style="width: <?php echo $shdWidth ?>%;">

                </div>
              </div>
            </div>
          <?php if($modules["slot1"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot1"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot1"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot1"] == 4) {$moduleName= "M-S01";}elseif($modules["slot1"] == 0) {$moduleName= "Empty slot";} ?>
          <div class="module1 module_select"><h3>Module slot 1</h3><img src="../image/graphics/module<?php echo $modules["slot1"] ?>.png" alt="module"><br><select name="module1" >
          <option value="<?php echo $modules["slot1"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot2"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot2"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot2"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot2"] == 4) {$moduleName= "M-S01";}elseif($modules["slot2"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module2 module_select"><h3>Module slot 2</h3><img src="../image/graphics/module<?php echo $modules["slot2"] ?>.png" alt="module"><br><select name="module2" >
        <option value="<?php echo $modules["slot2"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot3"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot3"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot3"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot3"] == 4) {$moduleName= "M-S01";}elseif($modules["slot3"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module3 module_select"><h3>Module slot 3</h3><img src="../image/graphics/module<?php echo $modules["slot3"] ?>.png" alt="module"><br><select name="module3" >
        <option value="<?php echo $modules["slot3"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot4"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot4"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot4"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot4"] == 4) {$moduleName= "M-S01";}elseif($modules["slot4"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module4 module_select"><h3>Module slot 4</h3><img src="../image/graphics/module<?php echo $modules["slot4"] ?>.png" alt="module"><br><select name="module4" >
        <option value="<?php echo $modules["slot4"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot5"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot5"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot5"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot5"] == 4) {$moduleName= "M-S01";}elseif($modules["slot5"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module5 module_select"><h3>Module slot 5</h3><img src="../image/graphics/module<?php echo $modules["slot5"] ?>.png" alt="module"><br><select name="module5" >
        <option value="<?php echo $modules["slot5"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot6"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot6"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot6"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot6"] == 4) {$moduleName= "M-S01";}elseif($modules["slot6"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module6 module_select"><h3>Module slot 6</h3><img src="../image/graphics/module<?php echo $modules["slot6"] ?>.png" alt="module"><br><select name="module6" >
        <option value="<?php echo $modules["slot6"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot7"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot7"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot7"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot7"] == 4) {$moduleName= "M-S01";}elseif($modules["slot7"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module7 module_select"><h3>Module slot 7</h3><img src="../image/graphics/module<?php echo $modules["slot7"] ?>.png" alt="module"><br><select name="module7" >
        <option value="<?php echo $modules["slot7"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot8"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot8"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot8"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot8"] == 4) {$moduleName= "M-S01";}elseif($modules["slot8"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module8 module_select"><h3>Module slot 8</h3><img src="../image/graphics/module<?php echo $modules["slot8"] ?>.png" alt="module"><br><select name="module8" >
        <option value="<?php echo $modules["slot8"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot9"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot9"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot9"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot9"] == 4) {$moduleName= "M-S01";}elseif($modules["slot9"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module9 module_select"><h3>Module slot 9</h3><img src="../image/graphics/module<?php echo $modules["slot9"] ?>.png" alt="module"><br><select name="module9" >
        <option value="<?php echo $modules["slot9"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
        <br>
        <?php if($modules["slot10"] == 1) {$moduleName= "L-L01";} elseif ($modules["slot10"] == 2) {$moduleName= "H-L01";}  elseif ($modules["slot10"] == 3) {$moduleName= "M-RS01";} elseif ($modules["slot10"] == 4) {$moduleName= "M-S01";}elseif($modules["slot10"] == 0) {$moduleName= "Empty slot";} ?>
        <div class="module10 module_select"><h3>Module slot 10</h3><img src="../image/graphics/module<?php echo $modules["slot10"] ?>.png" alt="module"><br><select name="module10">
        <option value="<?php echo $modules["slot10"]; ?>"><?php echo $moduleName; ?></option>
          <option value="1">L-L01</option>
          <option value="2">H-L01</option>
          <option value="3">M-RS01</option>
          <option value="4">M-S01</option>
        </select>
      </div>
    </div>
        <button type="submit" name="setModules" class="submit_modules">Set your modules!</button>
      </form>
      <div class="wrapper_tech">
        <div class="repair_hull">
          <?php
          $hpMax = ((1000000 * ($userResearch["researchHp"]*0.1+1))-($userBase["coreHealth"] ));
          $cost = round($hpMax/2);
           ?>
          <h2>Hull repair</h2>
          <?php
           if ($cost > 0) {
             echo '<span>Hull points to be repaired:</span><div class="slider_hp"><input type="range" name="hp_input" min="0" max="'.$hpMax.'" value="0" class="slider" id="hp"><span class="amount_hp"></span></div><span>Price: <span class="cost_hp">0</span> Credits</span>';
             echo "<button type='button' class='btn_repair' value='hp'>Start the repairs</button>";
           } else {
             echo "<span>Your core is already fully repaired!</span>";
           }
           ?>
        </div>
        <div class="recharge_shield">
          <?php
          $shdMax = ((500000*($userResearch["researchShd"]*0.1+1))-($userBase["coreShields"]));
          $cost = round($shdMax/3);
           ?>
          <h2>Shield recharge</h2>
          <?php
           if ($cost > 0) {
             echo '<span>Shield points to be recharged:</span><div class="slider_shd"><input type="range" name="shd_input" min="0" max="'.$shdMax.'" value="0" class="slider" id="shd"><span class="amount_shd"></span></div><span>Price: <span class="cost_shd">0</span> Hyperids</span>';
           } else {
             echo "<span>Your core has fully recharged shields!</span>";
           }
           ?>
          <?php
           if ($cost > 0 && $userBase["coreHealth"] > 0) {
             echo "<button type='button' class='btn_repair' value='shd'>Start the shield recharge</button>";
           } elseif ($userBase["coreHealth"] == 0) {
             echo "<p class='button_shd_stop'>Cannot recharge shields without present core!</p>";
           }
           ?>
        </div>
      </div>
      </section>

    </main>

    <footer>
      <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>
