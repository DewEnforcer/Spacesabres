$(document).ready(function(){
  $(".acp_main").submit(function(acpquery){
    acpquery.preventDefault();
    let command = $(".console").val();
    $.ajax({
    type: "POST", //execute ajax
      url: 'acphandler.php',
      data: {console: command},
    success: function(cmdResult) {
      console.log(cmdResult);
      $(".result").html(cmdResult);
    }
  });//end ajax call
  })
});
