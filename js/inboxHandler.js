$(document).ready(function() {
  var selectedMsg = {
    recieved: undefined,
    sent: undefined
  };
  // open msg
  $(document).on("click", ".msg", function(msg) {
    if (selectedMsg.recieved != this.id) {
      if (selectedMsg.recieved != undefined) {
        $(".msg"+selectedMsg.recieved+"").removeClass("nohovermsg");
      }
      selectedMsg.recieved = this.id;
      $(".msg_ajax").remove();
    let index = this.id;
    let box = this;
    $(box).addClass("nohovermsg")
    $.ajax({
    type: "POST",
      url: '../include/pm-select.php',
      data: {index: index, action: "recieved"},
    success: function(msg) {
    msg = $.parseJSON(msg);
    $(box).append(`<div class="msg_ajax"></div>`);
    $(".msg_ajax").html("<p class='main_msg'>"+msg[0]+"</p>");
    if (msg[1].length > 0) {
      $(".msg_ajax").append("<hr><p class='replyto_msg'>Replying to: "+msg[1]+"</p>");
    }
    if ($(box).attr("from") == "user") {
      $(".msg_ajax").append("<hr><div><button type='button' class='btn_block_user'>Block this user</button><button type='button' class='btn_reply_msg'>Send reply</button></div>"); ////////
    }
  }
  });
  }
});
// show filter
$(document).on("change", ".select_show_msg", function() {
  let show = this.value;
  if (show != "all") {
    let msgs = document.querySelectorAll(".msg_wrapper");
    let condit;
    if (show != "0" && show != "1") {
      condit = "from";
    } else {
      condit = "read";
    }
    msgs.forEach(function(msg) {
      if ($(msg).attr(condit) != show) {
        $(msg).css("display", "none");
      } else if ($(msg).attr(condit) == show) {
        $(msg).css("display", "flex");
      }
    });
  } else {
    $(".msg_wrapper").css("display", "flex");
  }
});
// un/mark all msgs
$(document).on("change", ".check_all", function() {
  if (this.checked == true) {
    $(".checkbox").prop("checked", true);
  } else if (this.checked == false) {
    $(".checkbox").prop("checked", false);
  }
});
// delete/read msgs
$(document).on("click", ".btn_mng_msg", function(ev) {
  ev.preventDefault();
  let action = this.value;
  let selected = document.querySelectorAll(".checkbox");
  let selectedArr = [];
  selected.forEach(function(checkbox) {
    if (checkbox.checked == true) {
      selectedArr.push($(checkbox).attr("token"));
    }
  });
  if (selectedArr.length > 0) {
    $.ajax({
    type: "POST", //execute ajax
    url: '../include/pm-delete.php',
    data: {action: action, msgs: JSON.stringify(selectedArr)},
    success: function(result) {
      if (result == "success") {
        selected.forEach(function(msg) {
          var wrapper = msg.parentElement;
          var realMsg = wrapper.parentElement;
          var msgBg = realMsg.lastChild;
           if (msg.checked == true) {
             if (action == "delete") {
                 $(realMsg).remove();
             } else if (action == "read") {
               $(msgBg).css("background-color", "rgb(85,85,85)");
             }
           }
           msg.checked = false;
        });
      } else if (result == "error") {
        $("main").append("<div class='popup_result'></div>");
        $(".popup_result").html("<h2>Error!</h2><span>An unexpected error has occured, please refresh the page or report this bug on forums.</span>");
      }
    }
    });//end ajax call
  }
});
// write new msg
$(document).on("click", ".btn_newmsg", function() {
  $(".inbox_container_main_header").css("display", "none");
  $(".inbox_msg_wrapper").css("display", "none");
  $(".inbox_container_main").append("<div class='new_msg_wrapper'></div>");
  $(".new_msg_wrapper").html(`<div><span>Recievers username: </span><input class="input_reciever" placeholder="Enter recievers name"></div><div><span>Subject: </span><input class="input_subject" placeholder="Enter subject"></div><textarea class="new_msg_msg" rows="8" cols="80" placeholder="Here goes your message..."></textarea><div><button type="button" class="btn_cancel_new_msg">Cancel</button><button type="button" class="btn_send_new_msg" value="new" style="color: rgb(80,220,100);">Send message</button></div>`);
});
// cancel message
$(document).on("click", ".btn_cancel_new_msg", function() {
  $(".new_msg_wrapper").remove();
  $(".inbox_container_main_header").css("display", "flex");
  $(".inbox_msg_wrapper").css("display", "flex");
});
//send message
$(document).on("click", ".btn_send_new_msg", function() {
  let msg = $(".new_msg_msg").val();
  let subject = $(".input_subject").val();
  let reciever = $(".input_reciever").val();
  let replyto = "";
  if (this.value == "reply") {
    replyto = $(".readonly").val();
  }
  $.ajax({
  type: "POST", //execute ajax
    url: '../include/pm-handler.php',
    data: {action: "msg", msg: msg, subject: subject, to: reciever, replyto: replyto},
  success: function(result) {
    if (result == "success") {
      $(".new_msg_wrapper").remove();
      $(".inbox_container_main_header").css("display", "flex");
      $(".inbox_msg_wrapper").css("display", "flex");
    } else if (result == "notfound") {
      $("main").append("<div class='popup_result'></div>");
      $(".popup_result").html("<h2>Error!</h2><span>No user matches recievers username!.</span>");
    } else if (result == "error") {
      $("main").append("<div class='popup_result'></div>");
      $(".popup_result").html("<h2>Error!</h2><span>An unexpected error has occured, please refresh the page or report this bug on forums.</span>");
    } else if (result == "notfilled") {
      $("main").append("<div class='popup_result'></div>");
      $(".popup_result").html("<h2>Error!</h2><span>You haven't filled in all the required details!</span>");
    } else if (result == "longsubj") {
      $("main").append("<div class='popup_result'></div>");
      $(".popup_result").html("<h2>Error!</h2><span>The subject is too long!</span>");
    } else if (result == "blocked") {
      $("main").append("<div class='popup_result'></div>");
      $(".popup_result").html("<h2>Error!</h2><span>This user has blocked you from sending them messages.</span>");
      $(".popup_result").fadeOut(6000,function() {
        $(".popup_result").remove();
      });
    }
}
});//end ajax call
});
$(document).on("click", ".btn_reply_msg", function() {
  let msg = document.querySelector(".msg"+selectedMsg.recieved+"").children;
  let sender = msg[0].innerHTML.split(": ");
  let subject = $(".subject_"+selectedMsg.recieved+"").html();
  let message = msg[4].firstChild.innerHTML;
  $(".inbox_container_main_header").css("display", "none");
  $(".inbox_msg_wrapper").css("display", "none");
  $(".inbox_container_main").append("<div class='new_msg_wrapper'></div>");
  $(".new_msg_wrapper").html(`<div><span>Recievers username: </span><input class="input_reciever" placeholder="Enter recievers name" value="${sender[1]}"></div><div><span>Subject: </span><input class="input_subject" placeholder="Enter subject" value="RE: ${subject}"></div><textarea class="new_msg_msg" rows="8" cols="80" style="height: 50%;" placeholder="Here goes your message..."></textarea><textarea class="readonly" readonly>Replying to: ${message}</textarea><div><button type="button" class="btn_cancel_new_msg">Cancel</button><button type="button" class="btn_send_new_msg" value="reply" style="color: rgb(80,220,100);">Send message</button></div>`);
});
$(".inbox").click(function(switchInbox){
    $(".blocked_users_list").remove();
    $(".nomsg").remove();
    $(".inbox_msg_wrapper_sent").remove();
    $(".inbox_container_main_header").css("display", "flex");
    $(".inbox_msg_wrapper").css("display", "flex");
      $(".new_msg_wrapper").remove();
});
$(".sentmsg").click(function(switchSentmsg){
    $(".new_msg_wrapper").remove();
    $(".blocked_users_list").remove();
  $(".inbox_container_main_header").css("display", "none");
  $(".inbox_msg_wrapper").css("display", "none");
    $(".nomsg").remove();
    $('.inbox_container_main').append("<div class='inbox_msg_wrapper inbox_msg_wrapper_sent'></div>");
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/msglist.php',
      data: {action: "getSent"},
    success: function(result) {
      if (result != "error" && result != "nomsgs") {
        let msgs = $.parseJSON(result);
        let index = 0;
        msgs.forEach(function(msgSent) {
          $(".inbox_msg_wrapper_sent").append(`<div class="msg_wrapper_sent msg_wrapper_sent_${index}" to="${msgSent[0]}"><p class='from to' id='to${index}' id="${index}">To: ${msgSent[0]}</p>`);
          $(".msg_wrapper_sent_"+index+"").append(`<p class='subject' id="${index}">Subject: <span class='subject_${index}'>${msgSent[1]}</span></p>`);
          $(".msg_wrapper_sent_"+index+"").append(`<p class='date' id="${index}">Sent on: ${msgSent[2]}</p>`);
          $(".msg_wrapper_sent_"+index+"").append(`<input type='hidden' id='randstr${index}' value='${msgSent[3]}'>`)
          index++;
        });
      }
  }
  });//end ajax call
});
$(document).on("click", ".msg_wrapper_sent", function(msg) {
  if (selectedMsg.sent != this.id) {
    if (selectedMsg.sent != undefined) {
      $(".msg_wrapper_sent"+selectedMsg.sent+"").removeClass("nohovermsg");
    }
    selectedMsg.sent = this.id;
  let index = this.id;
  let box = this;
  $(box).addClass("nohovermsg");
  $.ajax({
  type: "POST",
    url: '../include/pm-select.php',
    data: {index: index, action: "sent"},
  success: function(msg) {
  msg = $.parseJSON(msg);
  $(box).append(`<div class="msg_ajax"></div>`);
  $(".msg_ajax").html("<p class='main_msg'>"+msg[0]+"</p>");
  if (msg[1].length > 0) {
    $(".msg_ajax").append("<hr><p class='replyto_msg'>Replying to: "+msg[1]+"</p>");
  }
  if ($(box).attr("from") == "user") {
    $(".msg_ajax").append("<hr><div><button type='button' class='btn_block_user'>Block this user</button><button type='button' class='btn_reply_msg'>Send reply</button></div>"); ////////
  }
}
});
}
});
$(".friends").click(function(switchSentmsg){
    $(".new_msg_wrapper").remove();
  $(".inbox_container_main_header").css("display", "none");
  $(".inbox_msg_wrapper").css("display", "none");
  $(".nomsg").remove();
  $(".blocked_users_list").remove();
    $('.inbox_container_main').append("<p class='nomsg'>Coming Soon!</p>");
});
$(document).on("click", ".btn_block_user", function() {
  let user = $("#from"+selectedMsg.recieved+"").html().split(": ");
  user = user[1];
  $.ajax({
  type: "POST",
    url: '../include/blockUser.php',
    data: {action: "block", user: user},
  success: function(result) {
  }
});
});
$(document).on("click", ".blockedusers", function() {
    $(".new_msg_wrapper").remove();
  $(".nomsg").remove();
  $(".inbox_msg_wrapper_sent").remove();
  $(".inbox_container_main_header").css("display", "none");
  $(".inbox_msg_wrapper").css("display", "none");
  $(".inbox_container_main").append("<div class='blocked_users_list'></div>");
  $.ajax({
  type: "POST",
    url: '../include/blockUser.php',
    data: {action: "list", user: ""},
  success: function(result) {
    if (result != "empty") {
      result = $.parseJSON(result);
      let index = 0;
      result.forEach(function(user) {
        $(".blocked_users_list").append(`<div class="blocked_user" id="blocked_${index}"><span>Commander: ${user}</span><button type="button" class="btn_unblock_user" value="${index}">Unblock this user</button></div>`);
        index++;
      });
    } else {
      $(".blocked_users_list").append(`<span>You don't have any commanders blocked.</span>`);
    }
  }
  });
});
$(document).on("click", ".btn_unblock_user", function() {
   let index = this.value;
   $.ajax({
   type: "POST",
     url: '../include/blockUser.php',
     data: {action: "unblock", user: index},
   success: function(result) {
     if (result == "success") {
       $("#blocked_"+index+"").remove();
       $("main").append("<div class='popup_result'></div>");
       $(".popup_result").html("<h2>Success!</h2><span>The user has been unblocked and can now contact you again!</span>");
     } else if (result == "error") {
       $("main").append("<div class='popup_result'></div>");
       $(".popup_result").html("<h2>Error!</h2><span>Unfortunately an internal server error has occured, please try again or report this bug on forums!</span>");
     }
     $(".popup_result").fadeOut(3000,function() {
       $(".popup_result").remove();
     });
   }
 });
});
});
