$(document).ready(function() {
  $(".planet").click(function(planet) {
    let planetID = planet.target.id;
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/hacking.php',
      data: {planetID: planetID},
    success: function(html) {
    $('.hack-result').html(html); //fetch data into msg container
  }
  });//end ajax call
  })
})
