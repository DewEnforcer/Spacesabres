$(document).ready(function () {
  //init vars
  let amount = 1;
  let itemID = undefined;
  let itemName = "";
  let shipInfo = undefined;
  const basePrice = [];
  const pricesTemplate = {
    0: {
      id: "cred",
      text: "Credits",
    },
    1: {
      id: "hyp",
      text: "Hyperids",
    },
    2: {
      id: "nat",
      text: "Natiums",
    },
  };
  //init util functions
  const shopAjax = (action, itemID, amount, additional) => {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../include/shopAjax.php",
        type: "POST",
        data: {
          action: action,
          item: itemID,
          amount: amount,
          additional: additional,
        },
        success: function (data) {
          resolve(data);
        },
        error: function (error) {
          reject(error);
        },
      });
    });
  };
  const countPrice = () => {
    basePrice.forEach((item, i) => {
      const { id } = pricesTemplate[i];
      $("#price_" + id).html(numberFormated(item * amount));
    });
  };
  const getAmount = () => {
    return Number($(".items_amount").val());
  };
  const setAmountVisual = () => {
    if (amount <= 0) {
      amount = 1;
    }
    $(".items_amount").val(amount);
  };
  const getShipParams = async () => {
    const response = await fetch("./js/shipInfo.json");
    const dataReturn = await response.json();
    shipInfo = dataReturn;
  };
  //init shop data
  getShipParams();
  //event listeners
  $(".item").click((ev) => {
    //activates on item box click
    itemID = ev.currentTarget.attributes.id.value;
    console.log(itemID);
    if (typeof shipInfo === "undefined") {
      return;
    }
    itemName = shipInfo.ships[itemID].name;
    let description = shipInfo.ships[itemID].description;
    let type = shipInfo.ships[itemID].class;
    let price = shipInfo.ships[itemID].price;
    basePrice[0] = price.credits;
    basePrice[1] = price.hyperid;
    basePrice[2] = price.natium;
    let rnd = Math.floor(Math.random() * 10);
    $(".items_description").html("<div class='item_desc_wrapper'></div>");
    $(".item_desc_wrapper").html(
      "<h2 class='item_name'>" +
        itemName +
        "</h2><div class='item_header'></div><div class='item_desc'></div>"
    );
    $(".item_header").html(
      `<img src="../image/shopImg/item${itemID}.gif?${rnd}" class="shop_item_gif">`
    );
    $(".item_desc").html(
      `<h3>${type}</h3><p>${description}</p><hr><span>Price:</span>`
    );
    basePrice.forEach((item, i) => {
      if (item > 0) {
        const { id, text } = pricesTemplate[i];
        $(".item_desc").append(
          `<span><span id='price_${id}'>${numberFormated(
            item
          )}</span>${text}</span>`
        );
      }
    });
    $(".item_desc").append(
      `<div class="amount_wrapper"><div><span>Amount:</span><button type="button" class="btn_amount_minus btn_math">-</button><input type="number" class="items_amount" value="1" min="1"><button type="button" class="btn_amount_plus btn_math">+</button></div></div>`
    );
    $(".item_desc").append(
      `<hr style="margin: 5px 0;"><div class="info_item_btn_wrapper"></div>`
    );
    $(".info_item_btn_wrapper").html(
      `<button type="button" class="btn_tech_params">Technical Parameters</button><button type="button" class="btn_item_purch">Purchase</button>`
    );
  });
  //manages the ship info display
  $(document).on("click", ".btn_tech_params", () => {
    const { hp, speed, shd, base_evasion } = shipInfo.ships[itemID];
    const data = [
      "Hitpoints: " + hp,
      "Shields: " + shd,
      "Speed: " + speed,
      "Evasion: " + base_evasion * 100 + "%",
    ];
    $(".item_desc_wrapper").append("<div class='tech_params_box'></div>");
    $(".tech_params_box").html(
      `<h3>Technical parameters for ${itemName}</h3><hr><img src="../image/graphics/closeMsg.png" class="btn_close_params">`
    );
    data.forEach((item) => {
      $(".tech_params_box").append(`<span>${item}</span>`);
    });
  });
  $(document).on("click", ".btn_close_params", () => {
    $(".tech_params_box").remove();
  });
  //manages item purchase
  $(document).on("click", ".btn_item_purch", () => {
    $(".shop_main_wrapper").append(
      `<div class='purchase_confirm_popup'>
        <h3 class="purch_question">Are you sure you want to purchase ${amount} ${itemName} for </h3>
      </div>`
    );
    basePrice.forEach((item, i) => {
      if (item > 0) {
        const { text } = pricesTemplate[i];
        let additional = i > 0 ? " & " : "";
        $(".purch_question").append(
          additional + numberFormated(item * amount) + " " + text
        );
      }
    });
    $(".purch_question").append("?");
    $(".purchase_confirm_popup").append(
      `<div><button type='button' name='cancelPurch' class='cancelPurch' style="color: red">Cancel</button><button type='button' name='confirmPurch' style="color: rgb(80,220,100)" class='confirmPurch'>Confirm</button></div>`
    );
  });
  $(document).on("click", ".confirmPurch", function () {
    shopAjax("ship", itemID, amount, "").then((data) => {
      const color = "rgb(80,220,100)";
      let msg = "<h3>Success!</h3>";
      if (data != "error" && data != "item_not_avail" && data != "no_res") {
        data = $.parseJSON(data);
        updateUserValutes(...data);
      } else if (data == "error") {
        msg =
          "<h3>Error!</h3><p>Unfortunately an error has occured. Please try again or report this error on the forums!</p>";
      } else if (data == "no_res") {
        msg =
          "<h3>Error!</h3><p>You don't have enough resources to purchase these items!</p>";
      } else if (data == "item_not_avail") {
        msg = "<h3>Error!</h3><p>This item isn't available!</p>";
      }
      $(".purchase_confirm_popup").html(
        `${msg}<button type='button' name='cancelPurch' style="color: ${color}" class='cancelPurch'>Ok</button>`
      );
    });
  });
  $(document).on("click", ".cancelPurch", () => {
    $(".purchase_confirm_popup").remove();
  });
  //amount controllers
  $(document).on("click", ".btn_amount_plus", () => {
    // adds to the amount
    amount += 1;
    setAmountVisual();
    countPrice();
  });
  $(document).on("click", ".btn_amount_minus", () => {
    //subtracts from the amount
    amount -= 1;
    setAmountVisual();
    countPrice();
  });
  $(document).on("keyup", ".items_amount", () => {
    //custom user typed value
    amount = getAmount();
    countPrice();
  });
});
