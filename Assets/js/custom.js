// Nav Toggler End

// Nav Bar Click
$(".navbar-toggler").click(function () {
  $("body").addClass("showNav");
});

// Close Btn Click
$(".clsBar a").click(function () {
  $("body").removeClass("showNav");
});

// Nav Toggler End

// Dropdown Toggle Icon Change Start
$(".dropdown-toggle").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
});
// Dropdown Toggle Icon Change End

// Feature Btns Start
$(".fetBtn a").click(function () {
  $(".mbFeature").slideToggle("slow");
});
// Feature Btns End

// Order & Requisition Pages Search Bar Start
$("#srch").focusin(function () {
  $(".srchBx").css("borderColor", "#7A89FF");
  $(".input-group-append .btn").css("backgroundColor", "#5060DF");
});
$("#srch").focusout(function () {
  $(".srchBx").css("borderColor", "#ff0000");
  $(".input-group-append .btn").css("backgroundColor", "#7A89FF");
});
// Order & Requisition Pages Search Bar End

// Order Page Barcode & S.Qty. Clone Start
// $(document).ready(function () {
//   $(window).resize(function () {
//     if ($(window).width() > 992) {
//       $(".mb-brCode").hide();
//     } else {
//       $(".ord-brCode").eq(0).appendTo(".mb-brCode");
//       $(".ord-StockQty").eq(0).appendTo(".mb-brCode");
//       $(".Itm-brCode").hide();
//       $(".prdtStk-Qty").hide();
//       // $(".mb-brCode .ord-brCode").show();
//     }
//   });
// });

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(".mb-brCode").hide();
  } else {
    $(".ord-brCode").eq(0).appendTo(".mb-brCode");
    $(".ord-StockQty").eq(0).appendTo(".mb-brCode");
    $(".Itm-brCode").hide();
    $(".prdtStk-Qty").hide();
    // $(".mb-brCode .ord-brCode").show();
  }
});
$(document).ready(function () {
  // Function to handle the resizing logic
  function handleResize() {
    if ($(window).width() > 992) {
      $(".mb-brCode").hide();
    } else {
      $(".mb-brCode").show(); // Ensure the container is visible on smaller screens
      $(".mb-brCode").empty(); // Clear existing elements to prevent duplication
      $(".ord-brCode").eq(0).appendTo(".mb-brCode");
      $(".ord-StockQty").eq(0).appendTo(".mb-brCode");
      $(".Itm-brCode").hide();
      $(".prdtStk-Qty").hide();
    }
  }

  // Run the resize handler immediately on load/reload
  handleResize();

  // Bind the resize event to the handler
  $(window).resize(function () {
    handleResize();
  });
});

// Order Page Barcode & S.Qty. Clone End

// Order Table Start
$(".orderLink").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".prdt-Hide").slideToggle("slow");
});
// Order Table End

// Receive Order Page Item Name Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 992) {
      $(this).find(".tab-RecItm").hide();
    } else {
      $(this).find(".recive-Item").eq(0).appendTo(".tab-RecItm");
      $(this).find(".recItm-Name").hide();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(this).find(".tab-RecItm").hide();
  } else {
    $(this).find(".recive-Item").eq(0).appendTo(".tab-RecItm");
    $(this).find(".recItm-Name").hide();
  }
});
// Receive Order Page Item Name Clone End

// Receive Order Page Qty.Receive Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 992) {
      $(this).find(".tabTtl-Price").hide();
    } else {
      $(this).find(".recQty-Receive").eq(0).appendTo(".tabTtl-Price");
      $(this).find(".qty-Rcvd").hide();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(this).find(".tabTtl-Price").hide();
  } else {
    $(this).find(".recQty-Receive").eq(0).appendTo(".tabTtl-Price");
    $(this).find(".qty-Rcvd").hide();
  }
});
// Receive Order Page Qty.Receive Clone End

