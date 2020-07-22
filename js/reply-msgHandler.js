$(document).ready(function() {
  $("#replymsg").submit( function(form) {
    form.preventDefault();
    let to = $( "#user" ).val();
    let subject = $( "#subject" ).val();
    let message = $( "#msg" ).val();
    let replymsg = $( "#respondmsg").text();
    let splitreply = replymsg.split(": ");
    $.ajax({
    type: "POST",
      url: '../include/reply-handler.php',
      data: {to: to, subject: subject, message: message, replymsg: splitreply[1]},
    success: function(html) {
    $('#result').append(html);
    $('#respondmsg').text('');
    $('#replymsg').text('');
 }
});//end ajax call
});
$("#markAll").click(function(markAll) {
  let checker= "markAll";
  $.ajax({
  type: "POST",
    url: '../include/mark.php',
    data: {checker: checker},
  success: function(markA) {
  $('.result_selected').html(markA);
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
    url: '../include/mark.php',
    data: {randStrs: JSON.stringify(array)},
  success: function(delSel) {
  $('.result_selected').html(delSel); //fetch data into msg container
}
});//end ajax call
})
});
