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
    this.rotation = Math.random() * 3;
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
