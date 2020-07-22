$(document).ready(function() {
  function shopAjax(action, itemID, amount, additional) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../include/shopAjax.php",
        type: 'POST',
        data: {
          action: action,
          item: itemID,
          amount: amount,
          additional: additional
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
function numberFormated(price) {
  return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
function countPrice () {
  let creds = $(".startCrdPrice").val();
  let hyperid = $(".startHypPrice").val();
  let natium = $(".startNatPrice").val();
  let amount = $(".items_amount").val();

  let newCreds = numberFormated(Number(creds)*Number(amount));
  let newHyperid = numberFormated(Number(hyperid)*Number(amount));
  let newNatium = numberFormated(Number(natium)*Number(amount));

  $("#price_cred").html(newCreds);
  $("#price_hyp").html(newHyperid);
  $("#price_nat").html(newNatium);

  return;
}
var itemID;
var action;
var additional = "";
var name;
$(".item").click(function() {
  $(".startCrdPrice").remove();
  $(".startHypPrice").remove();
  $(".startNatPrice").remove();
  itemID = $(this).prop("id");
  if (itemID > 0 && itemID < 7) {
    action = "ship";
  } else if (itemID > 6 && itemID < 11) {
    action = "modules";
  } else if (itemID > 10 && itemID < 16) {
    action = "research";
  } else if (itemID > 18 && itemID < 27) {
    action = "equipment";
  } else if (itemID == 27) {
    action = "dock";
  } else if (itemID == 28) {
    action = "fuel";
  }
  $(".items_description").html("<div class='item_desc_wrapper'></div>");
  $.getJSON( "../js/shop.json", function(jsonItem) {
    name = jsonItem.ships[itemID].name;
    let description = jsonItem.ships[itemID].description;
    let type = jsonItem.ships[itemID].class;
    let price = jsonItem.ships[itemID].cost;
    let creditPrice = price.credits;
    let hyperidPrice = price.hyperid;
    let natiumPrice = price.natium;
    $("main").append(`<input type="hidden" class="startCrdPrice" value="${creditPrice}"><input type="hidden" class="startHypPrice" value="${hyperidPrice}"><input type="hidden" class="startNatPrice" value="${natiumPrice}">`);
    let rnd = Math.floor(Math.random()*10);
    $(".item_desc_wrapper").html("<h2 class='item_name'>"+name+"</h2><div class='item_header'></div><div class='item_desc'></div>");
    $(".item_header").html(`<img src="../image/shopImg/item${itemID}.gif?${rnd}" class="shop_item_gif">`); // change for server optimalization
    $(".item_desc").html(`<h3>${type}</h3><p>${description}</p><hr><span>Price:</span>`);
    if (creditPrice > 0) {
      $(".item_desc").append("<span><span id='price_cred'>"+numberFormated(creditPrice)+"</span>Credits</span>");
    }
    if (hyperidPrice > 0) {
      $(".item_desc").append("<span><span id='price_hyp'>"+numberFormated(hyperidPrice)+"</span>Hyperids</span>");
    }
    if (natiumPrice > 0) {
      $(".item_desc").append("<span><span id='price_nat'>"+numberFormated(natiumPrice)+"</span>Natiums</span>");
    }
    $(".item_desc").append(`<div class="amount_wrapper"><div><span>Amount:</span><button type="button" class="btn_amount_minus btn_math">-</button><input type="number" class="items_amount" value="1" min="1"><button type="button" class="btn_amount_plus btn_math">+</button></div></div>`);
    if (action == "ship") {
      $(".item_desc").append(`<select class="select_dock"></select><div class="config_template_wrapper"><span>Configuration template:</span><select class='template_equip'><option value="false">None</option><option value="true">Template</option></select></div>`);
      for (var i = 1; i <= docks; i++) {
        $(".select_dock").append("<option value="+i+">Dock "+i+"</option>");
      }
    }
    $(".item_desc").append(`<hr style="margin: 5px 0;"><div class="info_item_btn_wrapper"></div>`);
    $(".info_item_btn_wrapper").html(`<button type="button" class="btn_tech_params">Technical Parameters</button><button type="button" class="btn_item_purch">Purchase</button>`);
   });
});
$(document).on("click", ".btn_amount_plus", function() {
  let amount = $(".items_amount").val();
  let newAmount = Number(amount)+1;
  $(".items_amount").val(newAmount);
  countPrice();
});
$(document).on("click", ".btn_amount_minus", function() {
  let amount = $(".items_amount").val();
  let newAmount = Number(amount)-1;
  if (newAmount > 0) {
      $(".items_amount").val(newAmount);
      countPrice();
  }
});
$(document).on("keyup", ".items_amount", function() {
  countPrice();
});
$(document).on("click", ".btn_tech_params", function() {
  if (itemID < 11) {
    $.getJSON( "../js/techparams.json", function(jsonTechParam) {
      let params = jsonTechParam.techparams[itemID];
      $(".item_desc_wrapper").append("<div class='tech_params_box'></div>");
      $(".tech_params_box").html(`<h3>Technical parameters for ${name}</h3><hr><img src="../image/graphics/closeMsg.png" class="btn_close_params">`);
      console.log(params);
      $.each(params, function(index, value) {
        $(".tech_params_box").append(`<span>${value}</span>`);
      });
     });
  }
});
$(document).on("click", ".btn_item_purch", function() {
  $(".shop_main_wrapper").append("<div class='purchase_confirm_popup'></div>");
  let amount = Number($(".items_amount").val());
  let name = $(".item_name").html();
  let creds = Number($(".startCrdPrice").val()) * amount;
  let hyperid = Number($(".startHypPrice").val()) * amount;
  let natium = Number($(".startNatPrice").val()) * amount;
  $(".purchase_confirm_popup").html(`<h3 class="purch_question">Are you sure you want to purchase ${amount} ${name} for </h3>`);
  if (creds > 0) {
    $(".purch_question").append(numberFormated(creds)+" Credits ");
  }
  if (hyperid > 0) {
    $(".purch_question").append(", "+numberFormated(hyperid)+" Hyperids ");
  }
  if (natium > 0) {
    $(".purch_question").append(", "+numberFormated(natium)+" Natiums ");
  }
  $(".purch_question").append("?");
  $(".purchase_confirm_popup").append(`<div><button type='button' name='cancelPurch' class='cancelPurch' style="color: red">Cancel</button><button type='button' name='confirmPurch' style="color: rgb(80,220,100)" class='confirmPurch'>Confirm</button></div>`);
});
$(document).on("click", ".confirmPurch", function() {
    let amount = Number($(".items_amount").val());
    if (action == "ship") {
        additional = [$(".select_dock").val(), $(".template_equip").val()];
    }
  shopAjax(action, itemID, amount, JSON.stringify(additional)).then(data => {
    if (data == "success") {
      $(".purchase_confirm_popup").html(`<h3>Success!</h3><button type='button' name='cancelPurch' style="color: rgb(80,220,100)" class='cancelPurch'>Ok</button>`);
    } else if (data == "full") {
      $(".purchase_confirm_popup").html(`<h3>Error!</h3><p>Unfortunately you don't have enough space in your docks!</p><button type='button' name='cancelPurch' style="color: rgb(80,220,100)" class='cancelPurch'>Ok</button>`);
    } else if (data == "error") {
      $(".purchase_confirm_popup").html(`<h3>Error!</h3><p>Unfortunately an error has occured. Please try again or report this error on the forums!</p><button type='button' name='cancelPurch' style="color: rgb(80,220,100)" class='cancelPurch'>Ok</button>`);
    } else if (data == "nores") {
      $(".purchase_confirm_popup").html(`<h3>Error!</h3><p>You don't have enough resources to purchase these items!</p><button type='button' name='cancelPurch' style="color: rgb(80,220,100)" class='cancelPurch'>Ok</button>`);
    }
  });
});
$(document).on("click", ".cancelPurch", function() {
  $(".purchase_confirm_popup").remove();
});
$(document).on("click", ".btn_close_params", function() {
  $(".tech_params_box").remove();
});
});