// Receive Order Page Barcode & Qty.Order Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 992) {
      $(".recBr-Hide").hide();
    } else {
      $(".cloneQty-Code").eq(0).appendTo(".recBr-Hide");
      $(".cloneQty-Code").hide();
      $(".recBr-Hide .cloneQty-Code").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(".recBr-Hide").hide();
  } else {
    $(".cloneQty-Code").eq(0).appendTo(".recBr-Hide");
    $(".cloneQty-Code").hide();
    $(".recBr-Hide .cloneQty-Code").show();
  }
});
// Receive Order Page Barcode & Qty.Order Clone End

// Receive Order Table Start
$(".orderLink").click(function () {
  $(this).parent().siblings().find(".recBr-Hide").slideToggle("slow");
});
// Receive Order Table End

// Product Table Start
$(".brNum").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".hideInfo").slideToggle("slow");
});
// Product Table End

// Requisition Page Barcode & S.Qty Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 992) {
      $(".mb-ReqCode").hide();
    } else {
      //$(".reqBarCode").eq(0).appendTo(".mb-ReqCode");
      //$(".reqStockQty").eq(0).appendTo(".mb-ReqCode");
      $(".reqClm-Br").hide();
      $(".reqSt-Qty").hide();
      // $(".recBr-Hide .cloneQty-Code").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(".mb-ReqCode").hide();
  } else {
   // $(".reqBarCode").eq(0).appendTo(".mb-ReqCode");
   // $(".reqStockQty").eq(0).appendTo(".mb-ReqCode");
    $(".reqClm-Br").hide();
    $(".reqSt-Qty").hide();
    // $(".recBr-Hide .cloneQty-Code").show();
  }
});
// Requisition Page Barcode & S.Qty Clone End

// Requisition Table Toggle Start
$(".orderLink").click(function () {
  $(this).parent().siblings().find(".requi-Hide").slideToggle("slow");
});
// Requisition Table Toggle End

// Running Task Page Temp Status Start
$(".statusLink").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().parent().parent().find(".togleOrder").slideToggle("slow");
});
// Running Task Page Temp Status End

// Stock Page Feature Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() >= 1024) {
      $(".hdfetCol").hide();
    } else {
      //  $('.hdfetCol').show();
      $(".strfetCol").remove().clone().appendTo(".hdfetCol");
    }
  });
});

$(document).ready(function () {
  if ($(window).width() >= 1024) {
    $(".hdfetCol").hide();
  } else {
    // $('.hdfetCol').show();
    $(".strfetCol").remove().clone().appendTo(".hdfetCol");
  }
});
// Stock Page Feature Clone End

// Stock Page Search Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() >= 1024) {
      $(".mbClone-src").hide();
    } else {
      $(".mbClone-src").show();
      $(".mbCol-stk .srchBx").remove().clone().appendTo(".mbClone-src");
    }
  });
});

$(document).ready(function () {
  if ($(window).width() >= 1024) {
    $(".mbClone-src").hide();
  } else {
    $(".mbClone-src").show();
    $(".mbCol-stk .srchBx").remove().clone().appendTo(".mbClone-src");
  }
});
// Stock Page Search Clone End

// Stock Page Filter Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 768) {
      $(".filter-mb").css("display", "none");
    } else {
      $(".filter-mb").hide();
      $(".supData-Head").remove().clone().appendTo(".filter-mb");
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 768) {
    $(".filter-mb").css("display", "none");
  } else {
    $(".filter-mb").hide();
    $(".supData-Head").remove().clone().appendTo(".filter-mb");
  }
});

// Stock Page Filter Clone End

// Filter Btns Stock Page Start
$(".filtBtn a").click(function () {
  $(".filter-mb").slideToggle("slow");
});
// Filter Btns Stock Page End

// Feature Btns Stock Page Start
$(".fetBtn a").click(function () {
  $(".hdfetCol").slideToggle("slow");
});
// Feature Btns Stock Page End

// Stock Page All Store Start
$(document).ready(function () {
  $(".allStore").hide();

  $(".dskAll-str").click(function () {
    $(".allStore").css("display", "flex");
    $(".storeCol").css("background", "transparent");
    $(".dskAll-str").hide();
  });

  $(".allStore").click(function () {
    $(".dskAll-str").css("display", "flex");
    $(".storeCol").css("background", "transparent");
    $(".allStore").hide();
  });
});
// Stock Page All Store End

