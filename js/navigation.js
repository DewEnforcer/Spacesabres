$(document).ready(() => {
  let open = false;

  displayManager = () => {
    let display = "flex";
    if (open) {
      display = "none";
    }
    childArr = Array.from(document.querySelector(".header_btns").children);
    childArr.forEach((item, i) => {
      $(item).css("display", display);
    });
    $(".header_nav span").css("display", display);
  };

  $("#btn_navbar").click(() => {
    if (open) {
      $(".navigation").animate(
        {
          width: "0vw",
        },
        300
      );
      displayManager();
      open = false;
    } else {
      $(".navigation").animate(
        {
          width: "15vw",
        },
        300
      );
      displayManager();
      open = true;
    }
  });
});
