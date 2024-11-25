<script>
    $('body').on('click', '.cstm-Btn', function() {

        var allCustomSection = $('.checkRow').css('display');

        if (allCustomSection == "block") {
            $('.checkRow').css('display', 'none');
        } else {
            $('.checkRow').css('display', 'block');
        }

    });










    //order journey and requisition code goes here------------------------------------------------------------------------------------------------






    $('body').on('click', '.header-txt', function() {
        event.stopPropagation();
        $('#show-header').toggle();
        $('#show-smry').css('display', 'none');
        $('#show-itm').css('display', 'none');
    });


    // Checkbox Check all
    $('body').on('change', '.headCheckbox', function() {
        if ($(".headCheckbox").length == $(".headCheckbox:checked").length)
            $(".headChk-All").prop('checked', true);
        else
            $(".headChk-All").prop('checked', false);
    });
    // End




    //Header parts goes here
    $('body').on('click', '#header', function() {

        //$('#show-header').show();
        $('#show-header').css('display', 'block');



        if ($("#header:checked").length == 1) {
            $(".headChk-All").prop('checked', true);
            $(".headCheckbox").prop('checked', true);
            $('.headerTxt').css('display', 'block');
        } else {
            $(".headChk-All").prop('checked', false);
            $(".headCheckbox").prop('checked', false);
            $('.headerTxt').css('display', 'none');
        }

    });

    $('body').on('click', '.headChk-All', function() {

        //$('#show-header').show();
        $('#show-header').css('display', 'block');

        if ($(".headChk-All:checked").length == 1) {
            $("#header").prop('checked', true);
            $(".headCheckbox").prop('checked', true);
            $('.headerTxt').css('display', 'block');
        } else {
            $("#header").prop('checked', false);
            $(".headCheckbox").prop('checked', false);
            $('.headerTxt').css('display', 'none');
        }

    });

    $(document).click(function(event) {
        if (!$(event.target).closest('.header-txt, #show-header').length) {
            $('#show-header').css('display', 'none');
        }
    });
    //End hearder parts end here

    //Summary parts starts here



    $(document).click(function(event) {
        if (!$(event.target).closest('.summary-txt, #show-smry').length) {
            $('#show-smry').css('display', 'none');
        }
    });

    $('body').on('click', '.summary-txt', function() {
        event.stopPropagation();
        $('#show-smry').toggle();
        $('#show-header').css('display', 'none');
        $('#show-itm').css('display', 'none');
    });

    $('body').on('click', '#summary', function() {
        $('#show-smry').css('display', 'block');
        $('#show-header').css('display', 'none');
        $('#show-itm').css('display', 'none');


        if ($("#summary:checked").length == 1) {
            $('.show-smry-cls').css('display', 'block');
            $('#smrySuplr').css('display', 'block');
            $('#smryPayment').css('display', 'block');
            $('.smryHead').css('display', 'block');
            $('.smryDef_Val').css('display', 'block');
            $('.smryOtr_Val').css('display', 'block');


            $(".smryChk-All").prop('checked', true);
            $(".smryCheckbox").prop('checked', true);

        } else {
            $(".smryChk-All").prop('checked', false);
            $(".smryCheckbox").prop('checked', false);
            $('.show-smry-cls').css('display', 'none');

            $('#smrySuplr').css('display', 'none');
            $('#smryPayment').css('display', 'none');
            $('.smryHead').css('display', 'none');
            $('.smryDef_Val').css('display', 'none');
            $('.smryOtr_Val').css('display', 'none');


        }


    });

    $('body').on('click', '.smryChk-All', function() {

        $('#show-smry').css('display', 'block');
        $('#show-header').css('display', 'none');
        $('#show-itm').css('display', 'none');


        if ($(".smryChk-All:checked").length == 1) {

            $('.sumBreakupAmtText').css('display', 'block');
            $('.SummaryItems').css('display', 'table-row');
            $('.show-smry-cls').css('display', 'block');
            $('#smrySuplr').css('display', 'block');
            $('#smryPayment').css('display', 'block');
            $('.smryHead').css('display', 'block');
            $('.smryDef_Val').css('display', 'block');
            $('.smryOtr_Val').css('display', 'block');
            $('.sumMainDiv').css('display', 'table-row');

            $("#summary").prop('checked', true);
            $(".smryCheckbox").prop('checked', true);


        } else {


            $('.sumMainDiv').css('display', 'none');
            $('.SummaryItems').css('display', 'none');
            $('.sumBreakupAmtText').css('display', 'none');
            $("#summary").prop('checked', false);
            $(".smryCheckbox").prop('checked', false);
            $('.show-smry-cls').css('display', 'none');
            $('#smrySuplr').css('display', 'none');
            $('#smryPayment').css('display', 'none');
            $('.smryHead').css('display', 'none');
            $('.smryDef_Val').css('display', 'none');
            $('.smryOtr_Val').css('display', 'none');

        }


    });


    // updateVisibility();
    $('body').on('change', '.summary-default-currency, .summary-second-currency', function() {

        updateVisibility();
    });





    //End summary parts

    //start item parts
    $(document).click(function(event) {
        if (!$(event.target).closest('.itemTable-txt, #show-itm').length) {
            $('#show-itm').css('display', 'none');
        }
    });
    $('body').on('click', '.itemTable-txt', function() {
        event.stopPropagation();

        $('#show-header').css('display', 'none');
        $('#show-smry').css('display', 'none');

        $('#show-itm').toggle();
    });

    $('body').on('click', '.pdfFile', function() {

        $('#show-itm').css('display', 'none');
        $('#show-header').css('display', 'none');
        $('#show-smry').css('display', 'none');

    });

    $('body').on('click', '#taskRecord', function() {
        $('#show-itm').css('display', 'none');
        $('#show-header').css('display', 'none');
        $('#show-smry').css('display', 'none');

    });

    $('body').on('click', '#taskrecord', function() {
        $('#show-itm').css('display', 'none');
        $('#show-header').css('display', 'none');
        $('#show-smry').css('display', 'none');

    });

    //Item list check/uncheck
    $('body').on('click', '#itemTable', function() {
        $('#show-itm').css('display', 'block');


        if ($("#itemTable:checked").length == 1) {
            $(".itemChk-All").prop('checked', true);
            $(".itmTblCheckbox").prop('checked', true);
            $('#itemDiv').css('display', 'block');

            $('.photo').css('display', 'block');
            $('.itmProd').css('display', 'block');
            $('.itmCode').css('display', 'block');

            $('.itmTotal').css('display', 'block');
            $('.itmPrc').css('display', 'block');
            $('.otherCurPrice').css('display', 'block');
            $('.itmPrcunit').css('display', 'block');
            $('.itmPurqty').css('display', 'block');
            $('.itmRecqty').css('display', 'block');
            $('.otherCurTotal').css('display', 'block');
            $('.itmNote').css('display', 'block');


        } else {
            $(".itemChk-All").prop('checked', false);
            $(".itmTblCheckbox").prop('checked', false);
            $('#itemDiv').css('display', 'none');


            $('.photo').css('display', 'none');
            $('.itmProd').css('display', 'none');
            $('.itmCode').css('display', 'none');
            $('.itmTotal').css('display', 'none');
            $('.itmPrc').css('display', 'none');
            $('.otherCurPrice').css('display', 'none');
            $('.itmPrcunit').css('display', 'none');
            $('.itmPurqty').css('display', 'none');
            $('.itmRecqty').css('display', 'none');
            $('.otherCurTotal').css('display', 'none');
            $('.itmNote').css('display', 'none');
        }

    });

    $('body').on('click', '.itemChk-All', function() {

        $('#show-itm').css('display', 'block');


        if ($(".itemChk-All:checked").length == 1) {


            $('#itemDiv').css('display', 'block');
            $("#itemTable").prop('checked', true);
            $(".itmTblCheckbox").prop('checked', true);

            $('.photo').css('display', 'block');
            $('.itmProd').css('display', 'block');
            $('.itmCode').css('display', 'block');

            $('.itmTotal').css('display', 'block');
            $('.itmPrc').css('display', 'block');
            $('.otherCurPrice').css('display', 'block');
            $('.itmPrcunit').css('display', 'block');
            $('.itmPurqty').css('display', 'block');
            $('.itmRecqty').css('display', 'block');
            $('.otherCurTotal').css('display', 'block');
            $('.itmNote').css('display', 'block');
            $('.itemMainDiv').css('display', 'table-row');



        } else {
            $("#itemTable").prop('checked', false);
            $(".itmTblCheckbox").prop('checked', false);

            $('.photo').css('display', 'none');
            $('.itmProd').css('display', 'none');
            $('.itmCode').css('display', 'none');
            $('.itmTotal').css('display', 'none');
            $('.itmPrc').css('display', 'none');
            $('.otherCurPrice').css('display', 'none');
            $('.itmPrcunit').css('display', 'none');
            $('.itmPurqty').css('display', 'none');
            $('.itmRecqty').css('display', 'none');
            $('.otherCurTotal').css('display', 'none');
            $('.itmNote').css('display', 'none');

            $('.itemMainDiv').css('display', 'none');
        }

    });
    //end Item list check/uncheck       


    //End item parts

    //tasks code goes here

    $('body').on('click', '#taskRecord', function() {
        $('#show-itm').css('display', 'none');
        $('#show-header').css('display', 'none');
        $('#show-smry').css('display', 'none');

    });

    $('body').on('click', '#taskrecord', function() {
        $('#show-itm').css('display', 'none');
        $('#show-header').css('display', 'none');
        $('#show-smry').css('display', 'none');

    });

    //end task code
</script>