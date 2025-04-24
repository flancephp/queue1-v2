<script>
    $(document).ready(function() {


        var designationType = '<?php echo $designationType; ?>';
        if (designationType == 1) {
            $("#type_mobile").click();
            if ($('#mobile-section').attr('checked')) {
                var totalCount = $('.mobile-section-enable').length;
                var totalEnableCheckedCount = $('.mobile-section-enable:checked').length;
                var totalDisableCheckedCount = $('.mobile-section-disable:checked').length;


                if (totalCount == totalEnableCheckedCount) {
                    $('#checkallMobileSection').prop('checked', true);
                } else {
                    $('#checkallMobileSection').prop('checked', false);
                }

                if (totalCount == totalDisableCheckedCount) {

                    $('#uncheckallMobileSection').prop('checked', true);
                } else {
                    $('#uncheckallMobileSection').prop('checked', false);
                }

                $('.mobile-section-enable, .mobile-section-disable').click(function() {

                    var totalCount = $('.mobile-section-enable').length;

                    var totalEnableCheckedCount = $('.mobile-section-enable:checked').length;

                    var totalDisableCheckedCount = $('.mobile-section-disable:checked').length;

                    if (totalCount == totalEnableCheckedCount) {

                        $('#checkallMobileSection').prop('checked', true);
                    } else {
                        $('#checkallMobileSection').prop('checked', false);
                    }

                    if (totalCount == totalDisableCheckedCount) {

                        $('#uncheckallMobileSection').prop('checked', true);
                    } else {
                        $('#uncheckallMobileSection').prop('checked', false);
                    }

                });
            }
        } else {
            $("#type_web").click();
        }

        //new order sections starts


        $("#supplierCheckall").on('click', function() {


            if ($("#supplierCheckall").prop('checked') == true) {
                $('.supplierCheckbox').prop('checked', true);
                $('#new-order-section').prop('checked', true);

            } else {
                $('.supplierCheckbox').prop('checked', false);
                $('#new-order-section').prop('checked', false);
            }

        });

        var totalCount = $('.supplierCheckbox').length;

        var totalCheckedCount = $('.supplierCheckbox:checked').length;


        if (totalCount == totalCheckedCount) {
            console.log('totalCount', totalCount, totalCheckedCount);
            $('#supplierCheckall').prop('checked', true);
        } else {
            $('#supplierCheckall').prop('checked', false);
        }

        $('.supplierCheckbox').click(function() {


            if ($("#supplierCheckbox").prop('checked') == true) {
                $('#new-order-section').prop('checked', true);

            } else {
                $('#new-order-section').prop('checked', false);
            }


            var totalCount = $('.supplierCheckbox').length;

            var totalCheckedCount = $('.supplierCheckbox:checked').length;

            if (totalCount == totalCheckedCount) {

                $('#supplierCheckall').prop('checked', true);
            } else {
                $('#supplierCheckall').prop('checked', false);
            }

        });

        //check whether enable/disable is checked or not
        var totalCount = $('.order-enable').length;

        var totalEnableCheckedCount = $('.order-enable:checked').length;

        var totalDisableCheckedCount = $('.order-disable:checked').length;


        if (totalCount == totalEnableCheckedCount) {

            $('#checkallOrder').prop('checked', true);
        } else {
            $('#checkallOrder').prop('checked', false);
        }

        if (totalCount == totalDisableCheckedCount) {
            $('#uncheckallOrder').prop('checked', true);
        } else {
            $('#uncheckallOrder').prop('checked', false);
        }

        $('.order-enable, .order-disable').click(function() {

            var totalCount = $('.order-enable').length;

            var totalEnableCheckedCount = $('.order-enable:checked').length;

            var totalDisableCheckedCount = $('.order-disable:checked').length;

            if (totalCount == totalEnableCheckedCount) {
                $('#checkallOrder').prop('checked', true);
            } else {
                $('#checkallOrder').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {
                $('#uncheckallOrder').prop('checked', true);
            } else {
                $('#uncheckallOrder').prop('checked', false);
            }

        });
        //end order sections

        //new requisition starts here


        ///$('#show-requisition-detail').css('display', 'block');
        $('#show-requisition-detail').addClass('show');

        $("#memberCheckall").on('click', function() {

            $('.requisitionCheckbox:checkbox').not(this).prop('checked', this.checked);

            if ($("#memberCheckall").prop('checked') == true) {

                $('#new-requisition-section').prop('checked', true);
            } else {
                $('#new-requisition-section').prop('checked', false);
            }
        });

        var totalCount = $('.requisitionCheckbox').length;

        var totalCheckedCount = $('.requisitionCheckbox:checked').length;


        if (totalCount == totalCheckedCount) {

            $('#memberCheckall').prop('checked', true);
        } else {
            $('#memberCheckall').prop('checked', false);
        }

        $('.requisitionCheckbox').click(function() {


            if ($(".requisitionCheckbox").prop('checked') == true) {

                $('#new-requisition-section').prop('checked', true);
            } else {
                $('#new-requisition-section').prop('checked', false);
            }

            var totalCount = $('.requisitionCheckbox').length;

            var totalCheckedCount = $('.requisitionCheckbox:checked').length;

            if (totalCount == totalCheckedCount) {

                $('#memberCheckall').prop('checked', true);
            } else {
                $('#memberCheckall').prop('checked', false);
            }

        });

        //check whether enable/disable is checked or not
        var totalCount = $('.requisition-enable').length;

        var totalEnableCheckedCount = $('.requisition-enable:checked').length;

        var totalDisableCheckedCount = $('.requisition-disable:checked').length;


        if (totalCount == totalEnableCheckedCount) {

            $('#checkallRequisition').prop('checked', true);
        } else {
            $('#checkallRequisition').prop('checked', false);
        }

        if (totalCount == totalDisableCheckedCount) {
            $('#uncheckallRequisition').prop('checked', true);
        } else {
            $('#uncheckallRequisition').prop('checked', false);
        }

        $('.requisition-enable, .requisition-disable').click(function() {

            var totalCount = $('.requisition-enable').length;

            var totalEnableCheckedCount = $('.requisition-enable:checked').length;

            var totalDisableCheckedCount = $('.requisition-disable:checked').length;

            if (totalCount == totalEnableCheckedCount) {
                $('#checkallRequisition').prop('checked', true);
            } else {
                $('#checkallRequisition').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {
                $('#uncheckallRequisition').prop('checked', true);
            } else {
                $('#uncheckallRequisition').prop('checked', false);
            }

        });
        //end new requisition


        $("#checkallRequisition").on('click', function() {

            if ($("#uncheckallRequisition").is(':checked')) {
                $('#uncheckallRequisition').prop('checked', false);
            }

            $('.enableRequisition input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallRequisition").on('click', function() {

            if ($("#checkallRequisition").is(':checked')) {
                $('#checkallRequisition').prop('checked', false);
            }

            $('.desableRequisition input:radio').not(this).prop('checked', this.checked);
        });


        //Running task section
        if ($('#runningTask-section').attr('checked')) {

            $('#show-runningTask-detail').addClass("show");

            var totalCount = $('.runningtask-enable').length;

            var totalEnableCheckedCount = $('.runningtask-enable:checked').length;

            var totalDisableCheckedCount = $('.runningtask-disable:checked').length;


            if (totalCount == totalEnableCheckedCount) {

                $('#checkallRunningtask').prop('checked', true);
            } else {
                $('#checkallRunningtask').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {
                $('#uncheckallRunningtask').prop('checked', true);
            } else {
                $('#uncheckallRunningtask').prop('checked', false);
            }

            $('.runningtask-enable, .runningtask-disable').click(function() {


                if ($(".runningtask-enable").is(':checked') || $(".runningtask-disable").is(':checked')) {
                    $('#runningTask-section').prop('checked', true);
                }

                var totalCount = $('.runningtask-enable').length;

                var totalEnableCheckedCount = $('.runningtask-enable:checked').length;

                var totalDisableCheckedCount = $('.runningtask-disable:checked').length;

                if (totalCount == totalEnableCheckedCount) {
                    $('#checkallRunningtask').prop('checked', true);
                } else {
                    $('#checkallRunningtask').prop('checked', false);
                }

                if (totalCount == totalDisableCheckedCount) {
                    $('#uncheckallRunningtask').prop('checked', true);
                } else {
                    $('#uncheckallRunningtask').prop('checked', false);
                }

            });
        }

        // History section
        if ($('#history-section').attr('checked')) {

            $('#show-history-detail').addClass('show');

            var totalCount = $('.history-enable').length;

            var totalEnableCheckedCount = $('.history-enable:checked').length;

            var totalDisableCheckedCount = $('.history-disable:checked').length;


            if (totalCount == totalEnableCheckedCount) {

                $('#checkallHistory').prop('checked', true);
            } else {
                $('#checkallHistory').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {

                $('#uncheckallHistory').prop('checked', true);
            } else {
                $('#uncheckallHistory').prop('checked', false);
            }

            $('.history-enable, .history-disable').click(function() {


                if ($(".history-enable").is(':checked') || $(".history-disable").is(':checked')) {
                    $('#history-section').prop('checked', true);
                }


                var totalCount = $('.history-enable').length;

                var totalEnableCheckedCount = $('.history-enable:checked').length;

                var totalDisableCheckedCount = $('.history-disable:checked').length;

                if (totalCount == totalEnableCheckedCount) {

                    $('#checkallHistory').prop('checked', true);
                } else {
                    $('#checkallHistory').prop('checked', false);
                }

                if (totalCount == totalDisableCheckedCount) {

                    $('#uncheckallHistory').prop('checked', true);
                } else {
                    $('#uncheckallHistory').prop('checked', false);
                }

            });
        }

        //History check all/uncheck all
        $("#checkallHistory").on('click', function() {

            if ($("#uncheckallHistory").is(':checked')) {
                $('#uncheckallHistory').prop('checked', false);
            }

            if ($("#checkallHistory").is(':checked')) {
                $('#history-section').prop('checked', true);
            } else {
                $('#history-section').prop('checked', false);
            }

            $('.enableHistory input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallHistory").on('click', function() {

            if ($("#checkallHistory").is(':checked')) {
                $('#checkallHistory').prop('checked', false);
            }

            if ($("#uncheckallHistory").is(':checked')) {
                $('#history-section').prop('checked', true);
            } else {
                $('#history-section').prop('checked', false);
            }

            $('.desableHistory input:radio').not(this).prop('checked', this.checked);
        });


        //do this if any stock item checked
        $(".stock-section").on('click', function() {

            if ($(".stock-section").is(':checked')) {
                $('#stock-section').prop('checked', true);
            } else {
                $('#stock-section').prop('checked', false);
            }

            //check if all its checkboxes are unchecked then uncheck checkall check box also
            var totalCount = $('.stock-section').length;

            var totalCheckedCount = $('.stock-section:checked').length;

            if (totalCount == totalCheckedCount) {

                $('#storeCheckAll').prop('checked', true);
            } else {
                $('#storeCheckAll').prop('checked', false);
            }

        });

        //do this if check all or uncheck all
        $("#storeCheckAll").on('click', function() {

            if ($("#storeCheckAll").is(':checked')) {
                $('#stock-section').prop('checked', true);
                $('.stock-section').prop('checked', true);

            } else {
                $('#stock-section').prop('checked', false);
                $('.stock-section').prop('checked', false);
            }
        });

        //stock old code-------------------------------------------------
        // Stock section
        if ($('#stock-section').attr('checked')) {

            $('#show-stock-detail').addClass('show');

            var totalCount = $('.stock-enable').length;

            var totalEnableCheckedCount = $('.stock-enable:checked').length;

            var totalDisableCheckedCount = $('.stock-disable:checked').length;


            if (totalCount == totalEnableCheckedCount) {

                $('#checkallStock').prop('checked', true);
            } else {
                $('#checkallStock').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {

                $('#uncheckallStock').prop('checked', true);
            } else {
                $('#uncheckallStock').prop('checked', false);
            }


        }

        //stock radio options
        $('.stock-enable, .stock-disable').click(function() {


            if ($(".stock-enable").is(':checked') || $(".stock-disable").is(':checked')) {
                $('#stock-section').prop('checked', true);
            }

            var totalCount = $('.stock-enable').length;

            var totalEnableCheckedCount = $('.stock-enable:checked').length;

            var totalDisableCheckedCount = $('.stock-disable:checked').length;

            if (totalCount == totalEnableCheckedCount) {

                $('#checkallStock').prop('checked', true);
            } else {
                $('#checkallStock').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {

                $('#uncheckallStock').prop('checked', true);
            } else {
                $('#uncheckallStock').prop('checked', false);
            }

        });

        //Stock check all/uncheck all
        $("#checkallStock").on('click', function() {

            if ($("#uncheckallStock").is(':checked')) {
                $('#uncheckallStock').prop('checked', false);

            }


            if ($("#checkallStock").is(':checked')) {
                $('#stock-section').prop('checked', true);
            }


            $('.enableStock input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallStock").on('click', function() {



            if ($("#uncheckallStock").is(':checked')) {
                $('#stock-section').prop('checked', true);
            }


            if ($("#checkallStock").is(':checked')) {
                $('#checkallStock').prop('checked', false);
            }

            $('.desableStock input:radio').not(this).prop('checked', this.checked);
        });





        // RevenueCenter section
        if ($('#revenueCenter-section').attr('checked')) {

            $('#show-revenueCenter-detail').addClass('show');

            var totalCount = $('.revenueCenter-enable').length;

            var totalEnableCheckedCount = $('.revenueCenter-enable:checked').length;

            var totalDisableCheckedCount = $('.revenueCenter-disable:checked').length;


            if (totalCount == totalEnableCheckedCount) {

                $('#checkallRevenueCenter').prop('checked', true);
            } else {
                $('#checkallRevenueCenter').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {

                $('#uncheckallRevenueCenter').prop('checked', true);
            } else {
                $('#uncheckallRevenueCenter').prop('checked', false);
            }

            $('.revenueCenter-enable, .revenueCenter-disable').click(function() {


                if ($(".revenueCenter-enable").is(':checked') || $(".revenueCenter-disable").is(':checked')) {
                    $('#revenueCenter-section').prop('checked', true);
                }

                var totalCount = $('.revenueCenter-enable').length;

                var totalEnableCheckedCount = $('.revenueCenter-enable:checked').length;

                var totalDisableCheckedCount = $('.revenueCenter-disable:checked').length;

                if (totalCount == totalEnableCheckedCount) {

                    $('#checkallRevenueCenter').prop('checked', true);
                } else {
                    $('#checkallRevenueCenter').prop('checked', false);
                }

                if (totalCount == totalDisableCheckedCount) {

                    $('#uncheckallRevenueCenter').prop('checked', true);
                } else {
                    $('#uncheckallRevenueCenter').prop('checked', false);
                }

            });
        }


        // setup section
        if ($('#setup-section').attr('checked')) {

            $('#show-setup-detail').addClass('show');

            var totalCount = $('.setup-enable').length;

            var totalEnableCheckedCount = $('.setup-enable:checked').length;

            var totalDisableCheckedCount = $('.setup-disable:checked').length;


            if (totalCount == totalEnableCheckedCount) {

                $('#checkallSetup').prop('checked', true);
            } else {
                $('#checkallSetup').prop('checked', false);
            }

            if (totalCount == totalDisableCheckedCount) {

                $('#uncheckallSetup').prop('checked', true);
            } else {
                $('#uncheckallSetup').prop('checked', false);
            }

            $('.setup-enable, .setup-disable').click(function() {


                if ($(".setup-enable").is(':checked') || $(".setup-disable").is(':checked')) {
                    $('#setup-section').prop('checked', true);
                }

                var totalCount = $('.setup-enable').length;

                var totalEnableCheckedCount = $('.setup-enable:checked').length;

                var totalDisableCheckedCount = $('.setup-disable:checked').length;

                if (totalCount == totalEnableCheckedCount) {

                    $('#checkallSetup').prop('checked', true);
                } else {
                    $('#checkallSetup').prop('checked', false);
                }

                if (totalCount == totalDisableCheckedCount) {

                    $('#uncheckallSetup').prop('checked', true);
                } else {
                    $('#uncheckallSetup').prop('checked', false);
                }

            });
        }

        //Setup check all/uncheck all
        $("#checkallSetup").on('click', function() {

            if ($("#uncheckallSetup").is(':checked')) {
                $('#uncheckallSetup').prop('checked', false);
            }

            if ($("#checkallSetup").is(':checked')) {
                $('#setup-section').prop('checked', true);
            } else {
                $('#setup-section').prop('checked', false);
            }

            $('.enableSetup input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallSetup").on('click', function() {

            if ($("#checkallSetup").is(':checked')) {
                $('#checkallSetup').prop('checked', false);
            }

            if ($("#uncheckallSetup").is(':checked')) {
                $('#setup-section').prop('checked', true);
            } else {
                $('#setup-section').prop('checked', false);
            }

            $('.desableSetup input:radio').not(this).prop('checked', this.checked);
        });

        $("#checkallRevenueCenter").on('click', function() {

            if ($("#uncheckallRevenueCenter").is(':checked')) {
                $('#uncheckallRevenueCenter').prop('checked', false);
            }

            if ($("#checkallRevenueCenter").is(':checked')) {
                $('#revenueCenter-section').prop('checked', true);
            } else {
                $('#revenueCenter-section').prop('checked', false);
            }

            $('.enableRevenueCenter input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallRevenueCenter").on('click', function() {

            if ($("#checkallRevenueCenter").is(':checked')) {
                $('#checkallRevenueCenter').prop('checked', false);
            }

            if ($("#uncheckallRevenueCenter").is(':checked')) {
                $('#revenueCenter-section').prop('checked', true);
            } else {
                $('#revenueCenter-section').prop('checked', false);
            }

            $('.desableRevenueCenter input:radio').not(this).prop('checked', this.checked);
        });



        // Order check all/ uncheck all
        $("#checkallOrder").on('click', function() {

            if ($("#uncheckallOrder").is(':checked')) {
                $('#uncheckallOrder').prop('checked', false);
            }

            $('.enableOrder input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallOrder").on('click', function() {

            if ($("#checkallOrder").is(':checked')) {
                $('#checkallOrder').prop('checked', false);
            }

            $('.desableOrder input:radio').not(this).prop('checked', this.checked);
        });

        // RunningTask check all/ uncheck all
        $("#checkallRunningtask").on('click', function() {

            if ($("#uncheckallRunningtask").is(':checked')) {
                $('#uncheckallRunningtask').prop('checked', false);
                $('#runningTask-section').prop('checked', false);
            } else {
                $('#runningTask-section').prop('checked', true);
            }

            if ($("#checkallRunningtask").is(':checked')) {
                $('#runningTask-section').prop('checked', true);
            } else {
                $('#runningTask-section').prop('checked', false);
            }

            $('.enableRunningtask input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallRunningtask").on('click', function() {

            if ($("#checkallRunningtask").is(':checked')) {
                $('#checkallRunningtask').prop('checked', false);
            }

            if (!$("#uncheckallRunningtask").is(':checked')) {
                $('#runningTask-section').prop('checked', false);
            }

            $('.desableRunningtask input:radio').not(this).prop('checked', this.checked);
        });

        $("#checkallMobileSection").on('click', function() {

            if ($("#uncheckallMobileSection").is(':checked')) {
                $('#uncheckallMobileSection').prop('checked', false);
            }

            $('.enableMobileSection input:radio').not(this).prop('checked', this.checked);
        });

        $("#uncheckallMobileSection").on('click', function() {

            if ($("#checkallMobileSection").is(':checked')) {

                $('#checkallMobileSection').prop('checked', false);
            }

            $('.desableMobileSection input:radio').not(this).prop('checked', this.checked);
        });

    });
</script>