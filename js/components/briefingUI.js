//UI functions
const calculateFuelConsumption = () => {
  let totalConsumpt = 0;
  selectedShips.forEach((item, i) => {
    totalConsumpt += shipInfo[i + 1].fuel_consumption * item;
  });
  totalConsumpt *= getDistance();
  let width = (totalConsumpt / userFuel) * 100;
  enoughFuel = true;
  if (width > 100) {
    width = 100;
    enoughFuel = false;
  }
  width += "%";
  $(".real_consumption").animate({
    width,
  });
  $("#consumpt_number").html(`${totalConsumpt}/${userFuel} Fuel`);
};
const getShipParams = async () => {
  const response = await fetch("./js/shipInfo.json");
  const dataReturn = await response.json();
  shipInfo = dataReturn.ships;
};
const genMenuUi = () => {
  $(".menu_box").html(
    `<div class="ship_list"></div><div class="operation_box"></div>`
  );
  listAllShips();
  listOperationOptions();
};
const getDistance = () => {
  const coords = {
    x: Number($("#coord_x").val()),
    y: Number($("#coord_y").val()),
    map: Number($("#coord_map").val()),
  };
  const { x, y, map } = basePosition;
  return (
    Math.abs(x - coords.x) +
    Math.abs(y - coords.y) +
    Math.abs((map - coords.map) * 100)
  );
};
const manageTravelTime = () => {
  const distance = getDistance();
  const distIncrease = 100;
  let maxSpeed = 1000000000;
  selectedShips.forEach((item, i) => {
    if (item <= 0) {
      return;
    }
    const shipSpeed = shipInfo[i + 1].speed;
    if (shipSpeed < maxSpeed) {
      maxSpeed = shipSpeed;
    }
  });
  const travelTime = ((distance * distIncrease) / maxSpeed) * 60;
  let hours = Math.floor(travelTime / 3600);
  let minutes = Math.floor(travelTime / 60) - hours * 60;
  let seconds = Math.floor(travelTime - hours * 3600 - minutes * 60);

  hours = hours < 10 ? "0" + hours : hours;
  minutes = minutes < 10 ? "0" + minutes : minutes;
  seconds = seconds < 10 ? "0" + seconds : seconds;

  $("#time_required").html(`Travel time: ${hours}:${minutes}:${seconds}`);
};
const manageDeployButton = () => {
  let cursor = "pointer";
  let backgroundColor = "#5be64e";
  deployable = true;
  if (
    selectedShips.reduce((total, amount) => total + amount, 0) <= 0 ||
    !enoughFuel
  ) {
    cursor = "default";
    backgroundColor = "grey";
    deployable = false;
  }
  $(".btn_deploy").css({
    cursor,
    backgroundColor,
  });
};
const manageSelectedMission = () => {
  console.log("hey");
};
const listOperationOptions = () => {
  //fuel, x y map, time, mission type
  const title = `<h2>Mission overview</h2>`;
  const missionTypeBox = `<div class="missions_list"></div>`;
  const coordsBox = `<div class="coords_box"></div>`;
  const timeInfo = `<span id="time_required">Travel time: ??:??:??</span>`;
  const fuelInfo = `<div class="consumption_wrapper"><div class="max_consumption"><div class="real_consumption"></div></div><span id="consumpt_number">0/${userFuel} Fuel</span></div>`;
  const deployFleet = `<button type="button" class="btn_deploy">Deploy fleet!</button>`;
  $(".operation_box").html(
    title + missionTypeBox + coordsBox + timeInfo + fuelInfo + deployFleet
  );
  generateCoordinates();
  listMissions();
};
const listMissions = () => {
  missionList.forEach((item) => {
    let box = `<img class="mission_select" id="mission_${item}" src="./image/graphics/missIcon${item}.png">`;
    $(".missions_list").append(box);
  });
};
const generateCoordinates = () => {
  const { x, y, map } = targetCoords;
  const inputs = `<input type="number" class="coords_info" id="coord_map" value="${
    typeof map === "undefined" ? 0 : map
  }">:<input type="number" class="coords_info" id="coord_x" value="${
    typeof x === "undefined" ? 0 : x
  }">:<input type="number" class="coords_info" id="coord_y" value="${
    typeof y === "undefined" ? 0 : y
  }">`;
  const content = `<span>Coordinates(map/x/y): </span>${inputs}`;
  $(".coords_box").html(content);
};
const listAllShips = () => {
  shipsLoaded = true;
  const bgMain = `./image/bg/briefingBG.png`;
  Object.values(shipInfo).forEach((item, i) => {
    let amountBar = `<div class="amount_wrapper">
      <button type="button" class="btn_val_man" value="dec" id="manage_${i}">-</button>
      <input class="ship_pcs" type="number" id="amount_ship_${i}" value="${selectedShips[i]}">
      <button type="button" class="btn_val_man" value="add" id="manage_${i}">+</button>
      </div>`;
    let title = `<h3 class="ship_title">${item.name}</h3>`;
    let bgShip = `./image/graphics/ship${i + 1}.png`;
    let mainBox = `<div style="background-image: url(${bgShip}), url(${bgMain})" class="ship_box" id="ship_${i}">${
      title + amountBar
    }</div>`;
    $(".ship_list").append(mainBox);
  });
};
const changeDisplay = () => {
  let display;
  display = menuOpen ? "none" : "grid";
  const children = Array.from(document.querySelector(".menu_box").children);
  children.forEach((element) => {
    $(element).css({ display });
  });
  //$(".menu_box div").css({ display });
};
const setAmountVisual = (shipType) => {
  if (selectedShips[shipType] < 0) {
    selectedShips[shipType] = 0;
  } else if (selectedShips[shipType] > realFleet[shipType]) {
    selectedShips[shipType] = realFleet[shipType];
  }
  $("#amount_ship_" + shipType).val(selectedShips[shipType]);
  calculateFuelConsumption();
  manageDeployButton();
  manageTravelTime();
};
