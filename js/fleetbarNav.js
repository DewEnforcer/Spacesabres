const missionTranslate = ["Unknown", "Attack", "Return"];
const missionColors = ["grey", "red", "green"];
const routesArray = [];
let infoInit = false;
class movementRoute {
  constructor({ missionType, ships, arrival, x, y, map }) {
    this.missionType = missionType;
    this.ships = ships;
    this.parentMain = $(".fleet_bar");
    this.infoBox = undefined;
    this.arrivalParams = {
      x,
      y,
      map,
      arrival,
    };
    this.drawFleetRoute();
    this.countdown();
  }
  drawFleetRoute = () => {
    if (typeof this.infoBox === "undefined") {
      //init the route box
      this.infoBox = document.createElement("div");
      this.infoBox.classList.add("route_box");
      this.parentMain.append(this.infoBox);
      $(this.infoBox).css("display", "flex");
    }
    this.infoBox.innerHTML = `<h3 style="color: ${
      missionColors[this.missionType]
    }">Mission type: ${
      missionTranslate[this.missionType]
    }</h3><div class="route_params"></div>`;
    $(".route_params").html(
      `<span>Coordinates: ${this.arrivalParams.map}:${this.arrivalParams.x}:${this.arrivalParams.y}</span><span>Time left: <span id="arrival_text_${this.arrivalParams.arrival}">00:00:00</span></span>`
    );
  };
  countdown = () => {
    const offset = new Date().getTimezoneOffset() * 60 * 1000;
    const deadline = this.arrivalParams.arrival * 1000;
    const x = setInterval(() => {
      const now = new Date().getTime() + offset;
      const t = deadline - now;
      let hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      let minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
      let seconds = Math.floor((t % (1000 * 60)) / 1000);
      //add second digit if below 10
      hours = hours < 10 ? "0" + hours : hours;
      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;
      const text = `${hours}:${minutes}:${seconds}`;
      $(`#arrival_text_${this.arrivalParams.arrival}`).html(text);
      if (t < 0) {
        clearInterval(x);
        $(this.infoBox).remove();
      }
    }, 1000);
  };
}

$(document).ready(() => {
  let openFleet = false;
  retrieveFleetInfo = async () => {
    let response = await fetch("./include/getMovement.php", {
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      method: "POST",
      body: "action=fleetbar_data",
    });
    let data = await response.json();
    for (let i = 0; i < data.length; i++) {
      const element = data[i];
      routesArray.push(new movementRoute(element));
    }
  };
  displayManagerFleet = () => {
    let display = "flex";
    if (openFleet) {
      display = "none";
    }
    let childArr = Array.from(document.querySelector(".fleet_bar").children);
    childArr.forEach((item, i) => {
      $(item).css("display", display);
    });
  };
  changeBtnDisplay = () => {
    display = "Hover";
    if (openFleet) {
      display = "";
    }
    $("#btn_fleetbar").css(
      "background-image",
      "url('./image/graphics/openFleet" + display + ".png')"
    );
  };
  $("#btn_fleetbar").click(() => {
    if (openFleet) {
      $(".fleet_bar").animate(
        {
          width: "0vw",
        },
        300
      );
      changeBtnDisplay();
      displayManagerFleet();
      openFleet = false;
    } else {
      $(".fleet_bar").animate(
        {
          width: "20vw",
        },
        300
      );
      if (!infoInit) {
        retrieveFleetInfo();
        infoInit = true;
      }
      displayManagerFleet();
      changeBtnDisplay();
      openFleet = true;
    }
  });
  $(document).on("click", ".route_box", () => {
    window.location.href = "internalFleets.php";
  });
});