// Stock Page Single Store Start

$(".otrStock").click(function () {
  var check = $(".stockActive-br").length;
  if (check >= 1) {
    $(".stockActive-br").removeClass("stockActive-br");
  }
  $(this).addClass("stockActive-br");

  $(".dskAll-str").css("display", "flex");
  $(".storeCol").css("background", "transparent");
  $(".allStore").hide();
});

// Stock Page Single Store End

// History Page Date Clone Start
// $(document).ready(function () {
//   $(window).resize(function () {
//     if ($(window).width() > 1024) {
//       $(".mb-hisDate .date-flx").hide();
//     } else {
//       $(".mb-hisDate .date-flx").show();
//       $(".prtDate").remove().clone().appendTo(".mb-hisDate .date-flx");
//     }
//   });
// });

// $(document).ready(function () {
//   if ($(window).width() > 1024) {
//     $(".mb-hisDate .date-flx").hide();
//   } else {
//     $(".mb-hisDate .date-flx").show();
//     $(".prtDate").remove().clone().appendTo(".mb-hisDate .date-flx");
//   }
// });

$(document).ready(function () {
  function handleResize() {
    if ($(window).width() > 1024) {
      $(".mb-hisDate .date-flx").hide();
    } else {
      $(".mb-hisDate .date-flx").show();
      $(".prtDate").remove().clone().appendTo(".mb-hisDate .date-flx");
      $(".mb-hisDate .date-flx .datepicker").datepicker(); // Reinitialize the datepicker after cloning
    }
  }

  $(window).resize(handleResize);
  
  // Trigger the resize event on page load to handle the initial state
  handleResize();
});

// History Page Date Clone End

// History Page Account No. Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 1024) {
      $(".mb-Acntnum").hide();
    } else {
      $(".hisNo").eq(0).appendTo(".mb-Acntnum");
      $(".hisNo").hide();
      $(".mb-Acntnum .hisNo").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 1024) {
    $(".mb-Acntnum").hide();
  } else {
    $(".hisNo").eq(0).appendTo(".mb-Acntnum");
    $(".hisNo").hide();
    $(".mb-Acntnum .hisNo").show();
  }
});
// History Page Account No. Clone End

// History Page Table Toggle Part Start
$(".mb-hisLink").click(function () {
  $(this).parent().siblings().find(".shrtHisclm").slideToggle("slow");
});
// History Page Table Toggle Part End

// History Page Date Toggle Start
$(".cal-Ender a").click(function () {
  $(".mb-hisDate").slideToggle("slow");
});
// History Page Date Toggle End

// History Page Issue-in-out Toggle Start
$(".maxLink").click(function () {
  $(".detailPrice").addClass("detailPrc-show").fadeIn("slow");
});

$(".tab-revLnk").click(function () {
  $(".detailPrice").removeClass("detailPrc-show").fadeOut("slow");
});
// History Page Issue-in-out Toggle End

// History Page Filter Toggle Start
$(".head-Filter").click(function () {
  $(".hstTbl-head").addClass("hstTable-show").fadeIn("slow");
});

$(".tab-lnkFltr").click(function () {
  $(".hstTbl-head").removeClass("hstTable-show").fadeOut("slow");
});
// History Page Filter Toggle End

// Revenue Center Page Get Data Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 1024) {
      $(".mbguest-Data").hide();
    } else {
      $(".gt-Data").eq(0).appendTo(".mbguest-Data");
      $(".gt-Data").hide();
      $(".mbguest-Data .gt-Data").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 1024) {
    $(".mbguest-Data").hide();
  } else {
    $(".gt-Data").eq(0).appendTo(".mbguest-Data");
    $(".gt-Data").hide();
    $(".mbguest-Data .gt-Data").show();
  }
});
// Revenue Center Page Get Data Clone End

