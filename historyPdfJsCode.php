<script>



//     $('body').on('click', '.cstm-BtnHistory', function() {

// var allCustomSection = $('.checkRowHistory').css('display');

// if (allCustomSection == "block") {
//     $('.checkRowHistory').css('display', 'none');
// } else {
//     $('.checkRowHistory').css('display', 'block');
// }

//     });

    

 
    
// // Show/Hide all three section checkBox on custom button click.


// // End
// // By default checked all three section checkBox

// // End
// //////////////////////////////////////////////////////////////////////////
// // Header section start
// // Open header sub section list when click on header text or arrow

// $(document).click(function(event) {
//     if (!$(event.target).closest('.header-txtHistory, #show-headerHistory').length) {
//         $('#show-headerHistory').css('display', 'none');
//     }
// });

// $('body').on('click', '.header-txtHistory', function() {


//     event.stopPropagation();
//     $('#show-headerHistory').toggle();
//     $('#show-smryHistory').css('display', 'none');
//     $('#show-itmHistory').css('display', 'none');

   

// });
// // Do this if click on header check all
$('body').on('click', '.headChk-AllHistory', function() {


    if( $(".headChk-AllHistory:checked").length == 1  ) 
    {
        $("#headerHistory").prop('checked', true);
        $(".header-addressHistory").prop('checked', true);
        $(".header-logoHistory").prop('checked', true);

        $('.address-sectionHistory').css('display', 'block');
        $('.logo-sectionHistory').css('display', 'block');
        
    }
    else
    {
        $("#headerHistory").prop('checked', false);
        $(".header-addressHistory").prop('checked', false);
        $(".header-logoHistory").prop('checked', false);


        $('.address-sectionHistory').css('display', 'none');
        $('.logo-sectionHistory').css('display', 'none');
        
    }

});

// $('body').on('click', '#headerHistory', function() {


// if(   $("#headerHistory:checked").length == 1 ) 
// {
//     $(".headChk-AllHistory").prop('checked', true);
//     $(".header-addressHistory").prop('checked', true);
//     $(".header-logoHistory").prop('checked', true);


//     $('.address-sectionHistory').css('display', 'block');
//     $('.logo-sectionHistory').css('display', 'block');
    
// }
// else
// {
//     $(".headChk-AllHistory").prop('checked', false);
//     $(".header-addressHistory").prop('checked', false);
//     $(".header-logoHistory").prop('checked', false);

//     $('.address-sectionHistory').css('display', 'none');
//     $('.logo-sectionHistory').css('display', 'none');
    
// }

// });
// //end header check all sections

// // Summary section start
// $(document).click(function(event) {
//     if (!$(event.target).closest('.summary-txtHistory, #show-smryHistory').length) {
//         $('#show-smryHistory').css('display', 'none');
//     }
// });

// $('body').on('click', '.summary-txtHistory', function() {

//     event.stopPropagation();
//     $('#show-smryHistory').toggle();
//     $('#show-headerHistory').css('display', 'none');
//     $('#show-itmHistory').css('display', 'none');   

// });

$('body').on('click', '.smryChk-AllHistory', function() {


    if( $(".smryChk-AllHistory:checked").length == 1  ) 
    {
        //$("#summaryHistory").prop('checked', true);
        
        $('.summaryPart').css('display', 'block');
        $('.summaryPartCell').css('display', 'table-cell');
        
        $(".smryCheckboxHistory").prop('checked', true);
        
    }
    else
    {
        $(".smryCheckboxHistory").prop('checked', false);
        $('.summaryPart').css('display', 'none');
       // $("#summaryHistory").prop('checked', false);
        
    }


            $(".issueInSection").removeClass("col-2");
            $(".issueInSection").removeClass("col-3");
            $(".issueInSection").removeClass("col-6");
            $(".issueInSection").removeClass("col-7");
            $(".issueInSection").removeClass("col-12");

            $(".issueOutSection").removeClass("col-2");
            $(".issueOutSection").removeClass("col-6");
            $(".issueOutSection").removeClass("col-7");
            $(".issueOutSection").removeClass("col-3");
            $(".issueOutSection").removeClass("col-12");

            $("#varianceId").removeClass("col-2");
            $("#varianceId").removeClass("col-3");
            $("#varianceId").removeClass("col-7");
            $("#varianceId").removeClass("col-6");
            $("#varianceId").removeClass("col-12");

        if ( $('.otherCurSection').is(":visible") && $('#totalOtherCur').val() > 2 )
        {

                $(".issueOutSection").addClass("col-3");
           
                $("#varianceId").addClass("col-2");

                 $(".issueInSection").addClass("col-7");
           
            
        }
        else
        {

            $(".issueOutSection").addClass("11 col-3");
           
            $(".issueInSection").addClass("22 col-7");

            $("#varianceId").addClass("33 col-2");
        }


});

