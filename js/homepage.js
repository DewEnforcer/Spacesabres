$(document).ready(function() {
  var open = false;
  $(".btn_login").click(function() {
    if (open == false) {
      $(".login_form_wrapper").remove();
      $("main").append(`<div class="login_form_wrapper"></div>`);
      $(".login_form_wrapper").html(`<h3>LOGIN</h3><form action="include/login.inc.php" class="login_form" method="post"><input type="text" name="mailuid" placeholder="Username"><input type="password" name="pwd" placeholder="Password"><button type="submit" name="login-submit">Login</button><hr style="width: 100%; margin: 4px 0;"><a href="resetPassword.php" target="_blank">Forgot your password?</a></form>`);
      setTimeout(function () {
        $(".login_form_wrapper").css("display", "flex");
      }, 100);
      $(".login_form_wrapper").slideToggle(1000, function() {

      });
      open = true;
    } else {
      $(".login_form_wrapper").slideUp(500, function() {
        open = false;
      });
    }
  });
  $(".img_gameinfo").click(function() {
    $(".about_game_popup").remove();
    $("main").append(`<div class="about_game_popup"></div>`);
    $(".about_game_popup").animate({
      width: "30vw",
      height: "70%"
    }, 1000)
    $(".about_game_popup").html(`<h2>Meet Spacesabres!</h2><img src="../image/graphics/closeMsg.png" class="close_popup"><p>Spacesabres is a free to play browser game. If you have ever wanted to be an admiral or have your own galactic fleet , this game is perfect for you! Build your fleet and engage in strategical battles againts other players , or the feared xamons. Help the mining company by completing galactic missions and recieve great in-game rewards that will help you grow your fleet even more! So what are you waiting for? Register now and become the greatest admiral in the galaxy of spacesabres!</p>`);
  });

  let imgID;
  let titlesArr = ["Battlestation", "Shipyard", "Armory", "Ship configuration", "Docks", "Traveling", "Command center"];
  $(".img_gallery").click(function() {
    imgID = 0;
    $("main").append(`<div class="popup_gallery"></div>`);
    $(".popup_gallery").html(`<h2 class="img_title">${titlesArr[imgID]}</h2><img src="../image/graphics/closeMsg.png" id="gallery_close" class="close_popup"><div class="gallery_wrapper"></div>`);
    $(".gallery_wrapper").html(`<button type="button" class="btn_gallery" id="previous"><</button><div class="img_gallery_wrapper"><img src="../image/gallery/img${imgID}.png" ></div><button type="button" class="btn_gallery" id="next">></button>`);
    $(".popup_gallery").fadeIn(1000, function() {
    });
    $(".popup_gallery").css("display", "flex");
  });
  $(document).on("click", ".btn_gallery", function() {
    let movement = this.id;
    if (movement == "previous") {
      imgID--;
      if (imgID < 0) {
        imgID = 0;
      } else {
        $(".img_title").html(`${titlesArr[imgID]}`);
        $(".img_gallery_wrapper").html(`<img src="../image/gallery/img${imgID}.png" >`);
      }
    } else if (movement == "next") {
      imgID++;
      if (imgID > 6) {
        imgID = 6;
      } else {
        $(".img_title").html(`${titlesArr[imgID]}`);
        $(".img_gallery_wrapper").html(`<img src="../image/gallery/img${imgID}.png" >`);
      }
    }
  });
  $(document).on("click", ".close_popup", function() {
    $(".about_game_popup").fadeOut(1000, function() {
            $(".about_game_popup").remove();
    })
    $(".popup_gallery").fadeOut(1000, function() {
            $(".popup_gallery").remove();
    })
  });

});
