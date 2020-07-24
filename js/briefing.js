const ships = [];
const statics = [];
const portals = [];
const avalBGs = [1, 2, 3];
const portalBuffer = [];
const portalAmount = 1;
const rootFlash = "body_box";
const missionList = [1, 2]; //attack, defense
const mapID = Math.floor(Math.random * avalBGs.length);
let canvas = undefined;
let ctx = undefined;
let shipInfo = undefined;
let shipsLoaded = false;
let hyperspaceOpen = false;
let deployable = false;
const selectedShips = [0, 0, 0, 0, 0, 0];
const baseCoords = {
  x: 600,
  y: 400,
};
let menuOpen = false;
const deployFetch = async () => {
  const response = await fetch("./include/briefingAjax.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=deploy&data=" + JSON.stringify(selectedShips),
  });
};
//canvas functions
const manageShipsGamemap = (amountOld, amountNew, shipType) => {
  const difference = amountNew - amountOld;
  if (difference > 0) {
    for (let i = 0; i < difference; i++) {
      const randomOffsetX = Math.floor(
        Math.random() * 100 + Math.random() * -100
      );
      const randomOffsetY = Math.floor(
        Math.random() * 50 + Math.random() * -50
      );
      ships.push(
        new Ship(
          baseCoords.x + randomOffsetX,
          baseCoords.y + randomOffsetY,
          shipType + 1,
          0
        )
      );
    }
  } else if (difference < 0) {
    ships.forEach((item, i) => {
      if (item.shipType == shipType + 1) {
        ships.splice(i, 1);
      }
    });
  }
};
const realVelocity = (shipSpeed, vectorAngle) => {
  let velocity = {
    x: undefined,
    y: undefined,
  };
  velocity.x = shipSpeed * Math.sin(vectorAngle);
  velocity.y = shipSpeed * Math.cos(vectorAngle);
  return velocity;
};
const animate = () => {
  requestAnimationFrame(animate);
  ctx.clearRect(0, 0, innerWidth, innerHeight);
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  statics.forEach((item) => item.draw());
  ships.forEach((item) => item.update());
  portals.forEach((item) => item.update());
};
const preloadSources = () => {
  const sequences = 112;
  for (let i = 0; i <= sequences; i++) {
    let element = new Image();
    element.src = "./image/gamemap/portals/portalJumpAnim/" + i + ".png";
    portalBuffer.push(element);
  }
};
const initDisplay = () => {
  // for (let i = 0; i < portalAmount; i++) {
  portals.push(new Portal(0, 0));
  // }
  statics.push(
    new Background(0, 0, "./image/gamemap/backgrounds/background" + 1 + ".png")
  );
  statics.push(
    new Planet(
      mapID,
      500,
      canvas.height - 200,
      "./image/gamemap/planets/planet" + 1 + ".png",
      2
    )
  );
};
const removeShips = () => {
  while (ships.length) {
    ships.pop();
  }
};
const hyperspaceBoom = () => {
  if (hyperspaceOpen) {
    return;
  }
  hyperspaceOpen = true;
  const sound = document.createElement("audio");
  sound.src = "../sounds/hyperspacejump.mp3";
  sound.volume = 0.5;
  sound.preload = true;
  $("." + rootFlash).append(`<div class="hyperspace_flash"></div>`);
  sound.play();
  $(".hyperspace_flash").fadeIn(700, () => {
    $(".hyperspace_flash").css("display", "block");
  });
  $(".hyperspace_flash").fadeOut(250, () => {
    $(".hyperspace_flash").css("display", "none");
    hyperspaceOpen = false;
  });
  setTimeout(removeShips, 500);
};
const createTest = () => {
  let x = 300;
  let y = 500;
  for (let i = 0; i < 10; i++) {
    x += 20;
    y += 10;
    ships.push(new Ship(x, y, 1));
  }
};
const initJump = () => {
  const x = Math.round(Math.random() * canvas.width);
  const y = Math.round(Math.random() * canvas.height);
  portals.forEach((item) => {
    (item.x = x), (item.y = y);
  });
  ships.forEach((item) => item.initJump(x, y));
  portals.forEach((item) => (item.activated = true));
};
preloadSources();
getShipParams();

$(document).ready(() => {
  canvas = document.querySelector("#briefing_display");
  const wrapper = $(".body_box");
  canvas.width = wrapper.width();
  canvas.height = wrapper.height();
  ctx = canvas.getContext("2d");
  initDisplay();
  animate();
  $("#btn_open_menu").click(() => {
    if (!shipsLoaded) {
      genMenuUi();
      manageDeployButton();
    }
    changeDisplay();
    let width;
    menuOpen ? (width = "0vw") : (width = "70vw");
    $(".menu_box").animate({ width });
    menuOpen = !menuOpen;
  });
  //amount controlers
  $(document).on("click", ".btn_val_man", (ev) => {
    let value = 1;
    if (ev.target.value === "dec") {
      value = -1;
    }
    const shipType = Number(ev.target.id.split("_")[1]);
    const newValue = selectedShips[shipType] + value;
    manageShipsGamemap(selectedShips[shipType], newValue, shipType);
    selectedShips[shipType] = newValue;
    setAmountVisual(shipType);
  });
  $(document).on("change", ".ship_pcs", (ev) => {
    const shipType = Number(ev.target.id.split("_")[2]);
    const newAmount = Number(ev.target.value);
    manageShipsGamemap(selectedShips[shipType], newAmount, shipType);
    selectedShips[shipType] = newAmount;
    setAmountVisual(shipType);
  });
  $(document).on("click", ".btn_deploy", (ev) => {
    if (!deployable) {
      return;
    }
    //deployFetch();
    $("#btn_open_menu").trigger("click");
    initJump();
  });
});
