let shipInfo = undefined;
let selectedShip = 0;
const getShipParams = async () => {
  const response = await fetch("./js/shipInfo.json");
  const dataReturn = await response.json();
  shipInfo = dataReturn.ships;
  listAllShips();
};
const listAllShips = () => {
  const bgMain = `./image/bg/dockbg.jpg`;
  Object.values(shipInfo).forEach((item, i) => {
    let amount = `<span>${numberFormated(shipAm[i])} Pcs.</span>`; //shipAm derived from main page
    let title = `<h3 class="ship_title">${item.name}</h3>`;
    let bgShip = `./image/graphics/ship${i + 1}.png`;
    let mainBox = `<div style="background-image: url(${bgShip}), url(${bgMain})" class="ship_box" id="ship_${i}">${
      title + amount
    }</div>`;
    $(".dock_ship_list").append(mainBox);
  });
  displaySelection(); //initial
  displayShipDetails();
};
const displaySelection = (status = true) => {
  let opacity = "1";
  let borderColor = "white";
  if (!status) {
    opacity = "0.7";
    borderColor = "grey";
  }
  $("#ship_" + selectedShip).css({
    opacity,
    borderColor,
  });
};
const displayShipDetails = () => {
  if (typeof shipInfo === "undefined") {
    return;
  }
  const rnd = Math.floor(Math.random() * 10);
  const { name, description } = shipInfo[selectedShip + 1];
  const type = shipInfo[selectedShip + 1]["class"];
  const txt = `<h1>${name}</h1><div class="gif_container"></div><h3>${type}</h3><hr><span>In dock: ${numberFormated(
    shipAm[selectedShip]
  )} Pieces</span><hr><p>${description}</p>`;
  $(".dock_ship_details").html(txt);
  $(".gif_container").html(
    `<img src="./image/shopImg/item${selectedShip + 1}.gif?${rnd}">`
  );
};
$(document).ready(() => {
  getShipParams();
  $(document).on("click", ".ship_box", (ev) => {
    const id = Number(ev.target.id.split("_")[1]);
    if (id == selectedShip) {
      return;
    }
    displaySelection(false);
    selectedShip = id;
    displaySelection();
    displayShipDetails();
  });
});
