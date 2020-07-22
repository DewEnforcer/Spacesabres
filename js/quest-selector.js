$(document).ready(function() {
  $(".quest").click(function() {

    $(".mission_container_details").fadeIn(500, function() {
      $(".mission_container_details").css( 'display', 'flex');
    })
  let index = $(this).prop("id"); //get id of clicked mission
  let status = $(this).val();
  $.ajax({
  type: "POST", //execute ajax
    url: '../include/getMissionObjective.php',
    data: {id: index, status: status},
  success: function(getobjectives) {
      let parseJson = $.parseJSON(getobjectives);
      let objectives = parseJson["objectives"];
      let rewards = parseJson["rewards"];
      let completed = parseJson["completed"];
      $.getJSON( "../js/quests.json", function( json ) {
        let title = json.quests[index].title;
        let description = json.quests[index].description;
          $(".mission_container_details").html('<img src="../image/graphics/closeMsg.png" alt="close" class="close_details">');
          $(".mission_container_details").append("<h2>"+title+"</h2><hr>");
          $(".mission_container_details").append("<p>"+description+"</p><hr>");
          $(".mission_container_details").append("<div class='mission_container_details_wrapper'></div>");
          $(".mission_container_details_wrapper").html("<ul class='mission_container_details_mission_objectives'></ul>");//
          $(".mission_container_details_wrapper").append("<ul class='mission_container_details_mission_reward'></ul>");//
          $(".mission_container_details").append("<div class='btn_mission_wrapper1'></div>");
          if (status == "true") {
            $(".mission_container_details_mission_objectives").html("<li><h3>Progress in the mission objectives:</h3></li>");
          } else {
            $(".mission_container_details_mission_objectives").html("<li><h3>Mission objectives:</h3></li>");
          }
          $(".mission_container_details_mission_reward").html("<li><h3>Mission Rewards:</h3></li>");
          if (status == "false") {
            if (parseInt(index) < (parseInt(completed)+2)) {
              $(".btn_mission_wrapper1").html('<a href="include/questaccept.php?send=accept&&questID='+index+'" class="accept_mission" id="'+index+'">Accept the mission</a>');
          } else {
            $(".btn_mission_wrapper1").html("<span>Complete previous missions in order to unlock this one!</span>");
          }
        } else {
          $(".btn_mission_wrapper1").html(parseJson["buttons"]);
        }
          objectives.forEach(function(item) {
            $(".mission_container_details_mission_objectives").append(item);
          });
          rewards.forEach(function(item) {
            $(".mission_container_details_mission_reward").append(item);
          });
       });
}
});//end ajax call
 });
$(document).on("click", ".close_details", function(){
  $(".mission_container_details").fadeOut(1000, function() {
    $(".mission_container_details").css( 'display', 'none');
  })
  setTimeout(function () {
    $(".btn_mission_wrapper1").html("");
  $(".mission_container_details_name").html("");
  $(".mission_container_details_mission_description").html("");
  $(".mission_container_details_mission_objectives").html("");
  $(".mission_container_details_mission_reward").html("");
}, 1000);
$('.error_box').fadeOut(2500);
setTimeout(function(){ $("#result").css('display','none'); },2500);
});
$(document).on("click", ".cancel_mission" , function(cancelMission){
let index = cancelMission.target.id;
$.ajax({
type: "POST", //execute ajax
  url: '../include/questcancel.php',
  data: {id: index},
success: function(getresult) {
  $(".current_mission_box").html("<p class='result_cancel' style='font-family:sans-serif; margin-top:250px; margin-left:200px;' class=''>"+getresult+"</p>");
}});//end ajax call
});
$(".claim_rewards").click(function(claim){
let index = claim.target.id;
$.ajax({
type: "POST", //execute ajax
  url: '../include/questRewards.php',
  data: {id: index},
success: function(getresult) {
  $(".current_mission_box").html("<p class='result_cancel' style='font-family:sans-serif; margin-top:250px; margin-left:200px;' class=''>"+getresult+"</p>");
}});//end ajax call
});
});