//end summary section

// Item table section start
$(document).click(function(event) {
    if (!$(event.target).closest('.itemTable-txtHistory, #show-itmHistory').length) {
        $('#show-itmHistory').css('display', 'none');
    }
});

$('body').on('click', '.itemTable-txtHistory', function() {

    event.stopPropagation();
    $('#show-itmHistory').toggle();
    $('#show-smryHistory').css('display', 'none');
    $('#show-headerHistory').css('display', 'none');

});



$('body').on('click', '.itemChk-AllHistory', function() {


    if( $(".itemChk-AllHistory:checked").length == 1  ) 
    {
        $("#itemTableHistory").prop('checked', true);
        $('.itemSectionPart').css('display', 'block');
        $(".itmTblCheckboxHistory").prop('checked', true);
        
    }
    else
    {
        $("#itemTableHistory").prop('checked', false);
        $('.itemSectionPart').css('display', 'none');
        $(".itmTblCheckboxHistory").prop('checked', false);
        
    }

});

$('body').on('click', '#itemTableHistory', function() {


    if( $("#itemTableHistory:checked").length == 1  ) 
    {
        $(".itemChk-AllHistory").prop('checked', true);
        $('.itemSectionPart').css('display', 'block');
        $(".itmTblCheckboxHistory").prop('checked', true);
        
    }
    else
    {
        $(".itemChk-AllHistory").prop('checked', false);
        $('.itemSectionPart').css('display', 'none');
        $(".itmTblCheckboxHistory").prop('checked', false);
        
    }

});
//end item table section part

function showHideByClassHistoryItem(targetId)
{
    if ($('.' + targetId).is(":visible")) {
        $('.' + targetId).css('display', 'none');

        if( targetId == 'head8' )
        {
            $(".item-def-curr-value").prop('checked', false);
            $(".item-sec-curr-value").prop('checked', false);
        }

    } else {
        $('.' + targetId).css('display', 'block');

        if( targetId == 'head8' )
        {
            $(".item-def-curr-value").prop('checked', true);
            $(".item-sec-curr-value").prop('checked', true);
        }
    }

    if(targetId == 'itemTable-def-curr' || targetId == 'itemTable-other-curr')
    {
        if( $(".item-def-curr-value:checked").length == 1 || $(".item-sec-curr-value:checked").length == 1 )
        {

            if( $(".item-def-curr-value:checked").length == 0 )
            {
                $('.itemTable-def-curr').css('display', 'none');
            }
            if( $(".item-sec-curr-value:checked").length == 0 )
            {
                $('.itemTable-other-curr').css('display', 'none');
            }

            if( $(".item-def-curr-value:checked").length == 1 )
            {
                $('.itemTable-def-curr').css('display', 'block');
            }
            if( $(".item-sec-curr-value:checked").length == 1 )
            {
                $('.itemTable-other-curr').css('display', 'block');
            }


            $('.valueItem').css('display', 'block');
            $(".item-value").prop('checked', true);
        }
        else if( $(".item-def-curr-value:checked").length == 0 && $(".item-sec-curr-value:checked").length == 0 )
        {
            $('.valueItem').css('display', 'none');
            $(".item-value").prop('checked', false);
        }
    }

    $('.itemMainPart').css('display', 'block');
    
}

