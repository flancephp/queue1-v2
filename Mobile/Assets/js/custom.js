// Storage Stocktaking Pages Search Bar Start

$("#srch").focusin(function () {

  $(".srchBx").css("borderColor", "#7A89FF");

  $(".input-group-append .btn").css("backgroundColor", "#5060DF");

});

$("#srch").focusout(function () {

  $(".srchBx").css("borderColor", "#D5D6DD");

  $(".input-group-append .btn").css("backgroundColor", "#7A89FF");

});

// Storage Stocktaking Pages Search Bar End



// Production3 Toggle Start

$(".timeLink").click(function () {

  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");

  $(this).parent().siblings().find(".prdcnHide").slideToggle("slow");

});

// Production3 Toggle End

