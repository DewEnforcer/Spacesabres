$(document).ready(function() {
  $(".block").submit(function(bu) {
    bu.preventDefault();
    let user = $("#user").val();
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/blockUser.php',
      data: {username: user},
    success: function(html) {
    $(".result_block").css("display", "block");
    $('.result_block').html(html); //fetch data into msg container
    $('.result_block').fadeOut(3000);
    setTimeout(function(){ $(".result_block").css('display','none'); },3000)
  }
  });//end ajax call
  })
})
