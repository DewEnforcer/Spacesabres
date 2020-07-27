let searchOpen = false;
let searchUIGen = false;
const fetchPlayers = async (data) => {
  console.log(data);
  const response = await fetch("./include/getInfo.php", {
    method: "POST",
    headers: {
      "Content-type": "application/x-www-form-urlencoded",
    },
    body: `action=searchPlayer&data=${data}`,
  });
  data = await response.json();
  displayResults(data);
};
const displayResults = (data) => {
  $(".pSearch_results").html(""); //erase all previous results
  const dataValues = Object.values(data);
  if (dataValues.length <= 0) {
    $(".pSearch_results").append(`<h3>No results have been found!</h3>`);
  } else {
    dataValues.forEach((item, i) => {
      console.log(item);
      let userInfo = `<div><span>User Info</span><span>Username: ${
        item[0]
      }</span><span>Rank: ${
        rankArr[item.rank]
      }</span><span>Leaderboard position: ${item.leaderboardPos}</span></div>`;
      let mapInfo = `<div><span>Coordinates</span><span>Map: ${item.mapLocation}</span><span>X: ${item.pageCoordsX}</span><span>Y: ${item.pageCoordsY}</span></div>`;
      let resultBox = `<div class="result_box" id="result_${i}">${
        userInfo + mapInfo
      }</div>`;
      $(".pSearch_results").append(resultBox);
    });
  }
};
const generateSearchUI = () => {
  const title = `<h2>Player search</h2>`;
  const searchBar = `<input type="text" id="input_pSearch" placeholder="Enter players username..."></input>`;
  const searchResultBox = `<div class="pSearch_results"></div>`;
  const closer = `<img src='../image/graphics/closeMsg.png' id='close_player_search'>`;
  const searchBox = `<div class="pSearch_container">${
    closer + title + searchBar + searchResultBox
  }</div>`;
  $("main").append(searchBox);
  searchUIGen = true;
};
const toggleSearchUI = () => {
  let width = "0vw";
  let height = "0vh";
  let display = "none";
  let timeout = 350;
  if (!searchOpen) {
    width = "50vw";
    height = "35vh";
    display = "grid";
    timeout = 0;
  }
  $(".pSearch_container").animate({ width, height }, 400);
  setTimeout(() => $(".pSearch_container").css({ display }), timeout);
  searchOpen = !searchOpen;
};
$(document).ready(() => {
  $("#search").click((ev) => {
    ev.preventDefault();
    if (!searchUIGen) {
      generateSearchUI();
    }
    toggleSearchUI();
  });
  $(document).on("change", "#input_pSearch", (ev) => {
    fetchPlayers($("#input_pSearch").val());
  });
  $(document).on("click", "#close_player_search", (ev) => {
    toggleSearchUI();
  });
});
