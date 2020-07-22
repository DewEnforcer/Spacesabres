$(document).ready(() => {
  let openFleet = false;

  retrieveFleetInfo = async () => {
    //let response = await fetch("./include/getAllMov.php");
    //let data = await response.json();
    data = {
      missionType: 1,
      ships: {
        1: 100,
        2: 50,
        3: 30,
        4: 0,
        5: 0,
        6: 5,
      },
    };
    drawFleetRoutes(data);
  };
  drawFleetRoutes = ({ missionType, ships }) => {
    if (openFleet) {
    }
  };
  displayManager = () => {
    let display = "flex";
    if (openFleet) {
      display = "none";
    }
    childArr = Array.from(document.querySelector(".header_btns").children);
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
      openFleet = false;
    } else {
      $(".fleet_bar").animate(
        {
          width: "30vw",
        },
        300
      );
      changeBtnDisplay();
      openFleet = true;
    }
  });
});
