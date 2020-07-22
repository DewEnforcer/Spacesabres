$(document).ready(function () {
  let timeOffset = new Date();
  let offset = timeOffset.getTimezoneOffset();
  let time = new Date().getHours() + offset / 60;
  if (time < 7) {
    $(document.body).css(
      "background-image",
      "url('../image/bg/background.webp')"
    );
  } else if (time < 14) {
    $(document.body).css(
      "background-image",
      "url('../image/bg/background1.webp')"
    );
  } else if (time < 21) {
    $(document.body).css(
      "background-image",
      "url('../image/bg/background2.webp')"
    );
  } else if (time < 23) {
    $(document.body).css(
      "background-image",
      "url('../image/bg/background.webp')"
    );
  }
  $.ajax({
    type: "POST", //execute ajax
    url: "../include/getMovement.php",
    data: { index: "checkAttacks" },
    success: function (getStatus) {
      if (getStatus == "1") {
        $(".attack_alert").css("display", "block");
      } else if (getStatus == "0") {
        ("");
      }
    },
  }); //end ajax call
  function checkAttack() {
    let interval = setInterval(function (intervalCheckattack) {
      $.ajax({
        type: "POST", //execute ajax
        url: "../include/getMovement.php",
        data: { index: "checkAttacks" },
        success: function (getStatus) {
          if (getStatus == "1") {
            $(".attack_alert").css("display", "block");
          } else if (getStatus == "0") {
            $(".attack_alert").css("display", "none");
          }
        },
      }); //end ajax call
    }, 30000);
  }
  let initiateCheck = checkAttack();
  $(".button_confirm_result").click(function (hideResult) {
    $(".popup_result").html("");
    $(".popup_result").css("display", "none");
  });

  function msToTime(duration) {
    var milliseconds = parseInt((duration % 1000) / 100),
      seconds = Math.floor((duration / 1000) % 60),
      minutes = Math.floor((duration / (1000 * 60)) % 60),
      hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

    hours = hours < 10 ? "0" + hours : hours;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    $(".server_time").html(
      "Server time: " + hours + ":" + minutes + ":" + seconds + ""
    );
  }

  let serverClock = setInterval(function () {
    msToTime(serverTime);
    serverTime += 1000;
  }, 1000);
});
// new features
const numberFormated = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
};
const updateUserValutes = (credit, hyperid, natium) => {
  const children = $(".header_center_res").children();
  children[0].innerHTML = "Credits: " + numberFormated(credit);
  children[1].innerHTML = "Hyperid: " + numberFormated(hyperid);
  children[2].innerHTML = "Natium: " + numberFormated(natium);
};
