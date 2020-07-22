$(document).ready(function () {
  function dockAjax(action, additionalData) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../include/dockAjax.php",
        type: "POST",
        data: {
          action: action,
          data: additionalData,
        },
        success: function (data) {
          resolve(data);
        },
        error: function (error) {
          reject(error);
        },
      });
    });
  }
  function countEquipped(ship, equipment) {
    let amount = 0;
    for (var i = 0; i < ship[equipment].length; i++) {
      if (ship[equipment][i] > 0) {
        amount++;
      }
    }
    return amount;
  }
  var selected = undefined;
  var dock = 1;
  var params = [
    [0, 2, 4, 10, 15, 25],
    [4, 2, 0, 0, 0, 0],
    [0, 0, 1, 6, 7, 15],
    [1, 1, 1, 1, 1, 1],
  ];
  var shipNames = [
    "Hornet",
    "Spacefire",
    "Starhawk",
    "Peacemaker",
    "Centurion",
    "Na-Thalis destroyer",
  ];
  $(document).on("click", ".ship_tab", function () {
    if (selected != undefined) {
      $("#" + selected).css("border-color", "grey");
    }
    selected = $(this).prop("id");
    $(this).css("border-color", "white");
  });
  $(".btn_dock_scrap").click(function () {
    $("main").append(`<div class="squadron_popup"></div>`);
    $(".squadron_popup").html(
      `<h2>Scrap ship</h2><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`
    );
    if (selected != undefined) {
      let shipNumber = $("#" + selected).prop("id");
      let shipName = $("#" + selected).attr("type");
      $(".squadron_popup").append(
        `<p>Are you sure you want to scrap ${shipNames[shipName]} N.${shipNumber} ? Once the ship is scrapped any of it's equipped equipment will be lost with the ship.</p><button type="button" class="btn_remove_dock" value="${shipNumber}" id="${shipName}">Scrap</button>`
      );
    } else {
      $(".squadron_popup").append(
        `<p>Please select the ship you want to scrap</p>`
      );
    }
  });
  $(".btn_dock_manage").click(function () {
    if (selected != undefined) {
      let shipNumber = $("#" + selected).prop("id");
      let shipName = $("#" + selected).attr("type");
      window.location.href =
        "../include/redirect.php?shipID=" +
        shipNumber +
        "&dock=" +
        dock +
        "&type=" +
        shipName;
    } else {
      $("main").append(`<div class="squadron_popup"></div>`);
      $(".squadron_popup").html(
        `<h2>Equip ship</h2><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`
      );
      $(".squadron_popup").append(
        `<p>Please select the ship you want to equip</p>`
      );
    }
  });
  $(document).on("click", ".btn_remove_dock", function (remove) {
    $("main").append(`<div class="squadron_popup"></div>`);
    $(".squadron_popup").html(
      `<h2>Remove squadron</h2><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`
    );
    let shipNumber = $(this).val();
    let shipType = $(this).attr("id");
    let dataArr = JSON.stringify([shipNumber, shipType, dock]);
    dockAjax("remove", dataArr).then((data) => {
      if (data != "" && data != "error") {
        $("#" + shipNumber).remove();
        $(".squadron_popup").html(
          `<h2>Success</h2><p>Selected ship has been succesfully scrapped.</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`
        );
        selected = undefined;
      }
    });
  });
  $(".btn_dock_scrap_all").click(function () {
    dockAjax("delete_all", dock).then((data) => {
      $(".ship_tab").remove();
    });
  });
  $(".btn_dock_show").click(function () {
    if (dock != $(this).prop("id")) {
      $("main").append(
        "<img src='../image/graphics/loading.gif' class='loading'>"
      );
      dock = $(this).prop("id");
      dockAjax("switch", dock).then((data) => {
        if (data != "error") {
          $(".wrapper_squadrons").html(
            `<tr class="header_tr"><th>Ship ID</th><th>Ship type</th><th>Equipment</th></tr>`
          );
          let dockData = $.parseJSON(data);
          dockData.forEach(function (shipObj) {
            if (typeof shipObj == "object") {
              let lasers = countEquipped(shipObj, "lasers");
              let rockets = countEquipped(shipObj, "rockets");
              let shields = countEquipped(shipObj, "shields");
              let hyperspace = countEquipped(shipObj, "hyperspace");
              $(".wrapper_squadrons").append(
                `<tr class="ship_tab" id="${shipObj["number"]}" type="${
                  shipObj["type"]
                }"><td>${shipObj["number"]}</td><td>${
                  shipNames[shipObj["type"]]
                }</td><td><div class="equipment_wrapper">Lasers: ${lasers}/${
                  params[0][shipObj["type"]]
                } | Missile platforms: ${rockets}/${
                  params[1][shipObj["type"]]
                } | Shield generators: ${shields}/${
                  params[2][shipObj["type"]]
                } | Hyperspace engines: ${hyperspace}/${
                  params[3][shipObj["type"]]
                }</div></td></tr>`
              );
              selected = undefined;
              $("#" + selected).css("border-color", "grey");
            }
            $(".loading").remove();
          });
        }
        $(".loading").remove();
      });
      $(".dock_curr_sp").html(dock);
    }
  });
  $(".btn_filter").click(function () {
    selected = undefined;
    let type = $(this).prop("id");
    console.log(type);
    let table = document
      .querySelector(".wrapper_squadrons")
      .getElementsByTagName("tr");
    let amount = table.length;
    for (var i = 1; i < amount; i++) {
      let typeItem = $(table[i]).attr("type");
      console.log($(table[i]).attr("type"));
      if (typeItem != type) {
        $(table[i]).css("display", "none");
      } else {
        $(table[i]).css("display", "flex");
      }
    }
  });
  $(".btn_filter_all").click(function () {
    $(".ship_tab").css("display", "flex");
  });
  $(document).on("click", ".btn_close_popup", function () {
    $(".squadron_popup").remove();
  });
});
