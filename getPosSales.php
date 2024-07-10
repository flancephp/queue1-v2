<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title> Revenue Centers - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <style>
        .outlet__box { width: 20%; }
        .middle__info { width: 50%; }
        .right__box { width: 30%; }
        .middle__info .sales__box { width: 25%; }
        .middle__info .product__box { width: 50%; }
        .middle__info .cate__box { width: 25%; }
        @media (min-width: 992px) {
            .sales__table .outlet__box { width: 15%; }
            .sales__table .middle__info { width: 50%; }
            .sales__table .right__box { width: 35%; }
            .sales__table .middle__info .sales__box { width: 20%; }
            .sales__table .middle__info .product__box { width: 50%; }
            .sales__table .middle__info .cate__box { width: 30%; }

            .category__table .outlet__box { width: 50%; }
            .category__table .middle__info { width: 25%; }
            .category__table .right__box { width: 25%; } 
        }
        @media (max-width: 991px) {
            .outlet__box { width: 25%; }
            .middle__info { width: 75%; }
            .middle__info .sales__box { width: 25%; }
            .middle__info .product__box { width: 45%; }
            .middle__info .cate__box { width: 30%; }
            .right__box { width: 100%; }
            .res__label__item::before {
                content: attr(data-text);display: block;font-size: 9px;color: #777;line-height: 1.4;
            }
            .category__table .outlet__box { width: 50%; }
            .category__table .middle__info { width: 50%; }
        } 
        @media (max-width: 767px) {
            .outlet__box { width: 50%; }
            .middle__info { width: 50%;position: relative;min-height: 64px; }
            .middle__info .sales__box { width: 100%; }
            .middle__info .product__box { width: 100%;position: absolute;bottom: -4px;left: -100%; }
            .middle__info .cate__box { width: 100%;position: absolute;bottom:-4px;left: 0%; } 
            .category__table .middle__info { min-height: auto; }
        } 
      
    </style>
