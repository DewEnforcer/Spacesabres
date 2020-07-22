$(document).ready(function() {
  function briefingAjax(action, additionalData, missionType) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "./include/briefingAjax.php",
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
var selected = [];
var selectedStatus = ["placeholder"];
for (var i = 0; i < docks; i++) {
  selected.push([]);
  selectedStatus.push([false, false, false, false, false, false]);
}
var missionType = 1;
var dock = 1;
var params = [[0,2,4,10,15,25], [4,2,0,0,0,0],[1,1,1,2,2,3],[1,1,1,1,1,1]];
var names = ["Hornet","Spacefire","Starhawk","Peacemaker","Centurion","Na-Thalis Destroyer"];
var dataN = ["hornet","spacefire","starhawk","peacemaker","centurion","nathalis"];

$(".mission_params").click(function() {
  $(".briefing_header_docks").append("<div class='briefing_mission_wrapper'></div>");
  $(".briefing_mission_wrapper").html(`<img src="../image/graphics/closeMsg.png" class="close_popup"><h3>Select the mission</h3><div class="mission_select"><button type="button" name="button" class="btn_mission_type" id="mission_1" value="1" style="border-color: white;">Standard attack</button><button type="button" name="button" value="2" id="mission_2" class="btn_mission_type">Company defense</button></div><div class="mission_params_briefing"></div>`);
  $(".mission_params_briefing").html(`<h2>Coordinates</h2><form class="form_brief_coord" action="index.html" method="post"><div><span>Coordinates X:</span><input type="number" class="coords_x" placeholder="Enter X coordinates" ${coordsX}></div><div><span>Coordinates Y:</span><input type="number" class="coords_y" placeholder="Enter Y coordinates" ${coordsY}></div><div><span>System:</span><input type="number" class="map" placeholder="Enter target system" ${coordsMap}></div><button type="button" name="button" class="btn_submit_attack">Deploy fleet!</button></form>`);
  $(".briefing_mission_wrapper").fadeIn(1000, function() {

  });
  setTimeout(function () {
        $(".briefing_mission_wrapper").css("display", "flex");
  }, 100);
});

$(document).on("change", ".dock_select", function() {
  if (dock != Number(this.value)) {
    $("main").append("<img src='../image/graphics/loading.gif' class='loading'>");
    dock = Number(this.value);
    briefingAjax("switch", dock, "").then(data => {
      if (data != "error") {
        $(".ship_list").html(`<tr class="tr_header"><th>Deploy</th><th>Number</th><th>Ship type</th><th>Equipment</th></tr>`);
        let dockData = $.parseJSON(data);
        $(".loading").remove();
        dockData.forEach(function(shipObj) {
            if (typeof shipObj == "object") {
              let lasers = countEquipped(shipObj, "lasers");
              let rockets = countEquipped(shipObj, "rockets");
              let shields = countEquipped(shipObj, "shields");
              let hyperspace = countEquipped(shipObj, "hyperspace");
              $(".ship_list").append(`<tr class="ship_tab" id="${shipObj["number"]}" type="${dataN[shipObj["type"]]}"><td><input type="checkbox" name="ship_checkbox" class="ship_checkbox" ship="${shipObj["type"]}" id="ship_${shipObj["number"]}"></td><td>${shipObj["number"]}</td><td>${names[shipObj["type"]]}</td><td><div class="equipment_wrapper">Lasers: ${lasers}/${params[0][shipObj["type"]]} <br> Missiles: ${rockets}/${params[1][shipObj["type"]]} <br> Shields: ${shields}/${params[2][shipObj["type"]]} <br> Engines: ${hyperspace}/${params[3][shipObj["type"]]}</div></td></tr>`);
              if (selected[dock-1].indexOf(Number(shipObj["number"])) > -1) {
                document.querySelector("#ship_"+shipObj["number"]+"").checked = true;
              }
            }
        });
      }
    });
    $(".curr_dock").html(dock);
  }
});
// ↓ select individual handler
$(document).on("click", ".ship_checkbox", function() {
  let status = this.checked;
  let shipNumber = $(this).prop("id");
  let split = shipNumber.split("_");
  if (status == true) {
    selected[dock-1].push(Number(split[1]));
  } else if (status != true) {
    let index = selected[dock].indexOf(split[1]);
        selected[dock-1].splice(index, 1);
  }
  console.log(selected);
});
// ↓ select all shiptype handler
$(document).on("change", ".select_ships" ,function() {
  let ships = Number(this.value);
  let checkboxes = document.getElementsByName("ship_checkbox");
    for (var i = 0; i < checkboxes.length; i++) {
      let shipNumber = $(checkboxes[i]).prop("id");
      let split = shipNumber.split("_");
      if ($(checkboxes[i]).attr("ship") == ships && checkboxes[i].checked == false ) {
        selected[dock-1].push(Number(split[1]));
        checkboxes[i].checked = true;
      }
    }
    selectedStatus[dock][ships] = true;
  console.log(selected);
});
$(document).on("change", ".unselect_ships" ,function() {
  let ships = Number(this.value);
  let checkboxes = document.getElementsByName("ship_checkbox");
  if (selected[dock-1].length > 0) {
    for (var i = 0; i < checkboxes.length; i++) {
      let shipNumber = $(checkboxes[i]).prop("id");
      let split = shipNumber.split("_");
      let index = selected[dock-1].indexOf(split[1]);
      if ($(checkboxes[i]).attr("ship") == ships && checkboxes[i].checked == true) {
          checkboxes[i].checked = false;
          selected[dock-1].splice(index, 1);
      }
    }
    selectedStatus[dock][ships] = false;
    }

  console.log(selected);
});
$(document).on("change", ".select_filter", function() {
  let type = $(this).val();
  let table = document.querySelector(".ship_list").getElementsByTagName('tr');
  let amount = table.length;
  for (var i = 1; i < amount; i++) {
    let typeItem = $(table[i]).attr("type");
    if (typeItem != type) {
      $(table[i]).css("display", "none");
    } else {
      $(table[i]).css("display", "flex");
    }
  }
});
$(".btn_filter_all").click(function() {
  $(".ship_tab").css("display", "flex");
});
$(document).on("click", ".btn_submit_attack", function() {
  if (missionType == 1) {
    let coordsX = $(".coords_x").val();
    let coordsY = $(".coords_y").val();
    let map = $(".map").val();
    if (map == 0 || map == "" || coordsX == 0 || coordsX == "" || coordsY == 0 || coordsY == "" ) {
      // TODO: error handler
    } else {
      let attackArr = [selected, coordsX, coordsY, map];
      $("main").append("<img src='../image/graphics/loading.gif' class='loading'>");
      briefingAjax("attack", JSON.stringify(attackArr), missionType).then(data => {
        $(".loading").remove();
        if (data == "success") {
          window.location.href = "internalFleets.php";
        } else if (data == "error") {
          $("main").append("<div class='briefing_result'></div>");
          $(".briefing_result").html(`<h2>Error!</h2><p>Unfortunately an erro has occured. Please try again or report this error on the forums!</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
        } else if (data == "admin") {
          $("main").append("<div class='briefing_result'></div>");
          $(".briefing_result").html(`<h2>Error!</h2><p>Attacking an administrator would result in your fleet vanishing from the universe!</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
        } else if (data == "hyperspace") {
          $("main").append("<div class='briefing_result'></div>");
          $(".briefing_result").html(`<h2>Error!</h2><p>None of the selected ships have been equipped with hyperspace engine!</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
        } else if (data == "notenoughfuel") {
          $("main").append("<div class='briefing_result'></div>");
          $(".briefing_result").html(`<h2>Error!</h2><p>You don't have enough fuel for this operation!</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
        }
      });
    }
  } else if (missionType == 2) {
    $("main").append("<img src='../image/graphics/loading.gif' class='loading'>");
    let hours = $(".company_def_hrs").val();
    briefingAjax("compdef", JSON.stringify([selected, hours, missionType])).then(data => {
      $(".loading").remove();
      if (data == "success") {
        window.location.href = "internalCompanydefense.php";
      } else if (data == "error") {
        $("main").append("<div class='briefing_result'></div>");
        $(".briefing_result").html(`<h2>Error!</h2><p>Unfortunately an erro has occured. Please try again or report this error on the forums!</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
      } else if (data == "noships") {
        $("main").append("<div class='briefing_result'></div>");
        $(".briefing_result").html(`<h2>Error!</h2><p>None of the selected ships satisfy the requirements of the company!</p><img src="../image/graphics/closeMsg.png" class="btn_close_popup">`);
      }
    });
  }
});
$(document).on("click", ".close_popup", function() {
  $(".briefing_mission_wrapper").fadeOut(500,function() {
      $(".briefing_mission_wrapper").remove();
  });
});
$(document).on("click", ".btn_mission_type", function() {
  if (missionType != this.value) {
      $("#mission_"+missionType+"").css("border-color", "grey");
      missionType = this.value;
      $("#mission_"+missionType+"").css("border-color", "white");
      if (missionType == 1) {
        $(".mission_params_briefing").html(`<h2>Coordinates</h2><form class="form_brief_coord" action="index.html" method="post"><div><span>Coordinates X:</span><input type="number" class="coords_x" placeholder="Enter X coordinates" ${coordsX}></div><div><span>Coordinates Y:</span><input type="number" class="coords_y" placeholder="Enter Y coordinates"${coordsY}></div><div><span>System:</span><input type="number" class="map" placeholder="Enter target system" ${coordsMap}></div><button type="button" name="button" class="btn_submit_attack">Deploy fleet!</button></form>`);
      } else if (missionType == 2) {
        if (available == true) {
          $(".mission_params_briefing").html(`<h2>Company defense</h2><span>How long should this mission take? (Min.:1 hour, Max.: 10 hours)</span><input type="number" min="1" max="10" class="company_def_hrs" value="1" onkeyup="if(this.value > 10) {this.value = 10}"><button type="button" name="button" class="btn_submit_attack">Deploy fleet!</button>`);
        } else {
          if (available == "onway") {
            $(".mission_params_briefing").html(`<h2>Company defense</h2><p>Your fleet is already helping the company! <a href="internalCompanydefense.php" style="color: white">More details</a></p>`);
          } else {
            $(".mission_params_briefing").html(`<h2>Company defense</h2><p>Currently the company has enough ships to defend all it's territories till ${time} (Server time).</p>`);
          }
        }
      }
  }
});
});
