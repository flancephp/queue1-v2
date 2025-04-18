<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title> Sale Center - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>

<body class="mb-saleBg">

    <div class="container-fluid newOrder">
        <div class="row">
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
                            <h1 class="h1">Casa Bar</h1>
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
                                    <h1 class="h1">Casa Bar</h1>
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
                    <section class="ordDetail revCenter oltReport">
                        <div class="container hisData">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="revenueCenter.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>

                            <div class="row">
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
                                                <a href="javascript:void(0)"><i
                                                        class="fa-solid fa-arrows-rotate"></i></a>
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                            </div>
                                        </div>
                                        <!-- Date Box End -->
                                    </div>

                                </div>
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/history-stock.svg" alt="history stock">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->

                        <div class="container mb-hisDtl">
                            <div class="row">
                                <div class="col-md-5 ttl-Cstcol">
                                    <p class="tl-Cst">Total Cost</p>
                                    <div class="d-flex justify-content-center cst-Value">
                                        <div class="d-flex flex-wrap justify-content-center">
                                            <p class="prcntage-Val">360.44 $</p>
                                            <p class="cst-Prcntage">48.57%</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 var-Cstcol">
                                    <p class="sl-varDtl">Variances</p>
                                    <p class="sl-varDif">-18.3 $</p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="container mbguest-Data">

                        </div> -->

                        <div class="container detailPrice">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl saleCnt-Rpt">
                                    <div class="d-flex main-Sldiv">
                                        <div class="gstNo saleGust">
                                            <div class="guestNum d-flex">
                                                <div class="text-center">
                                                    <p class="gst-Head">No. of Guests</p>
                                                    <p class="gst-Var">25</p>
                                                </div>
                                            </div>
                                            <div class="sl-Num d-flex">
                                                <div class="text-center">
                                                    <p class="sale-Name">Sales</p>
                                                    <p class="sale-Amunt">742.0000 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ttlCost saleTtl-Cost text-center">
                                            <p class="tl-Cst">Total Cost</p>
                                            <div class="d-flex justify-content-center cst-Value">
                                                <div class="d-flex">
                                                    <p class="prcntage-Val">360.44 $</p>
                                                    <p class="cst-Prcntage">48.57%</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sale-Variance text-center saleVar-Inr">
                                            <p class="sl-varDtl">Variances</p>
                                            <p class="sl-varDif">-18.3 $</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container saleSrch-Cst">
                            <div class="row">
                                <div class="col-md-6 slCst-Srch">
                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" placeholder="Search" id="srch"
                                            aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Filter Btn Start -->
                                    <div class="dropdown slCst-fltBtn">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="Assets/icons/filter.svg" alt="Filter">
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Sales</a></li>
                                            <li><a class="dropdown-item" href="#">Bar Control</a></li>
                                            <li><a class="dropdown-item" href="#">Usage</a></li>
                                        </ul>
                                    </div>
                                    <!-- Filter Btn End -->

                                </div>
                                <div class="col-md-6 shrtBtn-Clm">
                                    <div class="shrtBtns">
                                        <a href="javascript:void(0)" class="cstBtn-Sale" data-bs-toggle="modal"
                                            data-bs-target="#adjust">
                                            <img src="Assets/icons/Setting_line.svg" alt="Adjustment"
                                                class="cstBtn-Img"> <span class="cstMb">Adjust</span>
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Sale">
                                            <img src="Assets/icons/zero.svg" alt="zero" class="cstBtn-Img">
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Sale hideBtn-Info">
                                            <img src="Assets/icons/info.svg" alt="Information" class="cstBtn-Img">
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Sale hideBtn-Prc">
                                            <span class="txtSale-Btn">$</span>
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Qty actvSale-Cst">
                                            <span class="txtSale-Btn">Qyt</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="outletBdy">
                        <div id="boxscroll">
                            <div class="container position-relative outletTbl">
                                <!-- Item Table Head Start -->
                                <div class="container outletTask">
                                    <div class="otltTbl-head">
                                        <div class="otlt-hdImg"></div>
                                        <div class="otlt-itm">
                                            <p>Item</p>
                                            <p>Totals</p>
                                        </div>
                                        <div class="flipClm-Otlt">
                                            <div class="mbStock">
                                                <div class="otlt-opnStk">
                                                    <p>Open Stock</p>
                                                    <p class="stkVal-Otlt">845.32 $</p>
                                                </div>
                                                <div class="otlt-isn">
                                                    <p>Issues In</p>
                                                    <p class="stkVal-Otlt">20.322 $</p>
                                                </div>
                                                <div class="otlt-Ajst">
                                                    <p>Adjust</p>
                                                </div>
                                                <div class="otlt-Pos">
                                                    <p>Sales POS</p>
                                                    <p class="posValue">130.22 $</p>
                                                </div>
                                                <div class="otlt-slBar">
                                                    <p>Sales Bar.Ctrl</p>
                                                    <p class="posValue">186.32 $</p>
                                                </div>
                                                <div class="otlt-clStk">
                                                    <p>Close Stock</p>
                                                    <p class="stkVal-Otlt">720.32 $</p>
                                                </div>
                                                <div class="otlt-usg">
                                                    <p>Usage/ Cost</p>
                                                    <p class="cstVal-Otlt">360.44 $</p>
                                                </div>
                                                <div class="otlt-var">
                                                    <p>Variance</p>
                                                    <p class="varnVal-Otlt">-18.3 $</p>
                                                </div>
                                            </div>
                                            <div class="otlt-hdNote"></div>
                                        </div>
                                        <div class="flipClm-Hide">
                                            <div class="mbStock">
                                                <div class="otlt-stkPrc">
                                                    <p>Stock price</p>
                                                </div>
                                                <div class="otlt-Unit">
                                                    <p class="untCount">Count Unit</p>
                                                    <p class="untFtr">Factor </p>
                                                    <p class="untSub">Sub Unit</p>
                                                </div>
                                                <div class="otlt-avgUsg">
                                                    <p>Average Usage</p>
                                                </div>
                                                <div class="otlt-Min">
                                                    <p>Min</p>
                                                </div>
                                                <div class="otlt-Max">
                                                    <p>Max</p>
                                                </div>
                                                <div class="otlt-Req">
                                                    <p>Requisition</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Otlt">
                                        <a href="javascript:void(0)" class="oltLnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Item Table Head End -->

                                <!-- Item Table Body Start -->
                                <div class="container outletBody-Task">
                                    <div class="otltTbl-bdy border-bottom">
                                        <div class="otltBd-hdImg">
                                            <img src="Assets/images/jack-daniel.png" alt="Item">
                                        </div>
                                        <div class="otltBd-itm">
                                            <p>Jack Daniels 1000ml</p>
                                        </div>
                                        <div class="infoBtn-Hide">
                                            <div class="outletInfo">
                                                <div class="mbStk-Detail">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="otltBd-opnStk">
                                                                <p class="mbSale-Head">Open Stock</p>
                                                                <p class="mblStock-Sale">23 Tot</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p class="mbSale-Head">Issues In</p>
                                                                <p class="mblStock-Sale fw-bold">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p class="mbSale-Head">Adjust</p>
                                                                <p class="mblStock-Sale">&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p class="mbSale-Head">Sales POS</p>
                                                                <p class="mblStock-Sale fw-bold">12 Tot</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p class="mbSale-Head">Sales Bar.C</p>
                                                                <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p class="mbSale-Head">Close Stock</p>
                                                                <p class="mblStock-Sale">50 Tot</p>
                                                            </div>
                                                        </div>
                                                        <div class="itmInfo-Otlt currItm-Info">
                                                            <div class="otltBd-opnStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbShow-Otlt">
                                                    <div class="itmInfo-Otlt">
                                                        <div class="otltBd-usg">
                                                            <p class="mbSale-Head">Cost/Usage</p>
                                                            <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p class="mbSale-Head">Variance</p>
                                                            <p class="mblStock-Sale fw-bold">-1 Tot</p>
                                                        </div>
                                                    </div>
                                                    <div class="itmInfo-Otlt currItm-Info">
                                                        <div class="otltBd-usg">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="otltBd-hdNote">
                                                    <p class="mbSale-Head mbHide-Note">Note</p>
                                                    <input type="text" class="form-control note-itm outletNote"
                                                        placeholder="Note">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flipInfo-Clm">
                                            <div class="outletInfo">
                                                <div class="mbFlip-Dtl">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-stkPrc">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal">3.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbStk-Flp">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="unitBd-div">
                                                                <div class="otltBd-Unithd">
                                                                    <p class="untCount mbSale-Head">Count Unit</p>
                                                                    <p class="untFtr mbSale-Head">Factor </p>
                                                                    <p class="untSub mbSale-Head">Sub Unit</p>
                                                                </div>
                                                                <div class="otltBd-Unit">
                                                                    <p class="untCount">Btl</p>
                                                                    <p class="untFtr">40 </p>
                                                                    <p class="untSub">Tot</p>
                                                                </div>
                                                            </div>

                                                            <div class="otltBd-Avr">
                                                                <p class="mbSale-Head">Avr Usage</p>
                                                                <p class="mblAvg-Dtl">18 Tot</p>
                                                                <p class="mblAvr-Usg">24.76 $</p>
                                                            </div>
                                                            <div class="otltBd-Min">
                                                                <p class="mbSale-Head">Min</p>
                                                                <p class="mblAvg-Dtl">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Max">
                                                                <p class="mbSale-Head">Max</p>
                                                                <p class="mblAvg-Dtl">80 Tot</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbFlip-DtlReq">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-Req">
                                                            <p class="mbSale-Head">Requisition</p>
                                                            <p class="mblAvg-Dtl fw-bold">80 Tot</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-bdyOlt">
                                        <a href="javascript:void(0)" class="slCst-Lnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="container outletBody-Task">
                                    <div class="otltTbl-bdy border-bottom">
                                        <div class="otltBd-hdImg">
                                            <img src="Assets/images/jack-daniel.png" alt="Item">
                                        </div>
                                        <div class="otltBd-itm">
                                            <p>Jack Daniels 1000ml</p>
                                        </div>
                                        <div class="infoBtn-Hide">
                                            <div class="outletInfo">
                                                <div class="mbStk-Detail">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="otltBd-opnStk">
                                                                <p class="mbSale-Head">Open Stock</p>
                                                                <p class="mblStock-Sale">23 Tot</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p class="mbSale-Head">Issues In</p>
                                                                <p class="mblStock-Sale fw-bold">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p class="mbSale-Head">Adjust</p>
                                                                <p class="mblStock-Sale">&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p class="mbSale-Head">Sales POS</p>
                                                                <p class="mblStock-Sale fw-bold">12 Tot</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p class="mbSale-Head">Sales Bar.C</p>
                                                                <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p class="mbSale-Head">Close Stock</p>
                                                                <p class="mblStock-Sale">50 Tot</p>
                                                            </div>
                                                        </div>
                                                        <div class="itmInfo-Otlt currItm-Info">
                                                            <div class="otltBd-opnStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbShow-Otlt">
                                                    <div class="itmInfo-Otlt">
                                                        <div class="otltBd-usg">
                                                            <p class="mbSale-Head">Cost/Usage</p>
                                                            <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p class="mbSale-Head">Variance</p>
                                                            <p class="mblStock-Sale fw-bold">-1 Tot</p>
                                                        </div>
                                                    </div>
                                                    <div class="itmInfo-Otlt currItm-Info">
                                                        <div class="otltBd-usg">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="otltBd-hdNote">
                                                    <p class="mbSale-Head mbHide-Note">Note</p>
                                                    <input type="text" class="form-control note-itm outletNote"
                                                        placeholder="Note">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flipInfo-Clm">
                                            <div class="outletInfo">
                                                <div class="mbFlip-Dtl">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-stkPrc">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal">3.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbStk-Flp">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="unitBd-div">
                                                                <div class="otltBd-Unithd">
                                                                    <p class="untCount mbSale-Head">Count Unit</p>
                                                                    <p class="untFtr mbSale-Head">Factor </p>
                                                                    <p class="untSub mbSale-Head">Sub Unit</p>
                                                                </div>
                                                                <div class="otltBd-Unit">
                                                                    <p class="untCount">Btl</p>
                                                                    <p class="untFtr">40 </p>
                                                                    <p class="untSub">Tot</p>
                                                                </div>
                                                            </div>

                                                            <div class="otltBd-Avr">
                                                                <p class="mbSale-Head">Avr Usage</p>
                                                                <p class="mblAvg-Dtl">18 Tot</p>
                                                                <p class="mblAvr-Usg">24.76 $</p>
                                                            </div>
                                                            <div class="otltBd-Min">
                                                                <p class="mbSale-Head">Min</p>
                                                                <p class="mblAvg-Dtl">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Max">
                                                                <p class="mbSale-Head">Max</p>
                                                                <p class="mblAvg-Dtl">80 Tot</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbFlip-DtlReq">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-Req">
                                                            <p class="mbSale-Head">Requisition</p>
                                                            <p class="mblAvg-Dtl fw-bold">80 Tot</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-bdyOlt">
                                        <a href="javascript:void(0)" class="slCst-Lnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="container outletBody-Task">
                                    <div class="otltTbl-bdy border-bottom">
                                        <div class="otltBd-hdImg">
                                            <img src="Assets/images/jack-daniel.png" alt="Item">
                                        </div>
                                        <div class="otltBd-itm">
                                            <p>Jack Daniels 1000ml</p>
                                        </div>
                                        <div class="infoBtn-Hide">
                                            <div class="outletInfo">
                                                <div class="mbStk-Detail">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="otltBd-opnStk">
                                                                <p class="mbSale-Head">Open Stock</p>
                                                                <p class="mblStock-Sale">23 Tot</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p class="mbSale-Head">Issues In</p>
                                                                <p class="mblStock-Sale fw-bold">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p class="mbSale-Head">Adjust</p>
                                                                <p class="mblStock-Sale">&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p class="mbSale-Head">Sales POS</p>
                                                                <p class="mblStock-Sale fw-bold">12 Tot</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p class="mbSale-Head">Sales Bar.C</p>
                                                                <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p class="mbSale-Head">Close Stock</p>
                                                                <p class="mblStock-Sale">50 Tot</p>
                                                            </div>
                                                        </div>
                                                        <div class="itmInfo-Otlt currItm-Info">
                                                            <div class="otltBd-opnStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbShow-Otlt">
                                                    <div class="itmInfo-Otlt">
                                                        <div class="otltBd-usg">
                                                            <p class="mbSale-Head">Cost/Usage</p>
                                                            <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p class="mbSale-Head">Variance</p>
                                                            <p class="mblStock-Sale fw-bold">-1 Tot</p>
                                                        </div>
                                                    </div>
                                                    <div class="itmInfo-Otlt currItm-Info">
                                                        <div class="otltBd-usg">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="otltBd-hdNote">
                                                    <p class="mbSale-Head mbHide-Note">Note</p>
                                                    <input type="text" class="form-control note-itm outletNote"
                                                        placeholder="Note">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flipInfo-Clm">
                                            <div class="outletInfo">
                                                <div class="mbFlip-Dtl">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-stkPrc">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal">3.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbStk-Flp">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="unitBd-div">
                                                                <div class="otltBd-Unithd">
                                                                    <p class="untCount mbSale-Head">Count Unit</p>
                                                                    <p class="untFtr mbSale-Head">Factor </p>
                                                                    <p class="untSub mbSale-Head">Sub Unit</p>
                                                                </div>
                                                                <div class="otltBd-Unit">
                                                                    <p class="untCount">Btl</p>
                                                                    <p class="untFtr">40 </p>
                                                                    <p class="untSub">Tot</p>
                                                                </div>
                                                            </div>

                                                            <div class="otltBd-Avr">
                                                                <p class="mbSale-Head">Avr Usage</p>
                                                                <p class="mblAvg-Dtl">18 Tot</p>
                                                                <p class="mblAvr-Usg">24.76 $</p>
                                                            </div>
                                                            <div class="otltBd-Min">
                                                                <p class="mbSale-Head">Min</p>
                                                                <p class="mblAvg-Dtl">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Max">
                                                                <p class="mbSale-Head">Max</p>
                                                                <p class="mblAvg-Dtl">80 Tot</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbFlip-DtlReq">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-Req">
                                                            <p class="mbSale-Head">Requisition</p>
                                                            <p class="mblAvg-Dtl fw-bold">80 Tot</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-bdyOlt">
                                        <a href="javascript:void(0)" class="slCst-Lnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="container outletBody-Task">
                                    <div class="otltTbl-bdy border-bottom">
                                        <div class="otltBd-hdImg">
                                            <img src="Assets/images/jack-daniel.png" alt="Item">
                                        </div>
                                        <div class="otltBd-itm">
                                            <p>Jack Daniels 1000ml</p>
                                        </div>
                                        <div class="infoBtn-Hide">
                                            <div class="outletInfo">
                                                <div class="mbStk-Detail">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="otltBd-opnStk">
                                                                <p class="mbSale-Head">Open Stock</p>
                                                                <p class="mblStock-Sale">23 Tot</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p class="mbSale-Head">Issues In</p>
                                                                <p class="mblStock-Sale fw-bold">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p class="mbSale-Head">Adjust</p>
                                                                <p class="mblStock-Sale">&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p class="mbSale-Head">Sales POS</p>
                                                                <p class="mblStock-Sale fw-bold">12 Tot</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p class="mbSale-Head">Sales Bar.C</p>
                                                                <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p class="mbSale-Head">Close Stock</p>
                                                                <p class="mblStock-Sale">50 Tot</p>
                                                            </div>
                                                        </div>
                                                        <div class="itmInfo-Otlt currItm-Info">
                                                            <div class="otltBd-opnStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p>0.4768 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbShow-Otlt">
                                                    <div class="itmInfo-Otlt">
                                                        <div class="otltBd-usg">
                                                            <p class="mbSale-Head">Cost/Usage</p>
                                                            <p class="mblStock-Sale fw-bold">13 Tot</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p class="mbSale-Head">Variance</p>
                                                            <p class="mblStock-Sale fw-bold">-1 Tot</p>
                                                        </div>
                                                    </div>
                                                    <div class="itmInfo-Otlt currItm-Info">
                                                        <div class="otltBd-usg">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p>0.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="otltBd-hdNote">
                                                    <p class="mbSale-Head mbHide-Note">Note</p>
                                                    <input type="text" class="form-control note-itm outletNote"
                                                        placeholder="Note">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flipInfo-Clm">
                                            <div class="outletInfo">
                                                <div class="mbFlip-Dtl">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-stkPrc">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal">3.4768 $</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbStk-Flp">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="unitBd-div">
                                                                <div class="otltBd-Unithd">
                                                                    <p class="untCount mbSale-Head">Count Unit</p>
                                                                    <p class="untFtr mbSale-Head">Factor </p>
                                                                    <p class="untSub mbSale-Head">Sub Unit</p>
                                                                </div>
                                                                <div class="otltBd-Unit">
                                                                    <p class="untCount">Btl</p>
                                                                    <p class="untFtr">40 </p>
                                                                    <p class="untSub">Tot</p>
                                                                </div>
                                                            </div>

                                                            <div class="otltBd-Avr">
                                                                <p class="mbSale-Head">Avr Usage</p>
                                                                <p class="mblAvg-Dtl">18 Tot</p>
                                                                <p class="mblAvr-Usg">24.76 $</p>
                                                            </div>
                                                            <div class="otltBd-Min">
                                                                <p class="mbSale-Head">Min</p>
                                                                <p class="mblAvg-Dtl">40 Tot</p>
                                                            </div>
                                                            <div class="otltBd-Max">
                                                                <p class="mbSale-Head">Max</p>
                                                                <p class="mblAvg-Dtl">80 Tot</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbFlip-DtlReq">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-Req">
                                                            <p class="mbSale-Head">Requisition</p>
                                                            <p class="mblAvg-Dtl fw-bold">80 Tot</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-bdyOlt">
                                        <a href="javascript:void(0)" class="slCst-Lnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Item Table Body End -->
                            </div>
                    </section>

                </section>

            </div>
        </div>
    </div>

    <!-- Add Storage Popup Start -->
    <div class="modal" tabindex="-1" id="adjust" aria-labelledby="add-PhyStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">Adjustment</h1>
                </div>
                <div class="modal-body">
                    <form class="addUser-Form row">
                        <input type="text" class="form-control" id="ajstName" placeholder="Item">
                        <input type="date" class="form-control" id="ajstDate">
                        <input type="number" class="form-control" id="ajstNum" placeholder="1">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">Save</button>
                    </div>
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Storage Popup End -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>