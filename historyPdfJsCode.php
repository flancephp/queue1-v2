<script>
    // // Do this if click on header check all
    $('body').on('click', '.headChk-AllHistory', function() {


        if ($(".headChk-AllHistory:checked").length == 1) {
            $("#headerHistory").prop('checked', true);
            $(".header-addressHistory").prop('checked', true);
            $(".header-logoHistory").prop('checked', true);

            $('.address-sectionHistory').css('display', 'block');
            $('.logo-sectionHistory').css('display', 'block');

        } else {
            $("#headerHistory").prop('checked', false);
            $(".header-addressHistory").prop('checked', false);
            $(".header-logoHistory").prop('checked', false);


            $('.address-sectionHistory').css('display', 'none');
            $('.logo-sectionHistory').css('display', 'none');

        }

    });

    $('body').on('click', '.smryChk-AllHistory', function() {


        if ($(".smryChk-AllHistory:checked").length == 1) {
            //$("#summaryHistory").prop('checked', true);

            $('.summaryPart').css('display', 'block');
            $('.summaryPartCell').css('display', 'table-cell');

            $(".smryCheckboxHistory").prop('checked', true);

        } else {
            $(".smryCheckboxHistory").prop('checked', false);
            $('.summaryPart').css('display', 'none');
            // $("#summaryHistory").prop('checked', false);

        }



        if ($('.issueInSection').is(":visible") && $('.issueOutSection').is(":visible")) {

            $(".issueOutSection").removeClass("col-12");
            $(".issueInSection").removeClass("col-12");

            $(".issueOutSection").addClass("col-6");
            $(".issueInSection").addClass("col-6");

        } else if (!$('.issueOutSection').is(":visible")) {

            $(".issueInSection").removeClass("col-6");

            $(".issueInSection").addClass("col-12");

        } else if (!$('.issueInSection').is(":visible")) {

            $(".issueOutSection").removeClass("col-6");

            $(".issueOutSection").addClass("col-12");

        }

        if ($('.varianceRow').is(":visible") && $('.convertedRow').is(":visible")) {

            $(".varianceRow").removeClass("col-12");
            $(".convertedRow").removeClass("col-12");

            $(".varianceRow").addClass("col-6");
            $(".convertedRow").addClass("col-6");

        } else if (!$('.varianceRow').is(":visible")) {

            $(".convertedRow").addClass("col-6");

            $(".convertedRow").addClass("col-12");

        } else if (!$('.convertedRow').is(":visible")) {
            $(".varianceRow").addClass("col-6");

            $(".varianceRow").addClass("col-12");

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


        if ($(".itemChk-AllHistory:checked").length == 1) {
            $("#itemTableHistory").prop('checked', true);
            $('.itemSectionPart').css('display', 'block');
            $(".itmTblCheckboxHistory").prop('checked', true);

        } else {
            $("#itemTableHistory").prop('checked', false);
            $('.itemSectionPart').css('display', 'none');
            $(".itmTblCheckboxHistory").prop('checked', false);

        }

    });

    $('body').on('click', '#itemTableHistory', function() {


        if ($("#itemTableHistory:checked").length == 1) {
            $(".itemChk-AllHistory").prop('checked', true);
            $('.itemSectionPart').css('display', 'block');
            $(".itmTblCheckboxHistory").prop('checked', true);

        } else {
            $(".itemChk-AllHistory").prop('checked', false);
            $('.itemSectionPart').css('display', 'none');
            $(".itmTblCheckboxHistory").prop('checked', false);

        }

    });
    //end item table section part

    function showHideByClassHistoryItem(targetId) {
        if ($('.' + targetId).is(":visible")) {
            $('.' + targetId).css('display', 'none');

            if (targetId == 'head8') {
                $(".item-def-curr-value").prop('checked', false);
                $(".item-sec-curr-value").prop('checked', false);
            }

        } else {
            $('.' + targetId).css('display', 'block');

            if (targetId == 'head8') {
                $(".item-def-curr-value").prop('checked', true);
                $(".item-sec-curr-value").prop('checked', true);
            }
        }

        if (targetId == 'itemTable-def-curr' || targetId == 'itemTable-other-curr') {
            if ($(".item-def-curr-value:checked").length == 1 || $(".item-sec-curr-value:checked").length == 1) {

                if ($(".item-def-curr-value:checked").length == 0) {
                    $('.itemTable-def-curr').css('display', 'none');
                }
                if ($(".item-sec-curr-value:checked").length == 0) {
                    $('.itemTable-other-curr').css('display', 'none');
                }

                if ($(".item-def-curr-value:checked").length == 1) {
                    $('.itemTable-def-curr').css('display', 'block');
                }
                if ($(".item-sec-curr-value:checked").length == 1) {
                    $('.itemTable-other-curr').css('display', 'block');
                }


                $('.valueItem').css('display', 'block');
                $(".item-value").prop('checked', true);
            } else if ($(".item-def-curr-value:checked").length == 0 && $(".item-sec-curr-value:checked").length == 0) {
                $('.valueItem').css('display', 'none');
                $(".item-value").prop('checked', false);
            }
        }

        $('.itemMainPart').css('display', 'block');

    }

    function showHideByClassHistory(targetId) {
        if ($('.' + targetId).is(":visible")) {
            $('.' + targetId).css('display', 'none');

        } else {

            if (targetId == 'paidSection') {
                $('.paidSection').css('display', 'table-cell');
            } else if (targetId == 'pendingSection') {
                $('.pendingSection').css('display', 'table-cell');
            } else if (targetId == 'receiveSection') {
                $('.receiveSection').css('display', 'table-cell');
            } else if (targetId == 'issueOutPendingSection') {
                $('.issueOutPendingSection').css('display', 'table-cell');
            } else {
                $('.' + targetId).css('display', 'block');
            }

        }

        if (targetId == 'defaultCurSection' || targetId == 'otherCurSection') {
            $('.issueInSection').css('display', 'block');
        }

        if (targetId == 'issueOutSection') {
            if (!$('.issueOutSection').is(":visible")) {

                $(".summary-issue-out-receive").prop('checked', false);
                $(".summary-issue-out-pending").prop('checked', false);

                $('.receiveSection').css('display', 'none');
                $('.issueOutPendingSection').css('display', 'none');
            } else {
                $(".summary-issue-out-receive").prop('checked', true);
                $(".summary-issue-out-pending").prop('checked', true);

                $('.receiveSection').css('display', 'table-cell');
                $('.issueOutPendingSection').css('display', 'table-cell');
            }
        }

        if (targetId == 'issueInSection') {
            if (!$('.issueInSection').is(":visible")) {
                $(".summary-default-currencyHistory").prop('checked', false);
                $(".summary-other-currencyHistory").prop('checked', false);

                $('.defaultCurSection').css('display', 'none');
                $('.otherCurSection').css('display', 'none');

                $(".summary-paid").prop('checked', false);
                $(".summary-pending").prop('checked', false);

                $('.paidSection').css('display', 'none');
                $('.pendingSection').css('display', 'none');
            } else {
                $(".summary-default-currencyHistory").prop('checked', true);
                $(".summary-other-currencyHistory").prop('checked', true);

                $('.defaultCurSection').css('display', 'block');
                $('.otherCurSection').css('display', 'block');

                $(".summary-paid").prop('checked', true);
                $(".summary-pending").prop('checked', true);

                $('.paidSection').css('display', 'table-cell');
                $('.pendingSection').css('display', 'table-cell');
            }
        } else {
            if (!$('.defaultCurSection').is(":visible") && !$('.otherCurSection').is(":visible")) {

                //$('.issueInSection').css('display', 'none');
                $(".summary-issue-in").prop('checked', false);
                $('.issueInSection').css('display', 'none');


            } else if ($('.defaultCurSection').is(":visible") || $('.otherCurSection').is(":visible")) {
                $(".summary-issue-in").prop('checked', true);
                $('.issueInSection').css('display', 'block');
            }
        }



        if ($('.issueInSection').is(":visible") && $('.issueOutSection').is(":visible")) {

            $(".issueOutSection").removeClass("col-12");
            $(".issueInSection").removeClass("col-12");

            $(".issueOutSection").addClass("col-6");
            $(".issueInSection").addClass("col-6");

        } else if (!$('.issueOutSection').is(":visible")) {

            $(".issueInSection").removeClass("col-6");

            $(".issueInSection").addClass("col-12");

        } else if (!$('.issueInSection').is(":visible")) {

            $(".issueOutSection").removeClass("col-6");

            $(".issueOutSection").addClass("col-12");

        }

        if ($('.varianceRow').is(":visible") && $('.convertedRow').is(":visible")) {

            $(".varianceRow").removeClass("col-12");
            $(".convertedRow").removeClass("col-12");

            $(".varianceRow").addClass("col-6");
            $(".convertedRow").addClass("col-6");

        } else if (!$('.varianceRow').is(":visible")) {

            $(".convertedRow").addClass("col-6");

            $(".convertedRow").addClass("col-12");

        } else if (!$('.convertedRow').is(":visible")) {
            $(".varianceRow").addClass("col-6");

            $(".varianceRow").addClass("col-12");

        }


    }
</script>