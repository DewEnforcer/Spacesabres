let loaded = false;
let uiLoaded = false;
let data = undefined;
const dataText = [
  "Points of destruction",
  "Destroyed ships",
  "Destroyed Hornets",
];
const fetchData = async () => {
  const response = await fetch("./include/getInfo.php", {
    method: "POST",
    headers: {
      "Content-type": "application/x-www-form-urlencoded",
    },
    body: "action='battleStats'",
  });
  data = await response.json();
  loaded = true;
  displayStats();
};
const loadUI = () => {
  uiLoaded = true;
  const closeController = `<img src="./image/graphics/closeMsg.png" id="close_stats">`;
  const box = `<div class="battle_stats_box">${closeController}</div>`;
  $("main").append(box);
};
const displayData = () => {
  if (!loaded) {
    fetchData();
    return;
  }
  if (!uiLoaded) {
    loadUI();
  }
  Object.values(data).forEach((item, i) => {
    let dataColumn = `<span></span>`;
    console.log(item);
  });
};
$(document).ready(() => {
  $(".btn_profile_abs").click(displayData);
});