// Revenue Center Page Green Check Btn Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 992) {
      $(".chkbx-revCntr").hide();
    } else {
      $(".sqChk-Box").eq(0).appendTo(".chkbx-revCntr");
      $(".sqChk-Box").hide();
      $(".chkbx-revCntr .sqChk-Box").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(".chkbx-revCntr").hide();
  } else {
    $(".sqChk-Box").eq(0).appendTo(".chkbx-revCntr");
    $(".sqChk-Box").hide();
    $(".chkbx-revCntr .sqChk-Box").show();
  }
});
// Revenue Center Page Green Check Btn Clone End

// Revenue Center Page Table Toggle Part Start
$(".mb-hisLink").click(function () {
  $(this).parent().siblings().find(".revCenter-Dtl").slideToggle("slow");
});
// Revenue Center Page Table Toggle Part End

// Item History View Page Filter Toggle Start
$(".head-Filter.itmVw-Filter").click(function () {
  $(".itmView-Table").addClass("itmVw-Tblshow").fadeIn("slow");
});

$(".tab-lnkFltr").click(function () {
  $(".itmView-Table").removeClass("itmVw-Tblshow").fadeOut("slow");
});
// Item History View Page Filter Toggle End

// Item History View Page Green Check Btn Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 1024) {
      $(".tab-itmVwchk").hide();
    } else {
      $(".itmVw-chkClm").eq(0).appendTo(".tab-itmVwchk");
      $(".itmVw-chkClm").hide();
      $(".tab-itmVwchk .itmVw-chkClm").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 1024) {
    $(".tab-itmVwchk").hide();
  } else {
    $(".itmVw-chkClm").eq(0).appendTo(".tab-itmVwchk");
    $(".itmVw-chkClm").hide();
    $(".tab-itmVwchk .itmVw-chkClm").show();
  }
});
// Item History View Page Green Check Btn Clone End

// Item History View Page Stock Quantity (Issue Out) Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 992) {
      $(".itmVw-stkQtyOut").hide();
    } else {
      $(".itmVw-qtybdClm").eq(0).appendTo(".itmVw-stkQtyOut");
      $(".itmVw-qtybdClm").hide();
      $(".itmVw-stkQtyOut .itmVw-qtybdClm").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 992) {
    $(".itmVw-stkQtyOut").hide();
  } else {
    $(".itmVw-qtybdClm").eq(0).appendTo(".itmVw-stkQtyOut");
    $(".itmVw-qtybdClm").hide();
    $(".itmVw-stkQtyOut .itmVw-qtybdClm").show();
  }
});
// Item History View Page Stock Quantity (Issue Out) Clone End

// Item History View Page Table Toggle Part Start
$(".mb-hisLink").click(function () {
  $(this).parent().siblings().find(".tbdy-lstStk").slideToggle("slow");
});
// Item History View Page Table Toggle Part End

// Setup Page Toggle Part Start
$(".stupLink").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().parent().find(".stup-SubMenu").slideToggle("slow");
});
// Setup Page Toggle Part End

// Manage Supplier Page Toggle Part Start
$(".suplrLink").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".supTbl-EmCol").slideToggle("slow");
});
// Manage Supplier Page Toggle Part End

// Manage Account Page Toggle Part Start
$(".mgAcntLink").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".mngAcntTbl-BlCol").slideToggle("slow");
});
// Manage Account Page Toggle Part End

// Manage Service Fee Page Toggle Part Start
$(".serFeeLnk").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".srvFeeTbl-UntCol").slideToggle("slow");
});
// Add Revenue Center Page Toggle Part End

// Item Manager Page Xcl & PDF Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 1024) {
      $(".mbItm-MngIcns").hide();
    } else {
      $(".itmMng-xlIcn").eq(0).appendTo(".mbItm-MngIcns");
      $(".itmMng-xlIcn").hide();
      $(".mbItm-MngIcns .itmMng-xlIcn").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 1024) {
    $(".mbItm-MngIcns").hide();
  } else {
    $(".itmMng-xlIcn").eq(0).appendTo(".mbItm-MngIcns");
    $(".itmMng-xlIcn").hide();
    $(".mbItm-MngIcns .itmMng-xlIcn").show();
  }
});
// Item Manager Page Xcl & PDF Clone End

