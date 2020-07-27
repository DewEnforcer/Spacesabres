const rankArr = [
  "Unknown",
  "Ensign",
  "Basic Lieutenant",
  "Lieutenant",
  "Lieutenant Commander",
  "Commander",
  "Captain",
  "Rear Admiral",
  "Vice Admiral",
  "Admiral",
  "Fleet Admiral",
  "Administrator",
];

//functions
const evaluateDanger = (data) => {
  let display = "block";
  if (data == "0") display = "none";
  $(".attack_alert").css("display", display);
};
const checkerFetch = async () => {
  const formData = new FormData();
  formData.append("action", "checkAttack");
  const response = await fetch("../include/getMovement.php", {
    method: "POST",
    body: formData,
  });
  const data = await response.text();
  evaluateDanger(data);
};
const bgChanger = () => {
  const time = new Date().getHours() + new Date().getTimezoneOffset() / 60;
  let bg = "";
  if (time > 7 && time <= 14) {
    bg = "1";
  } else if (time > 14 && time <= 21) {
    bg = "2";
  }
  $(document.body).css(
    "background-image",
    `url('../image/bg/background${bg}.webp')`
  );
};
const realTimeConv = () => {
  let seconds = Math.floor((serverTime / 1000) % 60);
  let minutes = Math.floor((serverTime / (1000 * 60)) % 60);
  let hours = Math.floor((serverTime / (1000 * 60 * 60)) % 24);

  hours = hours < 10 ? "0" + hours : hours;
  minutes = minutes < 10 ? "0" + minutes : minutes;
  seconds = seconds < 10 ? "0" + seconds : seconds;

  serverTime += 1000;

  const text = `Server time: ${hours}:${minutes}:${seconds}`;
  $(".server_time").html(text);
};
//utils
const numberFormated = (price) => {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
};
const updateUserValutes = (credit, hyperid, natium, userID = undefined) => {
  $("#span_credits").html("Credits: " + numberFormated(credit));
  $("#span_hyperids").html(" | Hyperid: " + numberFormated(hyperid));
  $("#span_natiums").html(" | Natium: " + numberFormated(natium));
  if (typeof userID != "undefined") {
    $("#span_id").html(" | UserID: " + userID);
  }
};
$(document).ready(function () {
  checkerFetch(); //initial attack check
  bgChanger();
  //set intervals now
  const attackChecker = setInterval(checkerFetch, 30000);
  const serverTimer = setInterval(realTimeConv, 1000);

  //events
  $(".button_confirm_result").click(function (hideResult) {
    $(".popup_result").html("");
    $(".popup_result").css("display", "none");
  });
});
