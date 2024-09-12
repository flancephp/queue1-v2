<?php
include('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    header('location: login.php');
    die;
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (isset($_GET['hotelId'])) {
    $merchantId = $hotelsArr[$_GET['hotelId']];

    $date = isset($_GET['date']) ? date('Y-m-d', strtotime($_GET['date'])) : '2021-11-10';
    $postData['Date'] = $date;
    $easyData = getPosData($merchantId, $postData);
    $easyData  = json_decode($easyData, true);
    if ($easyData['status'] == 'success') {
        $easyDataArr = $easyData['data'];
    }
}

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ? 'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title> Revenue Centers - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>

    <style>
        .outlet__box {
            width: 20%;
        }

        .middle__info {
            width: 50%;
        }

        .right__box {
            width: 30%;
        }

        .middle__info .sales__box {
            width: 25%;
        }

        .middle__info .product__box {
            width: 50%;
        }

        .middle__info .cate__box {
            width: 25%;
        }

        @media (min-width: 992px) {
            .sales__table .outlet__box {
                width: 15%;
            }

            .sales__table .middle__info {
                width: 50%;
            }

            .sales__table .right__box {
                width: 35%;
            }

            .sales__table .middle__info .sales__box {
                width: 20%;
            }

            .sales__table .middle__info .product__box {
                width: 50%;
            }

            .sales__table .middle__info .cate__box {
                width: 30%;
            }

            .category__table .outlet__box {
                width: 50%;
            }

            .category__table .middle__info {
                width: 25%;
            }

            .category__table .right__box {
                width: 25%;
            }
        }

        @media (max-width: 991px) {
            .outlet__box {
                width: 25%;
            }

            .middle__info {
                width: 75%;
            }

            .middle__info .sales__box {
                width: 25%;
            }

            .middle__info .product__box {
                width: 45%;
            }

            .middle__info .cate__box {
                width: 30%;
            }

            .right__box {
                width: 100%;
            }

            .res__label__item::before {
                content: attr(data-text);
                display: block;
                font-size: 9px;
                color: #777;
                line-height: 1.4;
            }

            .category__table .outlet__box {
                width: 50%;
            }

            .category__table .middle__info {
                width: 50%;
            }
        }

        @media (max-width: 767px) {

            .right__box {
    width: 100%;
    margin-top: 4px;
    .outlet__box{margin-bottom: 9px;}
  }
            .outlet__box {
                width: 50%;
            }

            .middle__info {
                width: 50%;
                position: relative;
                min-height: 64px;
            }

            .middle__info .sales__box {
                width: 100%;
            }

            .middle__info .product__box {
    width: 100%;
    position: absolute;
    bottom: -5px;
    left: -100%;
  }

            .middle__info .cate__box {
                width: 100%;
                position: absolute;
                bottom: -4px;
                left: 0%;
            }

            .category__table .middle__info {
                min-height: auto;
            }
        }

        @media (max-width: 420px) {
            .middle__info .product__box {
    width: 100%;
    position: absolute;
    bottom: -28px;
    left: -100%;
  }
  .itmBody > div:first-child p {
    font-size: 13px;
  }
  .right__box {
    width: 100%;
    margin-top: 20px;
    position: relative;
    bottom: -10px;
  }
  .newOrd-CntPrt {
    border-bottom: none !important;
    width: 90%;
    padding: 10px 10px 35px 10px;
    flex-wrap: wrap;
  }
  .res__label__item p {
    font-size: 13px;
    font-weight: 400;
  }

        }
    </style>
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
                            <h1 class="h1">Get POS Sales</h1>
                        </div>
                          <?php require_once('header.php'); ?>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="hisParent-sec revnueParent-sec">
                    <section class="ordDetail hisTory revCenter pb-5">

                        <!-- <div class="alrtMessage">
                            <div class="container">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <p><strong>Hello User!</strong> Ezee sales data imported successfully.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>

                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <p><strong>Hello User!</strong> error while importing data.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div> -->

                        <div class="container hisData">
                            <div class="d-flex justify-content-between flex-wrap align-items-center g-3 gap-3 pt-2">
                                <div class="">
                                <form name="frm" id="frm" method="get" action="">
                                    <div class="hstCal gap-1" style=" flex-wrap:wrap;">


                                    
                                        <div style="width: 250px;" class="me-2">
                                            


                                            <?php if($_SESSION['accountId'] == 1 || $_SESSION['accountId'] == 4){?>
                                                <select name="hotelId" id="hotelId" class="form-select" style=" background-size: 38px 38px; height:38px; padding: 0.375rem 3.25rem 0.375rem 0.75rem;" aria-label="Default select example">

    												<option value="21866" <?php echo $_GET['hotelId'] == 21866 ? 'selected="selected"' : '';?>>Fun Beach Hotel(21866)</option>
    												<option value="21930" <?php echo $_GET['hotelId'] == 21930 ? 'selected="selected"' : '';?>>Casa Del Mar Hotel(21930)</option>
    											</select>
										<?php }elseif($_SESSION['accountId'] == 3){?>	
												
                                            <select name="hotelId" id="hotelId" class="form-select" style=" background-size: 38px 38px; height:38px; padding: 0.375rem 3.25rem 0.375rem 0.75rem;" aria-label="Default select example">

    												<option value="29624" <?php echo $_GET['hotelId'] == 29624 ? 'selected="selected"' : '';?>>Mnarani Beach Hotel(29624)</option>
    											</select>
										<?php } ?>

                                        </div>



                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Date Box Start -->
                                        <div class="prtDate me-2">
                                            <div class="hstDate" style="min-height: 38px;">

                                            
                                                <input type="text" size="10" class="datepicker" placeholder="15/01/2023" name="date" id="date" autocomplete="off" value="<?php echo $date;?>">
                                               
                                            </div>
                                            <!--<div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-arrows-rotate"></i></a>
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                            </div>-->

                                        </div>

                                        <div class="d-flex justify-content-end mx-2 align-items-end">
                                            <a href="javascript:void(0);" class="btn btn-primary update w-lg-100" style="max-width: 140px;" onClick="document.frm.submit();"><?php echo showOtherLangText('Get data'); ?></a>
                                        </div>

                                        <div class=" d-flex justify-content-end mx-2 align-items-end">
                                            <a class="btn btn-primary update w-lg-100 px-2" style="max-width: 165px;" href="syncEasyData.php?hotelId=<?php echo $_GET['hotelId'];?>&date=<?php echo $_GET['date'];?>" onClick="return confirm('<?php echo showOtherLangText('Are you sure to sync the data?'); ?>');"><?php echo showOtherLangText('Sync to report'); ?></a>
                                        </div>
                                        <!-- Date Box End -->


                                    </div>

                                        </form>

                                </div>
                                <div class="d-flex justify-content-end align-items-end">
                                    <a href="revenueCenterReport.php" class="btn btn-primary update w-lg-100" style="max-width: 140px;"><?php echo showOtherLangText('Back'); ?></a>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->


                        <div class="data__table">
                            <div class="container nordPrice d-block">
                                <h2 class="fs-6 fw-bold px-2 px-lg-0"><?php echo showOtherLangText('Inventory items'); ?>

                                </h2>
                            </div>
                            <div class="container nordPrice position-relative">


                                <!-- Item Table Head Start -->
                                <div class="d-flex flex-wrap flex-row align-items-center itmTable">
                                    <div class="outlet__box tb-head fw-bold">
                                        <p><?php echo showOtherLangText('Outlet'); ?></p>
                                    </div>
                                    <div class="middle__info d-flex align-items-center">
                                        <div class="sales__box tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Sales'); ?></p>
                                        </div>
                                        <div class="product__box tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Product'); ?></p>
                                        </div>
                                        <div class="cate__box tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Category'); ?></p>
                                        </div>
                                    </div>

                                    <div class="right__box prdt-Hide d-flex">
                                        <div class="col tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Sale barcode'); ?></p>
                                        </div>
                                        <div class="col tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Quantity'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Item Table Head End -->
                            </div><!--.//container-->

                            <div id="boxscroll">

                                <div class="container cntTable">
                                    <!-- Item Table Body Start -->

                                    <?php

                                    if (is_array($easyDataArr) && !empty($easyDataArr)) {

                                        foreach ($easyDataArr as $row) {

                                            $x = 0;
                                            foreach ($row['InventoryItems'] as $itemData) {

                                                $x++;

                                    ?>
                                                <div class="newReqTask">


                                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt">
                                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                            <p><?php echo $row['Outlet']; ?></p>
                                                        </div>
                                                        <div class="middle__info d-flex align-items-start align-items-md-center">
                                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                                <?php showPrice($row['Sales'], $getDefCurDet['curCode']); ?>
                                                            </div>
                                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                                <?php echo $itemData['ItemName']; ?>
                                                            </div>
                                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>">
                                                                <?php echo $itemData['Category']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="right__box prdt-Hide">
                                                            <div class="d-flex">
                                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sale barcode'); ?>">
                                                                    <p><?php echo $itemData['ItemCode']; ?></p>
                                                                </div>
                                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Quantity'); ?>">
                                                                    <p><?php echo $itemData['Quantity']; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mbLnk-Order">
                                                        <a href="javascript:void(0)" class="orderLink">
                                                            <i class="fa-solid fa-angle-down"></i>
                                                        </a>
                                                    </div>
                                                </div><!--.//newReqTask-->
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <!-- Item Table Body End -->
                                </div>
                            </div>

                        </div><!--.//data__table-->

                        <div class="data__table mt-4">
                            <div class="container nordPrice d-block">
                                <h2 class="fs-6 fw-bold px-2 px-lg-0">Sales items
                                </h2>
                            </div>
                            <div class="container nordPrice position-relative">
                                <!-- Item Table Head Start -->
                                <div class="d-flex flex-wrap flex-row align-items-center itmTable sales__table">
                                    <div class="outlet__box tb-head fw-bold">
                                        <p><?php echo showOtherLangText('Outlet'); ?></p>
                                    </div>
                                    <div class="middle__info d-flex align-items-center">
                                        <div class="sales__box tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Sales'); ?></p>
                                        </div>
                                        <div class="product__box tb-head fw-bold">
                                            <p><?php echo showOtherLangText('name'); ?></p>
                                        </div>
                                        <div class="cate__box tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Category'); ?></p>
                                        </div>
                                    </div>

                                    <div class="right__box prdt-Hide d-flex">
                                        <div class="col tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Sale barcode'); ?></p>
                                        </div>
                                        <div class="col tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Text'); ?></p>
                                        </div>
                                        <div class="col tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Value'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Item Table Head End -->
                            </div><!--.//container-->

                            <div id="boxscroll">
                                <div class="container cntTable">

                                    <!-- Item Table Body Start -->


                                    <?php

                                    if (is_array($easyDataArr) && !empty($easyDataArr)) {

                                        foreach ($easyDataArr as $row) {

                                            $x = 0;
                                            foreach ($row['SalesItems'] as $itemData) {

                                    ?>





                                                <div class="newReqTask">
                                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt sales__table">
                                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                            <p><?php echo $row['Outlet']; ?></p>
                                                        </div>
                                                        <div class="middle__info d-flex align-items-start align-items-md-center">
                                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                                <?php showPrice($row['Sales'], $getDefCurDet['curCode']); ?>
                                                            </div>
                                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                                <?php echo $itemData['Name']; ?>
                                                            </div>
                                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>">
                                                                <?php echo $itemData['Category']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="right__box prdt-Hide">
                                                            <div class="d-flex">
                                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Salesbarcode'); ?>">
                                                                    <p><?php echo $itemData['SalesBarCode']; ?></p>
                                                                </div>
                                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Text'); ?>">
                                                                    <p><?php echo $itemData['Count']; ?></p>
                                                                </div>
                                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Value'); ?>">
                                                                    <p><?php showPrice($itemData['Value'], $getDefCurDet['curCode']); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mbLnk-Order">
                                                        <a href="javascript:void(0)" class="orderLink">
                                                            <i class="fa-solid fa-angle-down"></i>
                                                        </a>
                                                    </div>
                                                </div><!--.//newReqTask-->


                                    <?php
                                            }
                                        }
                                    }
                                    ?>







                                    <!-- Item Table Body End -->
                                </div>
                            </div><!--.//boxscroll-->

                        </div><!--.//data__table-->

                        <div class="data__table mt-4">
                            <div class="container nordPrice d-block">
                                <h2 class="fs-6 fw-bold px-2 px-lg-0">Categories</h2>
                            </div>
                            <div class="container nordPrice position-relative">
                                <!-- Item Table Head Start -->
                                <div class="d-flex flex-wrap flex-row align-items-center itmTable category__table">
                                    <div class="outlet__box tb-head fw-bold">
                                        <p><?php echo showOtherLangText('Name'); ?></p>
                                    </div>
                                    <div class="middle__info d-flex align-items-center fw-bold">
                                        <p><?php echo showOtherLangText('Count'); ?></p>
                                    </div>

                                    <div class="right__box prdt-Hide d-flex">
                                        <div class="col tb-head fw-bold">
                                            <p><?php echo showOtherLangText('Values'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Item Table Head End -->
                            </div><!--.//container-->

                            <div id="boxscroll">
                                <div class="container cntTable">
                                    <!-- Item Table Body Start -->

                                    <?php

                                    if (is_array($easyDataArr) && !empty($easyDataArr)) {

                                        foreach ($easyDataArr as $row) {

                                            $x = 0;
                                            foreach ($row['Categories'] as $catData) {


                                    ?>

                                                <div class="newReqTask">
                                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt category__table">
                                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                            <p><?php echo $catData['Name']; ?></p>
                                                        </div>
                                                        <div class="middle__info d-flex align-items-start align-items-md-center">
                                                            <div class="tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Count'); ?>">
                                                                <?php echo $catData['Count']; ?>
                                                            </div>

                                                        </div>
                                                        <div class="right__box prdt-Hide">
                                                            <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Values'); ?>">
                                                                <p><?php showPrice($catData['Value'], $getDefCurDet['curCode']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mbLnk-Order">
                                                        <a href="javascript:void(0)" class="orderLink">
                                                            <i class="fa-solid fa-angle-down"></i>
                                                        </a>
                                                    </div>
                                                </div><!--.//newReqTask-->
                                    <?php
                                            }
                                        }
                                    }
                                    ?>

                                    <!-- Item Table Body End -->
                                </div>
                            </div><!--.//boxscroll-->

                        </div><!--.//data__table-->


                    </section><!--.//ordDetail-->



                </section><!--hisParent-sec-->

            </div>
        </div>
    </div>





    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>

    <script>
		$( function() {
			$( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
		} );
    </script>

<script>  
function syncToReport(){

}  
</script> 
</body>

</html>