// Item Manager Page Dropdown Clone Start
$(document).ready(function () {
  $(window).resize(function () {
    if ($(window).width() > 1024) {
      $(".clnItm-MngDrp").hide();
    } else {
      $(".drpHead-ItmMng").eq(0).appendTo(".clnItm-MngDrp");
      $(".drpHead-ItmMng").hide();
      $(".clnItm-MngDrp .drpHead-ItmMng").show();
    }
  });
});

$(document).ready(function () {
  if ($(window).width() > 1024) {
    $(".clnItm-MngDrp").hide();
  } else {
    $(".drpHead-ItmMng").eq(0).appendTo(".clnItm-MngDrp");
    $(".drpHead-ItmMng").hide();
    $(".clnItm-MngDrp .drpHead-ItmMng").show();
  }
});
// Item Manager Page Dropdown Clone End

// Item Manager Page Dropdown Toggle Part Start
$(".itmMng-FilterBtn").click(function () {
  $(this).parent().parent().parent().find(".clnItm-MngDrp").slideToggle("slow");
});
// Item Manager Page Dropdown Toggle Part End

// Item Manager Page Price Toggle Part Start
$(".mb-itmMngLink").click(function () {
  $(this).parent().siblings().find(".prcItm-MngClm").slideToggle("slow");
});
// Item Manager Page Price Toggle Part End

// Revenue Center (Sale & Cost) Toggle Part Start
$(".oltLnk").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".mbStock").slideToggle("slow");
});

$(".slCst-Lnk").click(function () {
  $(this).find(".fa-angle-down").toggleClass("fa-angle-up");
  $(this).parent().siblings().find(".mbStk-Detail").slideToggle("slow");
  $(this).parent().siblings().find(".mbStk-Flp").slideToggle("slow");
});
// Revenue Center (Sale & Cost) Toggle Part End

// Revenue Center (Sale & Cost) Flip Column Part Start
$(document).ready(function () {
  $(".cstBtn-Sale").on("click", function () {
    $(this).toggleClass("actvSale-Cst");

    if ($(window).width() < 992 && $(".hideBtn-Prc").hasClass("actvSale-Cst")) {
      $(".itmInfo-Otlt").css("width", "66%");
      $(".flipInfo-Clm .itmInfo-Otlt").css("width", "100%");
      $(".mblStock-Sale").css("text-align", "center");
      $(".flipInfo-Clm .mblStock-Sale").css("text-align", "end");
    } else {
      $(".itmInfo-Otlt").css("width", "100%");
      $(".mblStock-Sale").css("text-align", "end");
    }

    if (
      $(window).width() < 992 &&
      $(".hideBtn-Info").hasClass("actvSale-Cst")
    ) {
      $(".outletTask").css("display", "none");
    } else {
      $(".outletTask").css("display", "flex");
    }

    if ($(".hideBtn-Prc").hasClass("actvSale-Cst")) {
      $(".currItm-Info").slideDown();
      $(".mblAvr-Usg").slideDown();
      $(".currItm-Info").css("transition", "height 0.11s ease-in-out");
      $(".currItm-Info").css("display", "flex");
    } else {
      $(".currItm-Info").slideUp();
      $(".mblAvr-Usg").slideUp();
    }

    if ($(".hideBtn-Info").hasClass("actvSale-Cst")) {
      $(".flipClm-Otlt").fadeOut();
      $(".infoBtn-Hide").fadeOut();
      $(".flipClm-Hide").delay(500).fadeIn();
      $(".flipInfo-Clm").delay(500).fadeIn();
    } else {
      $(".flipClm-Otlt").delay(500).fadeIn();
      $(".infoBtn-Hide").delay(500).fadeIn();
      $(".flipClm-Hide").fadeOut();
      $(".flipInfo-Clm").fadeOut();
    }
  });

  if ($(window).width() < 992) {
    $(".outletNote").prop("disabled", true);
  } else {
    $(".outletNote").prop("disabled", false);
  }

  $(".slCst-Lnk").click(function () {
    $(this).addClass("myClik");
    if ($(".outletNote").is(":disabled")) {
      $(this).parent().siblings().find(".outletNote").prop("disabled", true);
      $(this).parent().siblings().find(".mbHide-Note").css("display", "none");
    } else {
      $(this).parent().siblings().find(".outletNote").prop("disabled", false);
      $(this).parent().siblings().find(".mbHide-Note").css("display", "block");
    }
  });
});

