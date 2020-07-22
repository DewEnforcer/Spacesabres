$(document).ready(function() {
  $(".ally_row").click(function(showDetails) {
    $(".opacity_box").fadeIn(function() {
      $(".opacity_box").css("display", "block");
    })
    let id = $(this).prop("id");
    $.ajax({
      type: "POST",
      url: "../include/ajaxAlliance.php",
      data: {index: id, action: "showAlly"},
      success: function (showAlliance) {
      let allyDetail = $.parseJSON(showAlliance);
      console.log(allyDetail);
      $("main").append("<div class='ally_popup_window'></div>");
      $(".ally_popup_window").animate({
        width: "40vw",
        height: "50vh",
      }, 1000);
      setTimeout(function () {
        $(".ally_popup_window").html(`<img src="../image/graphics/closeMsg.png" class="close_ally"><div class="popup_header"></div><div class="popup_footer"></div>`);
        $(".popup_header").html(`<div class="header_logo"></div><div class="header_info"></div>`);
        $(".header_logo").html(`<img class="ally_img" src="../uploads/clanavtr/clanavtr${allyDetail["clanImgID"]}.png">`);
        $(".header_info").html(`<span style="font-size:24px;">Alliance info</span><span>Alliance tag: ${allyDetail["clanTag"]}</span><span>Alliance name: ${allyDetail["clanName"]}</span><span>Alliance leader: ${allyDetail["clanLeader"]}</span><span>Leaderboard position: ${allyDetail["position"]}</span><span>Total points: ${allyDetail["totalPoints"]}</span><span>Contact: ${allyDetail["clanContact"]}</span>`);
        $(".popup_footer").html(`<p>${allyDetail["clanDetail"]}</p><a href="internalJoinally.php?index=${allyDetail["clanName"]}">Join alliance</a>`);
      }, 1000);
      }
    });
  });
  $(document).on("click", ".close_ally" , function(close_window) {
    $(".ally_popup_window").animate({
      width: "0vw",
      height: "0vh",
      border: "none"
    }, 700);
    $(".ally_popup_window").promise().done(function() {
      $(".ally_popup_window").remove();
      $(".opacity_box").css("display", "none");
    });
  });
var typingTimer;
var doneTypingInterval = 2500;

$('.ally_name').keyup(function(){
    clearTimeout(typingTimer);
    if ($('.ally_name').val()) {
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
});

function doneTyping () {
  let allyName = $(".ally_name").val();
  $.ajax({
    type: "POST",
    url: "../include/ajaxAlliance.php",
    data: {index: allyName, action: "showAllyJoin"},
    success: function (showAllianceForm) {
      if (showAllianceForm != "null") {
        let jsonFormParams = $.parseJSON(showAllianceForm);
        console.log(jsonFormParams);
        $(".load_ally_params").html(`<span>Alliance tag: ${jsonFormParams["clanTag"]}</span><span>Alliance Name: ${jsonFormParams["clanName"]}</span><span>Alliance leader: ${jsonFormParams["clanLeader"]}</span>`);
      } else {
        $(".load_ally_params").html("<p>No alliance with this name has been found</p>");
      }
    }
  });
}
$(".btn_manage_rank").click(function() {
  let index = $(this).prop("id");
  let nick = $(".nickname_popup_"+index+"").prop("id");
  $("main").append(`<div class="box_changerank_ally"></div>`);
  $(".opacity_box").fadeIn(function() {
    $(".opacity_box").css("display", "block");
  })
  $(".box_changerank_ally").animate({
    width: "40vw",
    height: "50vh",
  }, 1000);
  setTimeout(function () {
    $(".box_changerank_ally").html(`<h2>Manage alliance rank for ${nick}</h2><img src="../image/graphics/closeMsg.png" class="close_rank_box">`);
    $(".box_changerank_ally").append(`<form class="form_ally_rankmng" action="./include/allianceHandler.php" method="post"><label>Select alliance rank you wish to set</label><select name="rank_selection"><option value="1">Ensign</option><option value="2">Lieutenant</option><option value="3">Captain</option><option value="4">Admiral</option></select><input type="hidden" name="index_rank" value="${index}"><button type="submit" name="btn_submit_rank_change">Submit</button></form>`);
    $(".box_changerank_ally").append(`<div class="ranks_functions"></div>`);
    $(".ranks_functions").html("<h3>List of rank functions</h3>");
    $(".ranks_functions").append(`<p>Ensign - Beginner member rank , has no permissions.</p>`);
    $(".ranks_functions").append(`<p>Lieutenant - Standard member rank , can send alliance messages, send alliances and NAP's, can kick commanders with Ensign rank.</p>`);
    $(".ranks_functions").append(`<p>Captain - Higher member rank , can send alliance messages, send alliances,NAP's, can kick commanders with Ensign and Lieutenant rank, can accept members.</p>`);
    $(".ranks_functions").append(`<p>Admiral - Highest standard member rank , can send alliance messages, send alliances, NAP's as well as end them, can kick commanders with Ensign, Lieutenant and Captain rank, can accept members.</p>`);
  }, 1000);
});
$(document).on("click", ".close_rank_box", function() {
  $(".box_changerank_ally").animate({
    width: "0vw",
    height: "0vh",
  }, 1000);
  $(".box_changerank_ally").promise().done(function() {
    $(".box_changerank_ally").remove();
    $(".opacity_box").fadeOut(function() {
      $(".opacity_box").css("display", "none");
    })
  });
})
//
$(".btn_disband_ally").click(function() {
  $("main").append(`<div class="box_warn_disband"></div>`);
  $(".box_warn_disband").html("<h2>Are you sure you want to disband this alliance?</h2>");
  $(".box_warn_disband").append(`<div class="btn_wrapper_disband"></div>`);
  $(".btn_wrapper_disband").html(`<a href="../include/allianceHandler.php?action=disband">Confirm</a><button type="button" class="btn_disband_decline">Decline</button>`);
  $(".box_warn_disband").append(`<h3>Once you confirm disbanding the alliance, there is no way to undo this action!</h3>`);
  $(".box_warn_disband").fadeIn(1000,function() {
    $(".box_warn_disband").css("display", "flex");
  });
});
$(document).on("click", ".btn_disband_decline", function() {
  $(".box_warn_disband").fadeOut(1000, function() {
      $(".box_warn_disband").css("display", "none");
      $("box_warn_disband").remove();
  });
});
$(".btn_give_leader").click(function() {
  $("main").append(`<div class="box_changerank_ally"></div>`);
  $(".opacity_box").fadeIn(function() {
    $(".opacity_box").css("display", "block");
  })
  $(".box_changerank_ally").animate({
    width: "40vw",
    height: "50vh",
  }, 1000);
  setTimeout(function () {
    $(".box_changerank_ally").html(`<h2>Are you sure you want to give up your leader rank?</h2><img src="../image/graphics/closeMsg.png" class="close_rank_box">`);
    $(".box_changerank_ally").append(`<form class="form_ally_rankmng" action="./include/allianceHandler.php" method="post"><label>Select commander you want to give leader rank</label><select name="user_selection" class="user_selection"></select><button type="submit" name="btn_submit_give_leader">Submit</button></form>
    <h3>Once you give up leadership , you won't be able to undo this action!</h3>`);
    var k = 0;
    for (var i = 1; i <= allMembers.length; i++) {
      $(".user_selection").append(`<option value="${i}">${allMembers[k]}</option>`);
      k++;
    }
  }, 1000);
});
$(".btn_read_app").click(function() {
  let index = $(this).prop("id");
  $.ajax({
    type: "POST",
    url: "../include/allianceHandler.php",
    data: {index: index, action: "show_app"},
    success: function (showAllianceApp) {
      if (showAllianceApp != "noperms") {
        $("main").append(`<div class="box_changerank_ally"></div>`);
        $(".opacity_box").fadeIn(function() {
          $(".opacity_box").css("display", "block");
        })
        $(".box_changerank_ally").animate({
          width: "40vw",
        }, 1000);
        setTimeout(function () {
          $(".box_changerank_ally").html(`<img src="../image/graphics/closeMsg.png" class="close_rank_box"><h2>Application reason</h2><p>${showAllianceApp}</p>`);
        }, 1000);
      }
    }
  });
})
$(".btn_send_diplo").click(function() {
  $("main").append(`<div class="box_changerank_ally"></div>`);
  $(".opacity_box").fadeIn(function() {
    $(".opacity_box").css("display", "block");
  })
  $(".box_changerank_ally").animate({
    width: "40vw",
  }, 1000);
  setTimeout(function () {
    $(".box_changerank_ally").html(`<h2>Send new diplomacy request</h2><img src="../image/graphics/closeMsg.png" class="close_rank_box">`);
    $(".box_changerank_ally").append(`<form class="form_diplo" action="./include/allianceHandler.php" method="post"><label>Enter name of the alliance</label><input name="ally_name_diplo"></input><select name="diplo_type"><option value="1">Alliance</option><option value="2">Non-agression pact [NAP]</option></select><button type="submit" name="btn_submit_diplo">Submit</button></form>`);
  }, 1000);
});
$(".btn_show_diplo").click(function() {
let index = $(this).prop("id");
$.ajax({
  type: "POST",
  url: "../include/ajaxAlliance.php",
  data: {action: "show_diplo"},
  success: function (showAllianceDiplo) {
      $("main").append(`<div class="box_changerank_ally"></div>`);
      $(".opacity_box").fadeIn(function() {
        $(".opacity_box").css("display", "block");
      })
      $(".box_changerank_ally").animate({
        width: "40vw",
      }, 1000);
      setTimeout(function () {
      if (showAllianceDiplo != "noperms" && showAllianceDiplo != "nopendings") {
        $(".box_changerank_ally").html(`<h2>List of all pending requests</h2><img src="../image/graphics/closeMsg.png" class="close_rank_box"><table class="table_pending_diplo"><tr class="table_diplo_header"><th>Tag | Name</th><th>Diplomacy type</th><th>Action</th></tr></table>`);
        $(".table_pending_diplo").append(`${showAllianceDiplo}`);
      } else {
        if (showAllianceDiplo == "noperms") {
          $(".box_changerank_ally").html(`<h2>You don't have sufficient permissions to access this info!</h2><img src="../image/graphics/closeMsg.png" class="close_rank_box">`);
        } else if (showAllianceDiplo == "nopendings") {
          $(".box_changerank_ally").html(`<h2>No pending requests</h2><img src="../image/graphics/closeMsg.png" class="close_rank_box">`);
        }
      }
      }, 1000);
  }
});
});
$(".btn_edit_avatar").click(function() {
  $("main").append(`<div class="box_edit_info"></div>`);
    $(".box_edit_info").css("display", "flex");
    $(".box_edit_info").html(`<h2>Change alliance logo</h2><img src="../image/graphics/closeMsg.png" class="close_edit_box">`);
    $(".box_edit_info").append(`<form class="logo_ally_form" action="include/imgUpload.php" method="post" enctype="multipart/form-data"><input type="file" name="img" placeholder="IMG" class="input_logo"><button type="submit" name="btn_submit_ally_logo">Change</button></form><h3>Remember, the logo must have dimensions smaller than 200x200 pixels and size smaller than 2 MB and has to be in accordance to TOS</h3>`);
});
$(".btn_edit_name").click(function() {
  let allyName = $(".ally_name_span").prop("id");
  let allyTag = $(".ally_tag_span").prop("id");
  $("main").append(`<div class="box_edit_info"></div>`);
    $(".box_edit_info").css("display", "flex");
    $(".box_edit_info").html(`<h2>Change alliance name|Tag</h2><img src="../image/graphics/closeMsg.png" class="close_edit_box">`);
    $(".box_edit_info").append(`<form class="change_ally_names_form" action="include/allianceHandler.php" method="post"><input type="text" name="new_name_ally" value="${allyName}"><input type="text" name="new_tag_ally"  value="${allyTag}"><button type="submit" name="btn_submit_new_ally_name">Change</button></form><span>If you do not wanna change alliance tag or name, keep them same!</span>`);
});
$(".btn_edit_desc").click(function() {
  let allyDesc = $(".ally_desc_p").prop("id");
  $("main").append(`<div class="box_edit_info"></div>`);
    $(".box_edit_info").css("height", "35vh");
    $(".box_edit_info").css("display", "flex");
    $(".box_edit_info").html(`<h2>Change alliance description</h2><img src="../image/graphics/closeMsg.png" class="close_edit_box">`);
    $(".box_edit_info").append(`<form class="change_ally_names_form" action="include/allianceHandler.php" method="post"><textarea type="text" name="new_desc_ally" maxlength=500 value="${allyDesc}"></textarea><button type="submit" name="btn_submit_desc_new">Change</button></form><span>If you do not wanna change alliance tag or name, keep them same!</span>`);
    $(".change_ally_names_form").css("height", "60%");
});
$(document).on("click", ".close_edit_box", function() {
  $(".box_edit_info").css("display", "none");
  $(".box_edit_info").remove();
})
});
