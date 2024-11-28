<?php
include('inc/dbConfig.php'); //connection details


if (!isset($_SESSION['adminidusername'])) {

    echo "<script>window.location='login.php'</script>";
    exit;
}

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Running Tasks - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css?v=1">
    <link rel="stylesheet" href="Assets/css/style1.css">

</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row g-0">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Dashboard'); ?></h1>
                        </div>
                        <div class="col-md-8 d-flex align-items-center justify-content-end">
                            <div class="mbPage">
                                <div class="mb-nav" id="mb-nav">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                    </button>
                                </div>
                                <div class="mbpg-name">
                                    <h1 class="h1"><?php echo showOtherLangText('Dashboard'); ?></h1>
                                </div>
                            </div>
                            <?php require_once('header.php'); ?>
                        </div>
                    </div>
                </section>



                <section class="rntskHead px-0">
                    <div class="container">
                        Welcome to Dashboard
                    </div>

                </section>


            </div>
        </div>
    </div>

    <div id="dialog" style="display: none;">
        <?php echo showOtherLangText('Are you sure to delete this record?') ?>
    </div>
    <?php require_once('footer.php'); ?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <form action="" method="post" id="assignUser" name="assignUser">
        <div class="modal" tabindex="-1" id="assign-order" aria-labelledby="edit-Assign-OrderLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h1 class="modal-title h1 mt-3"><?php echo showOtherLangText('Assign Order to User') ?></h1>
                    </div>
                    <div class="modal-body">

                        <div>
                            <input type="hidden" name="orderType" class="orderType" value="">
                            <input type="hidden" name="orderId" class="orderId" value="">
                            <input type="hidden" name="checkedTotal" id="checkedTotal" value="">
                        </div>
                        <div>
                            <strong class="checkAllSectionBox">
                                <input type="checkbox" class="CheckAllOptions" id="CheckAllOptions">
                                <label>
                                    <?php echo showOtherLangText('Check All') ?>
                                </label>
                            </strong>
                        </div>
                        <div class="mobUserList">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit"
                                class="btn btn-primary std-btn"><?php echo showOtherLangText('Save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="POST" id="frm_issueOutPopUpFrm" name="issueOutPopUpFrm">
        <div class="modal" tabindex="-1" id="issue-out" aria-labelledby="edit-Assign-OrderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body text-center fs-13">
                        <p class="modal-title h1 mt-3"><?php echo showOtherLangText('Please approve one more time the issue out'); ?></p>
                        <input type="hidden" name="orderId" class="issueOutOrdId" value="">
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo showOtherLangText('Back'); ?></button>
                        <button type="button"
                            class="approveBtn btn btn-primary"><?php echo showOtherLangText('Issue Out'); ?></button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <form action="runningOrders.php?confirm=2&orderId=<?php echo $_SESSION['errorQtyOrderId'] ?>" method="POST"
        id="frm_issueOutPopUpFinalFrm" name="issueOutPopUpFrm">
        <div class="modal" id="errorQtyModal" tabindex="-1" aria-labelledby="issueout2label" aria-hidden="true">
            <div class="modal-dialog  modal-550 modal-dialog-centered" style="align-items:center !important;">


                <?php
                if (isset($_SESSION['errorQty']) && $_SESSION['errorQty'] == 1) {
                    checkStockQtyRequisition($_SESSION['errorQtyOrderId'], $_SESSION['accountId']);
                }
                ?>

            </div>
        </div>

    </form>

    <div class="modal" tabindex="-1" id="order_details" aria-labelledby="orderdetails" aria-hidden="true">
        <div class="modal-dialog  modal-md site-modal">
            <div id="order_details_supplier" class="modal-content overflow-hidden p-2">

            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="delete-popup" aria-labelledby="add-DepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1 mt-3"><?php echo showOtherLangText('Are you sure to delete this record?') ?>
                    </h1>
                </div>

                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="button" data-bs-dismiss="modal"
                            class="btn btn-primary std-btn"><?php echo showOtherLangText('No'); ?></button>
                    </div>
                    <div class="btnBg">
                        <button type="button" onclick=""
                            class="deletelink btn btn-primary std-btn"><?php echo showOtherLangText('Yes'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    // function getDelNumb(canId, ordType) {

    //     $("#dialog").dialog({
    //         autoOpen: false,
    //         modal: true,
    //         //title     : "Title",
    //         buttons: {
    //             '<?php echo showOtherLangText('Yes') ?>': function() {
    //                 //Do whatever you want to do when Yes clicked
    //                 $(this).dialog('close');
    //                 window.location.href = 'runningOrders.php?canId=' + canId + '&type=' + ordType;
    //             },

    //             '<?php echo showOtherLangText('No') ?>': function() {
    //                 //Do whatever you want to do when No clicked
    //                 $(this).dialog('close');
    //             }
    //         }
    //     });

    //     $("#dialog").dialog("open");
    //     $('.custom-header-text').remove();
    //     $('.ui-dialog-content').prepend(
    //         '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
    // }

    function show_popup_issue_out() {

        $("#dialog").dialog({
            autoOpen: false,
            modal: true,
            //title     : "Title",
            buttons: {
                '<?php echo showOtherLangText('Yes') ?>': function() {
                    //Do whatever you want to do when Yes clicked
                    $(this).dialog('close');
                    window.location.href = 'runningOrders.php?canId=' + canId + '&type=' + ordType;
                },

                '<?php echo showOtherLangText('No') ?>': function() {
                    //Do whatever you want to do when No clicked
                    $(this).dialog('close');
                }
            }
        });

        $("#dialog").dialog("open");
        $('.custom-header-text').remove();
        $('.ui-dialog-content').prepend(
            '<div class="custom-header-text"><span><?php echo showOtherLangText('Queue1.com Says') ?></span></div>');
    }

    function AssignOrder(orderType, orderId) {

        $('.orderType').val(orderType);
        $('.orderId').val(orderId);

        $.ajax({
                method: "POST",

                data: {
                    assignedOrderType: orderType,
                    assignedOrderId: orderId
                }
            })
            .done(function(htmlRes) {
                $('.mobUserList').html(htmlRes);

                $("#CheckAllOptions").on('click', function() {

                    $('.optionCheck:checkbox').not(this).prop('checked', this.checked);

                    var totalCount = $('.optionCheck').length;
                    var totalCheckedCount = $('.optionCheck:checked').length;

                    if (totalCheckedCount == 0) {
                        $('#checkedTotal').val(0);
                    } else {
                        $('#checkedTotal').val(1);
                    }
                });

                var totalCount = $('.optionCheck').length;
                var totalCheckedCount = $('.optionCheck:checked').length;

                if (totalCheckedCount == 0) {
                    $('#checkedTotal').val(0);
                } else {
                    $('#checkedTotal').val(1);
                }

                if (totalCount == totalCheckedCount) {
                    $('#CheckAllOptions').prop('checked', true);
                } else {
                    $('#CheckAllOptions').prop('checked', false);
                }

                $(".optionCheck").on('click', function() {

                    var totalCount = $('.optionCheck').length;
                    var totalCheckedCount = $('.optionCheck:checked').length;

                    if (totalCheckedCount == 0) {
                        $('#checkedTotal').val(0);
                    } else {
                        $('#checkedTotal').val(1);
                    }

                    if (totalCount == totalCheckedCount) {

                        $('#CheckAllOptions').prop('checked', true);
                    } else {
                        $('#CheckAllOptions').prop('checked', false);
                    }
                });
            });

    }



    $(document).ready(function() {
        $('.approveBtn').click(function() {
            $.ajax({
                method: "POST",
                url: "runningOrders.php",

                data: {
                    clickApproveBtn: 1
                },
                success: function(data) {
                    let orderId = $('.issueOutOrdId').val();
                    window.location = "runningOrders.php?confirm=2&orderId=" + orderId;
                },
            })

        });

        $('.submitFinalIssueOut').click(function() {
            $.ajax({
                method: "POST",
                url: "runningOrders.php",

                data: {
                    clickApproveBtn: 1
                },
                success: function(data) {
                    $('#frm_issueOutPopUpFinalFrm').submit();
                },
            })

        });

        var errorQty = '<?php echo $_SESSION['errorQty'] ?>';
        if (errorQty != '' && errorQty == 1) {
            $('#errorQtyModal').modal('show');
        }

        window.onclick = function(event) {
            console.log('event.target', event.target);
            // if (event.target == modal) {
            //     modal.style.display = "none";
            // }
            // if (event.target == errorQtyModal) {
            //     errorQtyModal.style.display = "none";

            $.ajax({
                method: "POST",
                url: "runningOrders.php",

                data: {
                    clickAnywhere: 1
                }
            })

            // if (event.target == assignModalPopup) {
            //     assignModalPopup.style.display = "none";
            // }

        }
    });

    function cnfIssueOut(orderId) {

        $('.issueOutOrdId').val(orderId);
    }

    function getDelNumb(canId, ordType) {
        var newOnClick = "window.location.href='runningOrders.php?canId=" + canId + "&type=" + ordType + "'";

        $('.deletelink').attr('onclick', newOnClick);
        $('#delete-popup').modal('show');

    }

    function openPopup(ordType, ordId) {
        if (ordType == 1) {

            showOrderJourney(ordId, ordType);
            return false;

        } else if (ordType == 2) {

            showRequisitionJourney(ordId);
            return false;

        }


    }

    function showOrderJourney(ordId, ordType, isSupOrder = 0) {
        $.ajax({
                method: "POST",
                url: "ordershare_ajax_pdf.php",

                data: {
                    orderId: ordId,
                    orderType: ordType,
                    isSupDet: isSupOrder,
                    page: 'order'
                }
            })
            .done(function(htmlRes) {
                $('#order_details_supplier').html(htmlRes);
                $('#order_details').modal('show');


                //orderAndReqJsCode();
            });



    }

    function showRequisitionJourney(ordId) {

        $.ajax({
                method: "POST",
                url: "requisitionshare_ajax_pdf.php",

                data: {
                    orderId: ordId
                }
            })
            .done(function(htmlRes) {
                $('#order_details_supplier').html(htmlRes);
                $('#order_details').modal('show');

                //orderAndReqJsCode();
            });





    } //end requisition journey function

    function hideCheckbox(targetId) {

        if ($('#' + targetId).is(":visible")) {
            $('#' + targetId).css('display', 'none');
        } else {
            $('#' + targetId).css('display', 'block');
        }
    }

    $('body').on('change', '.headCheckbox', function() {
        if ($(".headCheckbox").length == $(".headCheckbox:checked").length)
            $(".headChk-All").prop('checked', true);
        else
            $(".headChk-All").prop('checked', false);
    });

    $('body').on('change', '.itmTblCheckbox', function() {

        console.log('sssss', $(".itmTblCheckbox").length, $(".itmTblCheckbox:checked").length);
        if ($(".itmTblCheckbox").length == $(".itmTblCheckbox:checked").length)
            $(".itemChk-All").prop('checked', true);
        else
            $(".itemChk-All").prop('checked', false);
    });

    $('body').on('change', '.smryCheckbox', function() {

        if ($(".smryCheckbox").length == $(".smryCheckbox:checked").length)
            $(".smryChk-All").prop('checked', true);
        else
            $(".smryChk-All").prop('checked', false);
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

    function showHideByClassItems(targetId) {

        if ($('.' + targetId).is(":visible")) {
            $('.' + targetId).css('display', 'none');


        } else {
            $('.' + targetId).css('display', 'block');

            if (!$('#itemDiv').is(":visible")) {

                $('#itemDiv').css('display', 'block');
            }

        }

    }

    function showHideByClassSummary(targetId) {

        if ($('.' + targetId).is(":visible")) {
            $('.' + targetId).css('display', 'none');


        } else {


            $('.sumMainDiv').css('display', 'table-row');
            $('.' + targetId).css('display', 'block');

            if (!$('.show-smry-cls').is(":visible")) {

                $('.show-smry-cls').css('display', 'block');
            }

            if (targetId == 'smryDef_Val' || targetId == 'smryOtr_Val') {
                $('.sumBreakupAmtText').css('display', 'block');

            }


        }

    }


    function updateVisibility() {

        var otherCurId = $('#ordCurId').val();

        if (!$(".summary-default-currency").is(":checked") && (!$(".summary-second-currency").is(
                ":checked") || otherCurId == 0)) {

            $('.amountSections').css('display', 'none');
            $('.SummaryItems').css('display', 'none');

            // $('.smryTtl').css('display', 'none');

        } else {
            $('.sumBreakupAmtText').css('display', 'block');
            $('.SummaryItems').css('display', 'table-row');
        }
    }
</script>
<?php
include_once('orderAndReqJourneyJsCode.php');
?>