</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row g-0">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <nav class="navbar d-flex flex-wrap align-items-stretch">
                    <div>
                        <div class="logo">
                            <img src="Assets/icons/logo_Q.svg" alt="Logo" class="lg-Img">
                            <div class="clsBar" id="clsBar">
                                <a href="javascript:void(0)"><i class="fa-solid fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="nav-bar">
                            <ul class="nav flex-column h2">
                                <li class="nav-item dropdown dropend">
                                    <a class="nav-link text-center dropdown-toggle" aria-current="page" href="index.php"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="Assets/icons/new_task.svg" alt="Task" class="navIcon">
                                        <img src="Assets/icons/new_task_hv.svg" alt="Task" class="mb_navIcn">
                                        <p>New Task</p>
                                    </a>
                                    <ul class="dropdown-menu nwSub-Menu" aria-labelledby="navbarDropdown">
                                        <li><a class="nav-link nav_sub" aria-current="page" href="index.php">
                                                <img src="Assets/icons/new_order.svg" alt="New order"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_order_hv.svg" alt="New order"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Order</span>
                                            </a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="newRequisition.php">
                                                <img src="Assets/icons/new_req.svg" alt="Req"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_req_hv.svg" alt="Req"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Requisition</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_stock.svg" alt="Stock"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_stock_hv.svg" alt="Stock"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Stocktake</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_prod.svg" alt="Product"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_prod_hv.svg" alt="Product"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Production</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_payment.svg" alt="Payment"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_payment_hv.svg" alt="Payment"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Payment</span></a>
                                        </li>
                                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                                <img src="Assets/icons/new_invoice.svg" alt="Invoice"
                                                    class="navIcon align-middle">
                                                <img src="Assets/icons/new_invoice_hv.svg" alt="Invoice"
                                                    class="mb_nvSubIcn align-middle">
                                                <span class="align-middle">New Invoice</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="runningTask.php">
                                        <img src="Assets/icons/run_task.svg" alt="Run Task" class="navIcon">
                                        <img src="Assets/icons/run_task_hv.svg" alt="Run Task"
                                            class="navIcon mb_navIcn">
                                        <p>Running Tasks</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="history.php">
                                        <img src="Assets/icons/office.svg" alt="office" class="navIcon">
                                        <img src="Assets/icons/office_hv.svg" alt="office" class="mb_navIcn">
                                        <p>Office</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="stockView.php">
                                        <img src="Assets/icons/storage.svg" alt="storage" class="navIcon">
                                        <img src="Assets/icons/storage_hv.svg" alt="storage" class="mb_navIcn">
                                        <p>Storage</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active text-center" href="revenueCenter.php">
                                        <img src="Assets/icons/revenue_center.svg" alt="Revenue" class="navIcon">
                                        <img src="Assets/icons/revenue_center_hv.svg" alt="Revenue" class="mb_navIcn">
                                        <p>Revenue Centers</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-bar lgOut">
                        <ul class="nav flex-column h2">
                            <li class="nav-item">
                                <a class="nav-link text-center" href="setup.php">
                                    <img src="Assets/icons/setup.svg" alt="setup" class="navIcon">
                                    <img src="Assets/icons/setup_hv.svg" alt="setup" class="mb_navIcn">
                                    <p>Setup</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-center" href="javascript:void(0)">
                                    <img src="Assets/icons/logout.svg" alt="logout" class="navIcon">
                                    <img src="Assets/icons/logout_hv.svg" alt="logout" class="mb_navIcn">
                                    <p>Log Out</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">Get POS Sales</h1>
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
                                    <h1 class="h1">Revenue Centers</h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> Account</span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 1</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 2</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 3</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 4</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
                            <div class="row g-3 pt-2">
                                <div class="col-md-6">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div> 
                                        </div>
                                        <!-- Date Box Start -->
                                        <div class="prtDate">
                                            <div class="hstDate">
                                                <input type="text" size="10" class="datepicker" placeholder="15/01/2023"
                                                    name="fromDate" autocomplete="off" value="">
                                                <span>-</span>
                                                <input type="text" size="10" class="datepicker" placeholder="15/02/2023"
                                                    name="fromDate" autocomplete="off" value="">
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-arrows-rotate"></i></a>
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                            </div>
                                        </div>
                                        <!-- Date Box End -->
                                    </div>

                                </div>
                                <div class="col-md-6 d-flex justify-content-end align-items-end">
                                    <a href="revenueCenter.php" class="sub-btn std-btn update w-lg-100" style="max-width: 140px;">Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->


                        <div class="data__table">
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
                                <div class="newReqTask">
                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                            <p>Outlet Name 1</p>
                                        </div>
                                        <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                2,35050
                                            </div>
                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                Product Name
                                            </div>
                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                Category Name
                                            </div> 
                                        </div>  
                                        <div class="right__box prdt-Hide">
                                            <div class="d-flex">
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sale barcode'); ?>"> 
                                                    <p>BARCODE 10001467</p>
                                                </div>
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Quantity'); ?>"> 
                                                    <p>345</p>
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
                                <div class="newReqTask">
                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                            <p>Outlet Name 1</p>
                                        </div>
                                        <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                2,35050
                                            </div>
                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                Product Name
                                            </div>
                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                Category Name
                                            </div> 
                                        </div>  
                                        <div class="right__box prdt-Hide">
                                            <div class="d-flex">
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sale barcode'); ?>"> 
                                                    <p>BARCODE 10001467</p>
                                                </div>
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Quantity'); ?>"> 
                                                    <p>345</p>
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
                                <div class="newReqTask">
                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                            <p>Outlet Name 1</p>
                                        </div>
                                        <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                2,35050
                                            </div>
                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                Product Name
                                            </div>
                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                Category Name
                                            </div> 
                                        </div>  
                                        <div class="right__box prdt-Hide">
                                            <div class="d-flex">
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sale barcode'); ?>"> 
                                                    <p>BARCODE 10001467</p>
                                                </div>
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Quantity'); ?>"> 
                                                    <p>345</p>
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
                                <div class="newReqTask">
                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                            <p>Outlet Name 1</p>
                                        </div>
                                        <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                2,35050
                                            </div>
                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                Product Name
                                            </div>
                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                Category Name
                                            </div> 
                                        </div>  
                                        <div class="right__box prdt-Hide">
                                            <div class="d-flex">
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sale barcode'); ?>"> 
                                                    <p>BARCODE 10001467</p>
                                                </div>
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Quantity'); ?>"> 
                                                    <p>345</p>
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
                                <div class="newReqTask">
                                    <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                            <p>Outlet Name 1</p>
                                        </div>
                                        <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                            <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                2,35050
                                            </div>
                                            <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                Product Name
                                            </div>
                                            <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                Category Name
                                            </div> 
                                        </div>  
                                        <div class="right__box prdt-Hide">
                                            <div class="d-flex">
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sale barcode'); ?>"> 
                                                    <p>BARCODE 10001467</p>
                                                </div>
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Quantity'); ?>"> 
                                                    <p>345</p>
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
                                <!-- Item Table Body End -->
                            </div>
                        </div>

                        </div><!--.//data__table-->

                        <div class="data__table mt-4">
                            <div class="container nordPrice d-block">
                                <h2 class="fs-6 fw-bold px-2 px-lg-0">Sales Hero</h2>
                            </div>
                            <div class="container nordPrice position-relative">
                                <!-- Item Table Head Start -->
                                <div class="d-flex flex-wrap flex-row align-items-center itmTable sales__table">
                                    <div class="outlet__box tb-head fw-bold">
                                        <p><?php echo showOtherLangText('Order'); ?></p>
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
                                            <p><?php echo showOtherLangText('Sub Total'); ?></p>
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
                                    <div class="newReqTask">
                                        <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt sales__table">
                                            <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                <p>Outlet Name 1</p>
                                            </div>
                                            <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                                <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                    2,35050
                                                </div>
                                                <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                    Product Name
                                                </div>
                                                <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                    Category Name
                                                </div> 
                                            </div>  
                                            <div class="right__box prdt-Hide">
                                                <div class="d-flex">
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sub Total'); ?>"> 
                                                        <p>456</p>
                                                    </div>
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Text'); ?>"> 
                                                        <p>11</p>
                                                    </div>
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Value'); ?>"> 
                                                        <p>34500 $</p>
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
                                    <div class="newReqTask">
                                        <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt sales__table">
                                            <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                <p>Outlet Name 1</p>
                                            </div>
                                            <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                                <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                    2,35050
                                                </div>
                                                <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                    Product Name
                                                </div>
                                                <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                    Category Name
                                                </div> 
                                            </div>  
                                            <div class="right__box prdt-Hide">
                                                <div class="d-flex">
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sub Total'); ?>"> 
                                                        <p>456</p>
                                                    </div>
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Text'); ?>"> 
                                                        <p>11</p>
                                                    </div>
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Value'); ?>"> 
                                                        <p>34500 $</p>
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
                                    <div class="newReqTask">
                                        <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt sales__table">
                                            <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                <p>Outlet Name 1</p>
                                            </div>
                                            <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                                <div class="sales__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sales'); ?>">
                                                    2,35050
                                                </div>
                                                <div class="product__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Product'); ?>">
                                                    Product Name
                                                </div>
                                                <div class="cate__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Category'); ?>"> 
                                                    Category Name
                                                </div> 
                                            </div>  
                                            <div class="right__box prdt-Hide">
                                                <div class="d-flex">
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Sub Total'); ?>"> 
                                                        <p>456</p>
                                                    </div>
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Text'); ?>"> 
                                                        <p>11</p>
                                                    </div>
                                                    <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Value'); ?>"> 
                                                        <p>34500 $</p>
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
                                    <div class="newReqTask">
                                        <div class="d-flex flex-wrap flex-row align-items-start align-items-md-center border-bottom itmBody newOrd-CntPrt category__table">
                                            <div class="outlet__box tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Outlet'); ?>">
                                                <p>Category Name</p>
                                            </div>
                                            <div class="middle__info d-flex align-items-start align-items-md-center"> 
                                                <div class="tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Count'); ?>">
                                                    33
                                                </div>
                                                 
                                            </div>  
                                            <div class="right__box prdt-Hide">
                                                <div class="col tb-bdy res__label__item" data-text="<?php echo showOtherLangText('Values'); ?>"> 
                                                    <p>14500000 $</p>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="mbLnk-Order">
                                            <a href="javascript:void(0)" class="orderLink">
                                                <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                        </div> 
                                    </div><!--.//newReqTask-->
                                     
                                     
                                    <!-- Item Table Body End -->
                                </div>
                            </div><!--.//boxscroll-->

                        </div><!--.//data__table-->

                       
                    </section><!--.//ordDetail-->

                    

                </section><!--hisParent-sec-->

            </div>
        </div>
    </div>

 
    
   

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>