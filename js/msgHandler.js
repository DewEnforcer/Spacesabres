$(document).ready(function() {
  $("form").submit( function(form) {
    form.preventDefault();
    let to = $( "#user" ).val();
    let subject = $( "#subject" ).val();
    let message = $( "#msg" ).val();
    let id = $( "#userID" ).val();
    $.ajax({
    type: "POST",
      url: '../include/pm-handler.php',
      data: {to: to, subject: subject, message: message, id: id },
    success: function(html) {
    $('#message-result').css("display", "block");
    $('#message-result').html(html);
    $('#message-result').fadeOut(3500);
    $('#msg').val('');
    $('#user').val('');
    $('#subject').val('');
 }
});//end ajax call
  })
});
