const ships = [];
const statics = [];
const portals = [];
const avalBGs = [1, 2, 3];
const portalAmount = 1;
const mapID = Math.floor(Math.random * avalBGs.length);
let canvas = undefined;
let ctx = undefined;
let shipInfo = undefined;
let shipsLoaded = false;
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
  ships.forEach((item) => item.update());
  statics.forEach((item) => item.draw());
  portals.forEach((item) => item.update());
};
const initDisplay = () => {
  // for (let i = 0; i < portalAmount; i++) {
  //   portals.push(new Portal());
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
class Portal {
  constructor() {
    this.x = Math.round(Math.random() * canvas.width);
    this.y = Math.round(Math.random() * canvas.height);
    this.activated = false;
    this.sequence = 0;
    this.sprite = new Image();
  }
  jumpActivate() {
    if (this.activated === true) {
      if (this.sequence <= 10) {
        //change portal img amount
        this.sequence += 1;
      } else {
        this.sequence = 0; //deactivated portal
        this.activated = false;
      }
    }
  }
  draw() {
    portalObjImg.src = "./image/gamemap/portals/" + this.sequence + ".png";
    this.jumpActivate();
    ctx.drawImage(portalObjImg, this.renderX, this.renderY);
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
  constructor(x, y, shipType) {
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
  }
  rotate() {
    if (this.x == this.targetX && this.y == this.targetY) {
      return;
    }
    const deltaY = this.targetY - this.y;
    const deltaX = this.targetX - this.x;
    this.rotation = Math.atan2(y, x);
  }
  update() {
    if (isMoving) {
      this.rotate();
      const newVelocity = realVelocity(this.speed, this.angle);
      this.x += newVelocity.x;
      this.y += newVelocity.y;
    }
    this.draw();
  }
  draw() {
    // translate and rotate
    ctx.translate(this.x, this.y);
    ctx.rotate(this.rotation);
    ctx.translate(-this.x, -this.y);

    ctx.drawImage(this.sprite, this.x, this.y);

    // untranslate and unrotate
    this.context.translate(this.x, this.y);
    this.context.rotate(-this.rotation);
    this.context.translate(-this.x, -this.y);
  }
  initJump(jumpX, jumpY) {
    this.targetX = jumpX;
    this.targetY = jumpY;
    this.isMoving = true;
  }
}
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
    if (!shipsLoaded) listAllShips();
    changeDisplay();
    let width;
    menuOpen ? (width = "0vw") : (width = "40vw");
    $(".menu_box").animate({ width });
    menuOpen = !menuOpen;
  });
});
