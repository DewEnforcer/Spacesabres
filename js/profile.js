let loaded = false;
let uiLoaded = false;
let openStats = false;
let data = undefined;
const dataText = [
  "Points of destruction",
  "Destroyed ships",
  "Destroyed Hornets",
  "Destroyed Spacefires",
  "Destroyed Starhawks",
  "Destroyed Peacemakers",
  "Destroyed Centurions",
  "Destroyed Na-Thalis destroyers",
  "Battles",
  "Victories",
  "Losses",
  "Draws",
];
const fetchData = async () => {
  const response = await fetch("./include/getInfo.php", {
    method: "POST",
    headers: {
      "Content-type": "application/x-www-form-urlencoded",
    },
    body: "action=getBattleStats",
  });
  data = await response.json();
  loaded = true;
  displayData();
};
const loadUI = () => {
  uiLoaded = true;
  const closeController = `<img src="./image/graphics/closeMsg.png" id="close_stats">`;
  const box = `<div class="battle_stats_box">${closeController}</div>`;
  $("main").append(box);
};
const displayData = () => {
  Object.values(data).forEach((item, i) => {
    let dataColumn = `<span>${dataText[i]}: ${numberFormated(
      Number(item)
    )}</span>`;
    $(".battle_stats_box").append(dataColumn);
  });
};
const displayStatsBox = () => {
  if (!uiLoaded) {
    loadUI();
  }
  if (!loaded) {
    fetchData();
  }
  let display = "none";
  if (!openStats) {
    display = "flex";
  }
  openStats = !openStats;
  $(".battle_stats_box").css({ display });
};
$(document).ready(() => {
  $(".btn_profile_abs").click((e) => {
    e.preventDefault();
    displayStatsBox();
  });
  $(document).on("click", "#close_stats", displayStatsBox);
});
