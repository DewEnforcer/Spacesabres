const ships = [];
const statics = [];
const portals = [];
const avalBGs = [1, 2, 3];
const portalBuffer = [];
const portalAmount = 1;
const mapID = Math.floor(Math.random * avalBGs.length);
let canvas = undefined;
let ctx = undefined;
let shipInfo = undefined;
let shipsLoaded = false;
let hyperspaceOpen = false;
const selectedShips = [0, 0, 0, 0, 0, 0];
const baseCoords = {
  x: 600,
  y: 400,
};
let menuOpen = false;
//functions
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
  portals.push(new Portal(600, 300));
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
const getShipParams = async () => {
  const response = await fetch("./js/shipInfo.json");
  const dataReturn = await response.json();
  shipInfo = dataReturn.ships;
};
const listAllShips = () => {
  shipsLoaded = true;
  const bgMain = `./image/bg/dockbg.jpg`;
  Object.values(shipInfo).forEach((item, i) => {
    let amountBar = `<div class="amount_wrapper">
    <button type="button" class="btn_decrease_val" id="manage_${i}">-</button>
    <input type="number" id="amount_ship_${i}" value="${selectedShips[i]}">
    <button type="button" class="btn_increase_val" id="manage_${i}">+</button>
    </div>`;
    let title = `<h3 class="ship_title">${item.name}</h3>`;
    let bgShip = `./image/graphics/ship${i + 1}.png`;
    let mainBox = `<div style="background-image: url(${bgShip}), url(${bgMain})" class="ship_box" id="ship_${i}">${
      title + amountBar
    }</div>`;
    $(".menu_box").append(mainBox);
  });
};
const changeDisplay = () => {
  let display;
  display = menuOpen ? "none" : "flex";
  $(".menu_box div").css({ display });
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
  $(".body_box").append(`<div class="hyperspace_flash"></div>`);
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
class Portal {
  constructor(x, y) {
    this.x = x;
    this.y = y;
    this.sequence = 0;
    this.sprite = new Image();
    this.activated = false;
  }
  draw() {
    if (!this.activated) {
      return;
    }
    if (this.sequence >= 112) {
      this.sequence = 0;
      this.activated = false;
      return;
    }
    this.sequence += 1;
    this.sprite.src =
      "./image/gamemap/portals/portalJumpAnim/" + this.sequence + ".png";
    let offsetX = this.sprite.width / 2;
    let offsetY = this.sprite.height / 2;
    ctx.drawImage(this.sprite, this.x - offsetX, this.y - offsetY);
  }
  update() {
    this.draw();
  }
}
class Planet {
  constructor(mapID, x, y, path, sizeRed = 1) {
    this.mapID = mapID; //Math.floor(Math.random * avalBGs.length);
    this.x = x;
    this.y = y;
    this.sprite = new Image();
    this.sprite.src = path;
    this.width = this.sprite.width / sizeRed;
    this.height = this.sprite.height / sizeRed;
    this.offsetY = this.height / 2;
    this.offsetX = this.width / 2;
  }
  draw() {
    ctx.drawImage(
      this.sprite,
      this.x - this.offsetX,
      this.y - this.offsetY,
      this.width,
      this.height
    );
  }
}
class Background {
  constructor(x, y, path) {
    this.x = x;
    this.y = y;
    this.sprite = new Image();
    this.sprite.src = path;
  }
  draw() {
    ctx.drawImage(this.sprite, this.x, this.y);
  }
}
//ship manager
class Ship {
  constructor(x, y, shipType, index) {
    this.x = x;
    this.y = y;
    this.targetX = x;
    this.targetY = y;
    this.shipType = shipType;
    this.speed = 5; //speed on the display
    this.sprite = new Image();
    this.sprite.src = "./image/gamemap/ships/icon" + shipType + ".png";
    this.rotation = 0;
    this.isMoving = false;
    this.drawShip = true;
  }
  rotate(force = false) {
    if (this.x == this.targetX && this.y == this.targetY) {
      return;
    }
    const deltaY = this.targetY - this.y;
    const deltaX = this.targetX - this.x;
    this.rotation = Math.atan2(deltaY, deltaX);
  }
  update() {
    if (this.isMoving) {
      this.rotate();
      let distanceX = this.targetX - this.x;
      let distanceY = this.targetY - this.y;
      let totalDistance = Math.sqrt(
        Math.pow(distanceX, 2) + Math.pow(distanceY, 2)
      );
      let time = totalDistance / this.speed;
      this.x += distanceX / time;
      this.y += distanceY / time;
      if (
        this.x - this.targetX <= 20 &&
        this.y - this.targetY <= 20 &&
        this.drawShip
      ) {
        this.drawShip = false;
        hyperspaceBoom();
      }
    }
    this.draw();
  }
  draw() {
    //if (!this.drawShip) {
    //  return;
    //}
    // translate and rotate
    ctx.translate(this.x, this.y);
    ctx.rotate(this.rotation);
    ctx.translate(-this.x, -this.y);

    ctx.drawImage(this.sprite, this.x, this.y);

    // untranslate and unrotate
    ctx.translate(this.x, this.y);
    ctx.rotate(-this.rotation);
    ctx.translate(-this.x, -this.y);
  }
  initJump(jumpX, jumpY) {
    setTimeout(() => {
      //random delay
      this.targetX = jumpX;
      this.targetY = jumpY;
      this.isMoving = true;
    }, Math.floor(Math.random() * 500));
  }
}
const createTest = () => {
  let x = 300;
  let y = 500;
  for (let i = 0; i < 10; i++) {
    x += 20;
    y += 10;
    ships.push(new Ship(x, y, 1));
  }
};
const setTestCoords = () => {
  const x = 600;
  const y = 300;
  ships.forEach((item) => item.initJump(x, y));
  portals.forEach((item) => (item.activated = true));
};
preloadSources();
getShipParams();
createTest();
$(document).ready(() => {
  canvas = document.querySelector("#briefing_display");
  const wrapper = $(".body_box");
  canvas.width = wrapper.width();
  canvas.height = wrapper.height();
  ctx = canvas.getContext("2d");
  initDisplay();
  animate();
  $("#btn_open_menu").click(() => {
    if (!shipsLoaded) listAllShips();
    changeDisplay();
    let width;
    menuOpen ? (width = "0vw") : (width = "40vw");
    $(".menu_box").animate({ width });
    menuOpen = !menuOpen;
  });
  setTimeout(setTestCoords, 5000);
});
