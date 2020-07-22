$(document).ready(function() {
  $(document).on("click", ".msg", (function(msg) {
  $(this).css("background-color", "grey");
  let index = msg.target.id; //get id of clicked msg
  let checkLenght = index.length; // check if the clicked elemenet is either div/subject/time/from
  if (checkLenght > 1) { //if element clicked is from
    split = index.split("m"); //splits the id to only get the number
  } else {
    split = ["nothing", index]; //else create aritficial array , to not confuse the code
  }
  let result= "randstr";
  let connect= result.concat(split[1]); //get the id of token of the msg
  let name = "from";
  let random = $("#"+connect).val(); //value of the token
  let conname = name.concat(split[1]); // get usernickname of sender
  let getName = $("#"+conname).text();
  $.ajax({
  type: "POST", //execute ajax
    url: './include/pm-select.php',
    data: {from: getName, random: random },
  success: function(html) {
  $('.inbox_container_main').html(html); //fetch data into msg container
;
}
});//end ajax call
}));
$(document).on("click", "#closemsg", (function() {
  $.ajax({
  type: "POST", //execute ajax
    url: './include/msglist.php',
    data: {list: "getList"},
  success: function(list) {
    $('.inbox_container_main').html(list);
}
});//end ajax call
}));
$(document).on("submit", "#replymsg", (function(form) {
  form.preventDefault();
  let to = $("#user").val();
  let subject = $( "#subject" ).val();
  let message = $( "#msg" ).val();
  let replymsg = $( "#respondmsg").text();
  let splitreply = replymsg.split(": ");
  $.ajax({
  type: "POST",
    url: './include/reply-handler.php',
    data: {to: to, subject: subject, message: message, replymsg: splitreply[1]},
  success: function(html) {
    $("#result").css("display", "block");
    $('#result').html(html);
    $('#result').fadeOut(2500);
    setTimeout(function(){ $("#result").css('display','none'); },2500)
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/msglist.php',
      data: {list: "getList"},
    success: function(list) {
      $('.inbox_container_main').html(list);
  }
});//end ajax calls
}
});//end ajax call
}));
$(document).on("click", "#markAll", function(markAll) {
let checker= "markAll";
$.ajax({
type: "POST",
  url: './include/mark.php',
  data: {checker: checker},
success: function(markA) {
$('#result').html(markA);
$("#result").css("display", "block");
$('#result').fadeOut(2500);
setTimeout(function(){ $("#result").css('display','none'); },2500);
}
});//end ajax call
})
$("#mark").click(function(markSel) {
let value = $('.checkbox').length;
let i = 1;
let array = [];
array[0] = 1;
while (i <= value) {
  let checker = $('#checkbox'+i+'').is(':checked');
  if (checker == true) {
      array[i] = $("#randstr"+i+"").val();
  } else {
  array[i] = 1;
}
  i++;
};
$.ajax({
type: "POST", //execute ajax
  url: './include/mark.php',
  data: {randStrs: JSON.stringify(array)},
success: function(delSel) {
$("#result").css("display", "block");
$('#result').html(delSel); //fetch data into msg container
$('#result').fadeOut(2500);
setTimeout(function(){ $("#result").css('display','none'); },2500);

}
});//end ajax call
})
$(document).on("click", "#reply", function() {
  let toreplymsg = $("#msgTextContainer").text();
  let getFrom = $("#from").text();
  let getSubject= $("#subject").text();
  let splitFrom = getFrom.split(": ");
  let splitSubject = getSubject.split(": ");
  $(".inbox_container_main").html("<img src='../image/graphics/closeMsg.png' id='closemsg' class='close_msg_reply' alt='close'>");
  let form1 = "<form class=message id='replymsg' action=internalInbox.php method=post>Username: <input type=text name=user id='user' value="+splitFrom[1]+"><br>Subject: <input type=text name=subject id='subject' value='RE:"+splitSubject[1]+"'><br><br><textarea name=msg id=msg rows=8 cols=80 maxlength='600'></textarea><br><button type=submit name=submit-msg class='submit_direct_reply'>Submit your message!</button><br></form>"
  $(".inbox_container_main").append(form1);
});
$(".block").submit(function(bu) {
  bu.preventDefault();
  let user = $("#user").val();
  $.ajax({
  type: "POST", //execute ajax
    url: './include/blockUser.php',
    data: {username: user},
  success: function(html) {
    $("#result").css("display", "block");
  $('.result_block').html(html); //fetch data into msg container
  $('#result').fadeOut(2500);
  setTimeout(function(){ $("#result").css('display','none'); },2500);
}
});//end ajax call
});
$(document).on("click",".image" ,function(msg) {
let index = msg.target.id; //get id of clicked msg
let checkLenght = index.length; // check if the clicked elemenet is either div/subject/time/from
if (checkLenght > 1) { //if element clicked is from
  let split = index.split("e"); //splits the id to only get the number
  if (split[0] == "imag") {
let id = split[1];
let result = "randstr";
let connect= result.concat(split[1]); //get the id of token of the msg
let name = "from";
let random = $("#"+connect).val(); //value of the token
let conname = name.concat(split[1]); // get usernickname of sender
let getName = $("#"+conname).text();
$.ajax({
type: "POST", //execute ajax
  url: './include/pm-delete.php',
  data: {from: getName, random: random },
success: function(html) {
  $('#result').html(html); //fetch data into msg container
  $("#result").css("display", "block");
  $('#result').fadeOut(2500);
  setTimeout(function(){ $("#result").css('display','none'); },2500)
}
});//end ajax call
}
}
});
$(document).on("click", "#deleteAll", function(delAll) {
let index = delAll.target.id;
$.ajax({
type: "POST", //execute ajax
url: './include/pm-delete.php',
data: {index: index},
success: function(delAll) {
$('#result').html(delAll); //fetch data into msg container
$("#result").css("display", "block");
$('#result').fadeOut(2500);
setTimeout(function(){ $("#result").css('display','none'); },2500)
}
});//end ajax call
});
$(document).on("click", "#delete", function(delSel) {
let value = $('.checkbox').length;
let i = 1;
let array = [];
array[0] = 1;
while (i <= value) {
  let checker = $('#checkbox'+i+'').is(':checked');
  if (checker == true) {
      array[i] = $("#randstr"+i+"").val();
  } else {
  array[i] = 1;
}
  i++;
};
$.ajax({
type: "POST", //execute ajax
  url: './include/pm-delete.php',
  data: {randStrs: JSON.stringify(array)},
success: function(delSel) {
$('#result').css("display", "block");
$('#result').html(delSel); //fetch data into msg container
$('#result').fadeOut(2500);
setTimeout(function(){ $("#result").css('display','none'); },2500)
}
});//end ajax call
});
$(".blockedusers").click(function(switchBlock){
$(".inbox_container_main").html("<section class='blockuser_container'>  <label for='blockUser'>Enter nickname of the user you want to block/unblock: </label> <br> <input type='text' name='blockUser' id='user'> <br> <button type='submit' name='button'>Block/Unblock</button></form><div class='result_block'</div</section>");
});
$(document).on("submit", ".block", (function(bu) {
  bu.preventDefault();
  let user = $("#user").val();
  $.ajax({
  type: "POST", //execute ajax
    url: './include/blockUser.php',
    data: {username: user},
  success: function(html) {
  $(".result_block").css("display", "block");
  $('.result_block').html(html); //fetch data into msg container
  $('.result_block').fadeOut(3000);
  setTimeout(function(){ $(".result_block").css('display','none'); },3000)
}
});//end ajax call
}));
$(".inbox").click(function(switchInbox){
  $.ajax({
  type: "POST", //execute ajax
    url: './include/msglist.php',
    data: {list: "getList"},
  success: function(list) {
    $('.inbox_container_main').html(list);
}
});//end ajax call
});
$(".sentmsg").click(function(switchSentmsg){
    $('.inbox_container_main').html("<p class='nomsg'>Coming Soon!</p>");
});
$(".friends").click(function(switchSentmsg){
    $('.inbox_container_main').html("<p class='nomsg'>Coming Soon!</p>");
});
$(".sendmsg").click(function(switchSentmsg){
    window.location.href = "../internalMessages.php";
});
});