// Revenue Center (Sale & Cost) Flip Column Part End

// Revenue Center All Categories Toggle Btn Start
$(document).ready(function () {
  if ($(window).width() < 992) {
    var get_catogery_length = $(".setEze-Ctgry").length;

    if (get_catogery_length > 3) {
      $(".setEze-Ctgry:lt(3)").css("display", "flex");
      $(".ezeCat-All").css("display", "block");

      $(".ezeCat-All").click(function () {
        $(this).find(".fa-angle-down").toggleClass("fa-angle-up");

        if ($(".setEze-Ctgry").hasClass("clicked-Once")) {
          $(".setEze-Ctgry").removeClass("clicked-Once").css("display", "none");
          $(".setEze-Ctgry:lt(3)").css("display", "flex");
        } else {
          $(".setEze-Ctgry").addClass("clicked-Once").css("display", "flex");
        }
      });
    }
  } else {
    $(".setEze-Ctgry").css("display", "flex");
  }
});
// Revenue Center All Categories Toggle Btn End

// Custom Select Box JS Start
$(document).on("click", function (event) {
  if (!$(event.target).closest(".selectOption").length) {
    $(".cstmSelect").removeClass("optActive");
  }
});
$(".selectOption").click(function (event) {
  event.stopPropagation();
});

$(".selectOption").click(function () {
  $(this).parent().toggleClass("optActive");
});

$(".selectOption option").click(function () {
  $(this).parents(".cstmSelect").removeClass("optActive");
});
// Custom Select Box JS End

// Add/Edit Outlet Page Toggle Start
$(".mb-oltLnk").click(function () {
  $(this).parent().parent().parent().find(".unitOlt-Clm").slideToggle("slow");
});
// Add/Edit Outlet Page Toggle End

// Add/Edit Outlet Page Checkbox Start
$(document).ready(function () {
  $("#setOtlt").on("click", function () {
    $(".outletChk").toggle(this.checked);
  });
});

function removeQueryParameter(url, parameterName) {
    var urlParts = url.split('?');
    if (urlParts.length >= 2) {
        var prefix = encodeURIComponent(parameterName) + '=';
        var queryParams = urlParts[1].split(/[&;]/g);
        
        // Iterate through query parameters
        for (var i = queryParams.length; i-- > 0;) {
            if (queryParams[i].lastIndexOf(prefix, 0) === 0) {
                queryParams.splice(i, 1); // Remove parameter
            }
        }
        
        // Construct new URL with updated query parameters
        url = urlParts[0] + (queryParams.length > 0 ? '?' + queryParams.join('&') : '');
    }
    
    return url;
}



$(document).ready(function () {
  $(".cstBtn-Sale").on("click", function () {


        if( $(".actvSale-Cst").length )
        {
          $(".hideVariance").hide();
          $(".showVariance").show();
        }
        else
        {
          $(".hideVariance").show();
          $(".showVariance").show();
        }


  });
});

// Add/Edit Outlet Page Checkbox End
// Login page password field toggle visibility

// const passwordField = document.getElementById("password");
// const togglePassword = document.querySelector(".password-toggle-icon i");

// togglePassword.addEventListener("click", function () {
//   if (passwordField.type === "password") {
//     passwordField.type = "text";
//     togglePassword.classList.remove("fa-eye");
//     togglePassword.classList.add("fa-eye-slash");
//   } else {
//     passwordField.type = "password";
//     togglePassword.classList.remove("fa-eye-slash");
//     togglePassword.classList.add("fa-eye");
//   }
// });
