$(document).ready(function() {
    function numberFormated(resultCost) {
      return resultCost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  }
  function resAjax(itemID) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../include/researchAjax.php",
        type: 'POST',
        data: {
          item: itemID
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
var itemID;
var creditPrice;
var hyperidPrice;
var natiumPrice;
var itemLvl;
var name;
$(".item").click(function() {
  let coef;
  itemID = $(this).prop("id");
  itemLvl = lvls[itemID];
  if (itemLvl == 1 || itemLvl == 0) {
    coef = 1;
  } else if (itemLvl == 2) {
    coef = 2;
  } else {
    coef = itemLvl*2;
  }
  $.getJSON( "../js/shop.json", function(jsonItem) {
    let curr = $("#research_info_time").html();
    name = jsonItem.ships[itemID].name;
    let description = jsonItem.ships[itemID].description;
    let type = jsonItem.ships[itemID].class;
    let price = jsonItem.ships[itemID].cost;
    creditPrice = price.credits * coef;
    hyperidPrice = price.hyperid * coef;
    natiumPrice = price.natium * coef;
    $(".content").html("<h2 class='item_name'>"+name+"</h2>");
    $(".content").append(`<h3>${type}</h3><p>${description}</p><hr><span>Current level: ${itemLvl}</span><hr><span>Price:</span>`);
    if (creditPrice > 0) {
      $(".content").append("<span><span id='price_cred'>"+numberFormated(creditPrice)+"</span> Credits</span>");
    }
    if (hyperidPrice > 0) {
      $(".content").append("<span><span id='price_hyp'>"+numberFormated(hyperidPrice)+"</span> Hyperids</span>");
    }
    if (natiumPrice > 0) {
      $(".content").append("<span><span id='price_nat'>"+numberFormated(natiumPrice)+"</span> Natiums</span>");
    }
    $(".content").append(`<span>Research time: ${time[itemID]}</span>`);
    if (itemLvl < 50 && curr == undefined) {
      $(".content").append(`<button type="button" class="btn_item_purch">Research</button>`)
    } else if (itemLvl == 50) {
      $(".content").append(`<hr><span>You have reached the maximal level of this research</span>`)
    } else if (curr != undefined) {
      $(".content").append(`<hr><span>You are already researching something!</span>`)
    }
   });
});
$(document).on("click", ".closeitems", function(closeItems) {
$(".purchase_confirm_popup").remove();
});
$(document).on("click", ".btn_item_purch", function(confirmPurch) {
  $(".research_main_wrapper").append("<div class='purchase_confirm_popup'></div>");
  $(".purchase_confirm_popup").html(`<h3 class="purch_question">Are you sure you want to research ${name} for </h3>`);
  if (creditPrice > 0) {
    $(".purch_question").append(numberFormated(creditPrice)+" Credits ");
  }
  if (hyperidPrice > 0) {
    $(".purch_question").append(", "+numberFormated(hyperidPrice)+" Hyperids ");
  }
  if (natiumPrice > 0) {
    $(".purch_question").append(", "+numberFormated(natiumPrice)+" Natiums ");
  }
  $(".purch_question").append("?");
  $(".purchase_confirm_popup").append(`<div><button type='button' name='cancelPurch' class='cancelPurch' style="color: red">Cancel</button><button type='button' name='confirmPurch' style="color: rgb(80,220,100)" class='confirmPurch'>Confirm</button></div>`);
  $(document).on("click", ".confirmPurch", function() {
    resAjax(itemID).then(data => {
      if (data != "" && data != "error") {
        $(".purchase_confirm_popup").html(`<h3>Success!</h3><button type='button' name='cancelPurch' style="color: rgb(80,220,100)" class='cancelPurch'>Ok</button>`);
      }
    });
  });
});
$(document).on("click", ".cancelPurch", function() {
$(".purchase_confirm_popup").remove();
});
});
