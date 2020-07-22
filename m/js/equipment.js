  function allowDrop(ev) {
    ev.preventDefault();
  }

  function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
  }

  function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    let itemData = data.split("_");
    let target = ev.target.id;
    let slotData = target.split("_");
    if (itemData[0] == slotData[0]) {
        ev.target.appendChild(document.getElementById(data));
        $(".btn_save_setup").css({"cursor": "pointer", "background-color": "rgb(80,220,100)","border-color": "green"});
    } else {
      ""; // TODO: here goes some error message
    }
  }
  function inventorize (elementInv, inventory) {
    for (var i = 0; i < elementInv.length; i++) {
      let type = $(elementInv[i]).attr("type");
      inventory[type]++;
    }
    return inventory;
  }
  function eqAjax(action, newEq, newInv) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../include/equipmentAjax.php",
        type: 'POST',
        data: {
          action: action,
          newEq: newEq,
          newInv: newInv,
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
  $(document).ready(function() {
    $(document).on("click", ".item_img", function() {
      let location = this.parentElement;
      let locationData = location.id;
      let locationDataSplit = locationData.split("_");
      let status;
      if (locationDataSplit[1] == "inv") {
        var slots = document.getElementById(locationDataSplit[0]+"_ship").getElementsByTagName('div');
        for (var i = 0; i < slots.length; i++) {
          if (slots[i].children.length == 0) {
            status = true;
            break;
          }
        }
        if (status == true) {
          slots[i].appendChild(this);
          $(".btn_save_setup").css({"cursor": "pointer", "background-color": "rgb(80,220,100)","border-color": "green"})
        }
      } else if (locationDataSplit[1] == "ship") {
        var slots = document.querySelector("#"+locationDataSplit[0]+"_inv").getElementsByTagName('div');
        let number = slots.length;
        for (var i = 0; i < slots.length; i++) {
          if (slots[i].children.length == 0) {
            status = true;
            break;
          }
        }
        if (status == true) {
          slots[i].appendChild(this);
          $(".btn_save_setup").css({"cursor": "pointer", "background-color": "rgb(80,220,100)","border-color": "green"})
        } else {
        $("."+locationDataSplit[0]+"_slots_items").append(`<div class="tmp_${locationDataSplit[0]}_item slot_eq" id="${locationDataSplit[0]}_inv_${number}" ondrop='drop(event)' ondragover='allowDrop(event)'</div>`);
        document.querySelector("#"+locationDataSplit[0]+"_inv_"+number).appendChild(this);
        $(".btn_save_setup").css({"cursor": "pointer", "background-color": "rgb(80,220,100)","border-color": "green"})
      }
      }
    });
    var action = "equip";
    $(".btn_save_setup").click(function () {
      if ($(this).css("cursor") == "pointer" || action == "template") {
        $("main").append("<img src='../image/graphics/loading.gif' class='eq_loader'");
        let equipmentArr = [[], [], [], []];
        let invArr = [];
        let slotsLasers = document.getElementById("laser_ship").getElementsByTagName('div');
        let slotsRockets = document.getElementById("rocket_ship").getElementsByTagName('div');
        let slotsEnergy = document.getElementById("shields_ship").getElementsByTagName('div');
        let slotsHyperspace = document.getElementById("hyperspace_ship").getElementsByTagName('div');

        for (var i = 0; i < slotsLasers.length; i++) {
          if (slotsLasers[i].innerHTML != "") {
              let getItem = slotsLasers[i].innerHTML.split("\"");
            let split = getItem[11].split("_");
            equipmentArr[0].push(split[2]);
            invArr.push([split[3], split[0]]);
          } else {
            equipmentArr[0].push(0);
          }
        }
        for (var i = 0; i < slotsRockets.length; i++) {
          if (slotsRockets[i].innerHTML != "") {
            let getItem = slotsRockets[i].innerHTML.split("\"");
            let split = getItem[11].split("_");
            equipmentArr[1].push(split[2]);
            invArr.push([split[3], split[0]]);
          } else {
            equipmentArr[1].push(0);
          }
        }
        for (var i = 0; i < slotsEnergy.length; i++) {
          if (slotsEnergy[i].innerHTML != "") {
            let getItem = slotsEnergy[i].innerHTML.split("\"");
            let split = getItem[11].split("_");
            equipmentArr[2].push(split[2]);
            invArr.push([split[3], split[0]]);
          } else {
            equipmentArr[2].push(0);
          }
        }
        for (var i = 0; i < slotsHyperspace.length; i++) {
          if (slotsHyperspace[i].innerHTML != "") {
              let getItem = slotsHyperspace[i].innerHTML.split("\"");
            let split = getItem[11].split("_");
            equipmentArr[3].push(split[2]);
            invArr.push([split[3], split[0]]);
          } else {
            equipmentArr[3].push(0);
          }
        }

        eqAjax(action, JSON.stringify(equipmentArr),JSON.stringify(invArr)).then(data => {
          $(".eq_loader").remove();
          if (data == "success" && action == "equip") {
            $(this).css({
              "cursor": "auto",
              "background-color": "grey",
              "border": "none"
            });
            $("main").append("<div class='eq_result'></div>");
            $(".eq_result").html("<h2>Success!</h2><p>Equipment configuration for this ship has been successfully set!</p><button type='button' class='btn_eq_confirm'>Ok</button>");
          } else if (data == "error") {
            $("main").append("<div class='eq_result'></div>");
            $(".eq_result").html("<h2>Error!</h2><p>Unfortunately an error occured while equipping your ship! Please try again or report this bug on the forums!</p><button type='button' class='btn_eq_confirm'>Ok</button>");
          }
        });
      } else {
      }
  });
  $(".btn_tmpl").click(function() {
    action = "template"
    $(".btn_save_setup").trigger("click");
    action = "equip";
  });
  $(".template_select").change(function() {
    let typeArr = ["laser", "rocket", "shields", "hyperspace"];
    if (this.value == "template") {
      let k = 0;
      let inventory = {
        laser: inventorize(document.querySelector("#laser_inv").children, {"19": 0, "20": 0, "21": 0}),
        rocket: inventorize(document.querySelector("#rocket_inv").children, {"26": 0}),
        shields: inventorize(document.querySelector("#shields_inv").children, {"25": 0}),
        hyperspace: inventorize(document.querySelector("#hyperspace_inv").children, {"22": 0, "23": 0, "24": 0})
      }
      var i = 0;
        templates.forEach(function(slot) {
          for (var j = 0; j < slot.length; j++) {
            let prevItem = $("#"+typeArr[k]+"_ship_"+i+"").attr("type");
            let prevItemImg = document.querySelector("#"+typeArr[k]+"_ship_"+i+"").children;
            if (prevItem > 0) {
              inventory[typeArr[k]][prevItem]++; //return item to inventory
              $("#"+typeArr[k]+"_inv").append(`<div ondrop='drop(event)' ondragover='allowDrop(event)' id="${typeArr[k]}_inv_${i}" class="${typeArr[k]}_item slot_eq ${typeArr[k]}_slot_inv_${prevItem}" type="${prevItem}"><img src="../image/equipment/item${prevItem}.jpg" oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class="${typeArr[k]}_img item_img" id="${typeArr[k]}_aval_${prevItem}_${i}"></div>`);
            }
            if (inventory[typeArr[k]][slot[j]] - 1 > 0) {
              inventory[typeArr[k]][slot[j]]--;
              $("#"+typeArr[k]+"_ship_"+i+"").html("<img src='../image/equipment/item"+slot[j]+".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='"+typeArr[k]+"_img item_img' id='"+typeArr[k]+"_eq_"+slot[j]+"_"+i+"'>"); //inserts new item
              let avalSlots = $("."+typeArr[k]+"_slot_inv_"+slot+""); //empties the inventoy slot for item of this id
              $(avalSlots[j]).html("");
            }
            i++;
          }
          k++;
      });
      $(".btn_save_setup").css({"cursor": "pointer", "background-color": "rgb(80,220,100)","border-color": "green"})
    }
  });
  $(document).on("click", ".btn_eq_confirm", function() {
    $(".eq_result").remove();
  });
});