function showHideByClassHistory(targetId) {
    if ($('.' + targetId).is(":visible")) {
        $('.' + targetId).css('display', 'none');

    }
    else {

        if(targetId == 'paidSection')
        {
            $('.paidSection').css('display', 'table-cell');
        }
        else if(targetId == 'pendingSection')
        {
            $('.pendingSection').css('display', 'table-cell');
        }
        else if(targetId == 'receiveSection')
        {
            $('.receiveSection').css('display', 'table-cell');
        }
        else if(targetId == 'issueOutPendingSection')
        {
            $('.issueOutPendingSection').css('display', 'table-cell');
        }
        else
        {
            $('.' + targetId).css('display', 'block');
        }
       
    }
   
    if(targetId == 'defaultCurSection' || targetId == 'otherCurSection' )
    {
        $('.issueInSection').css('display', 'block');
    }
    
    


    if(targetId == 'issueOutSection')
    {
        if (  !$('.issueOutSection').is(":visible")  )
        {

            $(".summary-issue-out-receive").prop('checked', false);
            $(".summary-issue-out-pending").prop('checked', false);

            $('.receiveSection').css('display', 'none');
            $('.issueOutPendingSection').css('display', 'none');
        }
        else
        {
            $(".summary-issue-out-receive").prop('checked', true);
            $(".summary-issue-out-pending").prop('checked', true);

            $('.receiveSection').css('display', 'table-cell');
            $('.issueOutPendingSection').css('display', 'table-cell');
        }
    }
    
    if(targetId == 'issueInSection')
    {
        if (  !$('.issueInSection').is(":visible")  )
        {
            $(".summary-default-currencyHistory").prop('checked', false);
            $(".summary-other-currencyHistory").prop('checked', false);

            $('.defaultCurSection').css('display', 'none');
            $('.otherCurSection').css('display', 'none');

            $(".summary-paid").prop('checked', false);
            $(".summary-pending").prop('checked', false);

            $('.paidSection').css('display', 'none');
            $('.pendingSection').css('display', 'none');
        }
        else
        {
            $(".summary-default-currencyHistory").prop('checked', true);
            $(".summary-other-currencyHistory").prop('checked', true);

            $('.defaultCurSection').css('display', 'block');
            $('.otherCurSection').css('display', 'block');

            $(".summary-paid").prop('checked', true);
            $(".summary-pending").prop('checked', true);

            $('.paidSection').css('display', 'table-cell');
            $('.pendingSection').css('display', 'table-cell');
        }
    }
    else
    {
        if (  !$('.defaultCurSection').is(":visible") && !$('.otherCurSection').is(":visible") ) {
        
            //$('.issueInSection').css('display', 'none');
            $(".summary-issue-in").prop('checked', false);

            
        } else if ( $('.defaultCurSection').is(":visible") || $('.otherCurSection').is(":visible") ) {
            $(".summary-issue-in").prop('checked', true);
            $('.issueInSection').css('display', 'block');
        }
    }
    
    if (  $('.varianceRow').is(":visible") && !$('.issueInSection').is(":visible") && !$('.issueOutSection').is(":visible") )
    {
        $("#varianceId").removeClass("col-2");
        $("#varianceId").removeClass("col-6");
        $("#varianceId").addClass("col-12");
        
    }

    if (  $('.issueInSection').is(":visible") && !$('.varianceRow').is(":visible") && !$('.issueOutSection').is(":visible") )
    {
        $(".issueInSection").removeClass("col-7");
        $(".issueInSection").removeClass("col-12");
        $(".issueInSection").addClass("col-12");
        
    }

    if (  $('.issueOutSection').is(":visible") && !$('.varianceRow').is(":visible") && !$('.issueInSection').is(":visible") )
    {
        $(".issueOutSection").removeClass("col-3");
        $(".issueOutSection").removeClass("col-6");
        $(".issueOutSection").addClass("col-12");
        
    }
     
    if (  $('.issueOutSection').is(":visible") && $('.varianceRow').is(":visible") && !$('.issueInSection').is(":visible") )
    {
        $(".issueOutSection").removeClass("col-3");
        $(".issueOutSection").removeClass("col-12");
        $(".issueOutSection").addClass("col-7");

        $("#varianceId").removeClass("col-2");
        $("#varianceId").removeClass("col-12");
        $("#varianceId").addClass("col-5");
        
    }
    
    if (  $('.issueInSection').is(":visible") && $('.issueOutSection').is(":visible") && !$('.varianceRow').is(":visible") )
    {
        $(".issueOutSection").removeClass("col-3");
        $(".issueOutSection").removeClass("col-6");
        $(".issueOutSection").removeClass("col-7");
        $(".issueOutSection").removeClass("col-12");
        $(".issueOutSection").addClass("col-5");

        $(".issueInSection").removeClass("col-7");
        //$(".issueInSection").removeClass("col-md-6");
        //$(".issueInSection").removeClass("col-md-7");
        $(".issueInSection").removeClass("col-12");
        $(".issueInSection").addClass("col-7");

        
    }

    if (  $('.issueInSection').is(":visible") && !$('.issueOutSection').is(":visible") && $('.varianceRow').is(":visible") )
    {
       
        //$(".issueInSection").removeClass("col-2");
        $(".issueInSection").removeClass("col-6");
        $(".issueInSection").removeClass("col-7");
        $(".issueInSection").removeClass("col-12");
        $(".issueInSection").addClass("col-7");

        $("#varianceId").removeClass("col-2");
        $("#varianceId").removeClass("col-6");
        $("#varianceId").removeClass("col-7");
        $("#varianceId").removeClass("col-12");
        $("#varianceId").addClass("col-5");

        
    }
    
    if (  $('.issueOutSection').is(":visible") && $('.varianceRow').is(":visible") && $('.issueInSection').is(":visible") )
    {
        $(".issueOutSection").removeClass("col-5");
        $(".issueOutSection").removeClass("col-7");
        // $(".issueOutSection").removeClass("col-md-7");
        // $(".issueOutSection").removeClass("col-md-3");
        $(".issueOutSection").removeClass("col-12");
        $(".issueOutSection").addClass("col-3");

        $(".issueInSection").removeClass("col-6");
       // $(".issueInSection").removeClass("col-md-3");
       // $(".issueInSection").removeClass("col-md-6");
        $(".issueInSection").removeClass("col-7");
        $(".issueInSection").removeClass("col-12");
        $(".issueInSection").addClass("col-7");

        $("#varianceId").removeClass("col-2");
        $("#varianceId").removeClass("col-5");
      //  $("#varianceId").removeClass("col-md-7");
        $("#varianceId").removeClass("col-6");
        $("#varianceId").removeClass("col-12");
        $("#varianceId").addClass("col-2");
        
    }

    // if ( !$('.otherCurSection').is(":visible") &&  $('.issueOutSection').is(":visible") && $('.varianceRow').is(":visible") && $('.issueInSection').is(":visible") )
    // {
    //     $(".issueOutSection").removeClass("col-md-2");
    //     $(".issueOutSection").removeClass("col-md-6");
    //     $(".issueOutSection").removeClass("col-md-7");
    //     $(".issueOutSection").removeClass("col-md-3");
    //     $(".issueOutSection").removeClass("col-md-12");
    //     $(".issueOutSection").addClass("col-md-4");

    //     $(".issueInSection").removeClass("col-md-2");
    //     $(".issueInSection").removeClass("col-md-3");
    //     $(".issueInSection").removeClass("col-md-6");
    //     $(".issueInSection").removeClass("col-md-7");
    //     $(".issueInSection").removeClass("col-md-12");
    //     $(".issueInSection").addClass("col-md-4");

    //     $("#varianceId").removeClass("col-md-2");
    //     $("#varianceId").removeClass("col-md-3");
    //     $("#varianceId").removeClass("col-md-7");
    //     $("#varianceId").removeClass("col-md-6");
    //     $("#varianceId").removeClass("col-md-12");
    //     $("#varianceId").addClass("col-md-4");
        
    // }
       
    // if ( $('.otherCurSection').is(":visible") && $('#totalOtherCur').val() > 2 )
    // {


    //     if ( $('.issueOutSection').is(":visible") && !$('#varianceId').is(":visible")  )
    //     {
    //         $(".issueOutSection").addClass("col-md-12");
    //     }
    //     else if ( !$('.issueOutSection').is(":visible") && $('#varianceId').is(":visible")  )
    //     {
    //         $("#varianceId").addClass("col-md-12");
    //     }
    //     else
    //     {
            
    //         $(".issueOutSection").addClass("col-md-6");
        
    //         $("#varianceId").addClass("col-md-6");
    //     }
            

    //         $(".issueInSection").addClass("col-md-12");
        
    // }

    

    



}






</script>