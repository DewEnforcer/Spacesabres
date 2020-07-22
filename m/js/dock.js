$(document).ready(function() {
  function dockAjax(action, additionalData) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "./include/dockAjax.php",
        type: 'POST',
        data: {
          action: action,
          data: additionalData
        },
        success: function(data) {
          resolve(data);
        },
        error: function(error) {
          reject(error);
        },
      })
    })
}
function countEquipped (ship, equipment) {
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
  var params = [[0,2,4,10,15,25], [4,2,0,0,0,0],[0,0,1,6,7,15],[1,1,1,1,1,1]];
  var shipNames = ["Hornet", "Spacefire", "Starhawk", "Peacemaker", "Centurion", "Na-Thalis destroyer"];
  // TODO: přidat event listener kterej mění ve kterým docku se user nachází -> také aktualizovat seznam lodí
  $(document).on("click", ".ship_tab", function() {
    if (selected != $(this).prop("id")) {
      $(".ship_options_wrapper").remove();
      $(this).append("<div class='ship_options_wrapper'></div>");
      $(".ship_options_wrapper").html(`<button type="button" name="button" class="btn_dock_manage">Equip selected ship</button><button type="button" name="button" class="btn_dock_scrap">Scrap selected ship</button>`);
      $(".ship_options_wrapper").animate({
        width: "100%"
      }, 700)
      $(".ship_options_wrapper").css("display", "flex");
      selected = $(this).prop("id");
    } else if (selected == $(this).prop("id")) {

    }

  });
  $(document).on("click", ".btn_dock_scrap", function() {
      $("main").append(`<div class="squadron_popup"></div>`);
      $(".squadron_popup").html(`<h2>Scrap ship</h2><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
    if (selected != undefined) {
      let shipNumber = $("#"+selected).prop("id");
      let shipName = $("#"+selected).attr("type");
      $(".squadron_popup").append(`<p>Are you sure you want to scrap ${shipNames[shipName]} N.${shipNumber} ? Once the ship is scrapped any of it's equipped equipment will be lost with the ship.</p><button type="button" class="btn_remove_dock" value="${shipNumber}" id="${shipName}">Scrap</button>`);
    } else {
      $(".squadron_popup").append(`<p>Please select the ship you want to scrap</p>`);
    }
  });
  $(document).on("click", ".btn_dock_manage", function() {
    if (selected != undefined) {
      let shipNumber = $("#"+selected).prop("id");
      let shipName = $("#"+selected).attr("type");
      window.location.href = "./include/redirect.php?shipID="+shipNumber+"&&shipType="+shipName+"&&dock="+dock+"";
    } else {
      $("main").append(`<div class="squadron_popup"></div>`);
      $(".squadron_popup").html(`<h2>Equip ship</h2><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
      $(".squadron_popup").append(`<p>Please select the ship you want to equip</p>`);
    }
  });
  $(document).on("click", ".btn_remove_dock", function (remove) {
    let shipNumber = $(this).val();
    let shipType = $(this).attr("id");
    let dataArr = JSON.stringify([shipNumber, shipType, dock]);
    dockAjax("remove", dataArr).then(data => {
      if (data != "" && data != "error") {
      $("#"+shipNumber).remove();
      $(".squadron_popup").html(`<h2>Success</h2><p>Selected ship has been succesfully scrapped.</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
      selected = undefined;
      }
    });
  });
  $(".btn_dock_scrap_all").click(function() {
    dockAjax("delete_all", dock).then(data => {
      $(".ship_tab").remove();
    });
  });
  $(document).on("change", ".dock_select", function() {
    if (dock != this.value) {
      $("main").append("<img src='../image/graphics/loading.gif' class='loading'>");
      dock = this.value
      dockAjax("switch", dock).then(data => {
        if (data != "error") {
          $(".wrapper_squadrons").html(`<tr class="header_tr"><th>Number</th><th>Ship type</th><th>Equipment</th></tr>`);
          let dockData = $.parseJSON(data);
          dockData.forEach(function(shipObj) {
              if (typeof shipObj == "object") {
                let lasers = countEquipped(shipObj, "lasers");
                let rockets = countEquipped(shipObj, "rockets");
                let shields = countEquipped(shipObj, "shields");
                let hyperspace = countEquipped(shipObj, "hyperspace");
                $(".wrapper_squadrons").append(`<tr class="ship_tab" id="${shipObj["number"]}" type="${shipObj["type"]}"><td>${shipObj["number"]}</td><td>${shipNames[shipObj["type"]]}</td><td><div class="equipment_wrapper">Lasers: ${lasers}/${params[0][shipObj["type"]]} <br> Missile platforms: ${rockets}/${params[1][shipObj["type"]]} <br> Shield generators: ${shields}/${params[2][shipObj["type"]]} <br> Hyperspace engines: ${hyperspace}/${params[3][shipObj["type"]]}</div></td></tr>`);
                selected = undefined;
                $("#"+selected).css("border-color", "grey");
              }
            $(".loading").remove();
          });
        }
        $(".loading").remove();
      });
      $(".dock_curr_sp").html(dock);
    }
  });
  $(document).on("change", ".select_filter", function() {
    selected = undefined;
    let type = this.value;
    if (type == "all") {
      $(".ship_tab").css("display", "flex");
    } else {
      let table = document.querySelector(".wrapper_squadrons").getElementsByTagName('tr');
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
    }
  });
  $(document).on("click", ".btn_close_popup", function() {
    $(".squadron_popup").remove();
  });
});
