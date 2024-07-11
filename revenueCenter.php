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
        @media screen and (min-width: 1600px) {
        .gt-Data {
            width: 118px;
        }
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
                            <h1 class="h1">Revenue Centers</h1>
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
                    <section class=" container hisData">

                        <div class="alrtMessage">
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
                        </div>

                        <div class="container hisData">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                            <!-- Filter Btn Start -->
                                            <div class="his-filtBtn">
                                                <a href="javascript:void(0)" class="head-Filter">
                                                    <img src="Assets/icons/filter.svg" alt="Filter">
                                                </a>
                                            </div>
                                            <!-- Filter Btn End -->
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
                                <div
                                    class="col-md-6 expStrdt revenue-Feature d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <!-- <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/history-stock.svg" alt="history stock">
                                            </a>
                                        </div> -->
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#revenue_center_pdf">
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
                                <div class="col-md-5 is-Incol">
                                    <p class="sale-Name">Sales</p>
                                    <p class="ttlAmount">4,322.05 $</p>
                                </div>
                                <div class="col-md-5 is-Outcol">
                                    <p class="tl-Cst">Total Cost</p>
                                    <p class="ttlAmount-rec">3,998.06 $</p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="container mbguest-Data">

                        </div>

                        <div class="container detailPrice">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl salesDtl">
                                    <div class="d-flex main-Sldiv">
                                        <div class="gstNo">
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
                                        <div class="ttlCost">
                                            <p class="tl-Cst text-center">Total Cost</p>
                                            <div class="d-flex justify-content-end cst-Value">
                                                <div class="col-md-7 d-flex">
                                                    <p class="prcntage-Val">3,998.06 $</p>
                                                    <p class="cst-Prcntage">34%</p>
                                                </div>
                                            </div>
                                            <div class="d-flex cst-Col">
                                                <div class="col-md-5">
                                                    <p class="sl-Cost">Sales Cost</p>
                                                    <p class="sl-Usage">Usage</p>
                                                    <p class="sl-Variance">Variance</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <p class="sale-Amount">3,438 $</p>
                                                    <p class="usage-Amount">450.06 $</p>
                                                    <p class="var-Amount">110 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sale-Variance text-center">
                                            <p class="sl-varDtl">Variances</p>
                                            <p class="sl-varDif">-110 $</p>
                                            <p class="sl-varValue">13 $</p>
                                            <p class="sl-varTtl">-123 $</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="gt-Data">
                                    <div class="revFeat position-relative text-center">
                                        <a href="javascript:void(0)" class="dropdown-toggle tabFet" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="edIt"></span>
                                            <p class="btn2 d-flex justify-content-center align-items-center">
                                                <span>Get Data</span> <i class="fa-solid fa-angle-down"></i>
                                            </p>
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item ent-Gstno" data-bs-toggle="modal" data-bs-target="#modalEnterGuestNo" href="javascript:void(0)">Enter Guest No.</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item gt-Pos" href="getPosSales.php">Get POS Sales</a>
                                            </li>
                                            <li><a class="dropdown-item imp-Data" href="javascript:void(0)">Import Data File</a></li>
                                            <li>
                                                <a class="dropdown-item dwn-Sample" href="javascript:void(0)"> 
                                                <i class="fa-solid fa-arrow-down"></i> 
                                                <span>Download sample file</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="container position-relative hstTbl-head revTbl-Hd">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="rev-Cntrgst align-items-center">
                                    <div class="tb-head d-flex align-items-center rev-List">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Revenur Center</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- <div class="tb-head guest-List">
                                        <div class="d-flex align-items-center">
                                            <p>Guests</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="otRev-head">
                                    <div class="out-Let align-items-center">
                                        <div class="tb-head d-flex align-items-center out-List">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Outlet</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tb-head out-Sales">
                                            <div class="d-flex align-items-center">
                                                <p>Sales</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head out-Ttlcst">
                                            <div class="d-flex align-items-center">
                                                <p>Cost</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sale-Cost align-items-center">
                                        <div class="tb-head sale-Cstlist">
                                            <div class="d-flex align-items-center">
                                                <p>Variance</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head sale-Usage">
                                            <div class="d-flex align-items-center">
                                                <p>Guests</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>



                                        <div class="tb-head sale-Varlist">
                                            <div class="d-flex align-items-center">
                                                <p>Usageperguests</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head chk-revCnt">
                                        <div class="tab-mbFltr">
                                            <a href="javascript:void(0)" class="tab-lnkFltr"><i
                                                    class="fa-solid fa-arrow-left"></i></a>
                                        </div>
                                        <div class="tab-Filttxt">
                                            <p>Filter</p>
                                        </div>
                                        <a href="javascript:void(0)">
                                            <img src="Assets/icons/chkColumn.svg" alt="Check Column">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>
                    </section>

                    <section class="hisTblbody">
                        <div id="boxscroll">
                            <div class="container position-relative hstTbl-bd">
                                <!-- Item Table Body Start -->
                                <div class="revCntr-Task align-items-center">
                                    <div class="mbshw-Revcnt">
                                        <div class="revCenter-Name d-flex align-items-center itmBody">
                                            <div class="center-List">
                                                <p>Casa</p>
                                            </div>
                                            <div class="num-List">
                                                <p><span class="mbguest-Head">Guests</span> 20</p>
                                            </div>
                                        </div>
                                        <div class="revCenter-Dtl">
                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Casa Bar</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p class="mbhead-revCntr">Sales</p>
                                                        <p class="bl-Sale">742.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">250.9259 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>33.82%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>7 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-76 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>

                                            </div>

                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Casa Kitchen</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p class="mbhead-revCntr">Sales</p>
                                                        <p class="bl-Sale">632.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">120.9259 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>23.82%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>130.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>130.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>4 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-89 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>

                                            </div>

                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Casa Laundry</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p>&nbsp;</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">45 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>&nbsp;</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>&nbsp;</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>45 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="revCntr-Task align-items-center mt-1">
                                    <div class="mbshw-Revcnt">
                                        <div class="revCenter-Name d-flex align-items-center itmBody">
                                            <div class="center-List">
                                                <p>Fun Beach</p>
                                            </div>
                                            <div class="num-List">
                                                <p><span class="mbguest-Head">Guests</span> 43</p>
                                            </div>
                                        </div>
                                        <div class="revCenter-Dtl">
                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Fun Beach Bar</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p class="mbhead-revCntr">Sales</p>
                                                        <p class="bl-Sale">742.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">250.9259 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>33.82%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>7 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-76 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>
                                                <!-- <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="grey-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div> -->
                                            </div>

                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Fun Beach Kitchen</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p class="mbhead-revCntr">Sales</p>
                                                        <p class="bl-Sale">632.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">120.9259 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>23.82%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>130.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>130.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>4 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-89 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>
                                                <!-- <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div> -->
                                            </div>

                                        </div>
                                    </div>

                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="revCntr-Task align-items-center mt-1">
                                    <div class="mbshw-Revcnt">
                                        <div class="revCenter-Name d-flex align-items-center itmBody">
                                            <div class="center-List">
                                                <p>Casa</p>
                                            </div>
                                            <div class="num-List">
                                                <p><span class="mbguest-Head">Guests</span> 20</p>
                                            </div>
                                        </div>
                                        <div class="revCenter-Dtl">
                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Casa Bar</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p class="mbhead-revCntr">Sales</p>
                                                        <p class="bl-Sale">742.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">250.9259 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>33.82%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>7 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-76 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>
                                                <!-- <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="grey-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div> -->
                                            </div>

                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Casa Kitchen</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p class="mbhead-revCntr">Sales</p>
                                                        <p class="bl-Sale">632.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">120.9259 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>23.82%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>130.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>130.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>4 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-89 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>
                                                <!-- <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div> -->
                                            </div>

                                            <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">Casa Laundry</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        <p>&nbsp;</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            <p class="mbhead-revCntr">Total Cost</p>
                                                            <p class="pr-Tcost">45 $</p>
                                                        </div>
                                                        <div class="ttlCost-Pnage">
                                                            <p>&nbsp;</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>&nbsp;</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>45 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>&nbsp;</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>
                                                <!-- <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div> -->
                                            </div>

                                        </div>
                                    </div>

                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <!-- Item Table Body End -->
                            </div>
                    </section>

                </section>

            </div>
        </div>
    </div>


    <!-- Enter Guest No. Modal Start --> 
    
    <!-- Modal -->
    <div
        class="modal fade" id="modalEnterGuestNo" tabindex="-1" role="dialog"
        aria-labelledby="modalEnterGuestNoTitle" aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <p class="modal-title h1" id="modalEnterGuestNoTitle">
                        Enter Guest No
                    </p>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <div class="row g-3 align-items-center addUser-Form"> 
                            <div class="col-md-4">
                                <label for="date" class="label">Date</label>
                            </div>
                            <div class="col-md-8">
                                <input 
                                    type="date"
                                    name="date"
                                    id="date"
                                    class="form-control m-0"
                                >
                            </div>
                            <div class="col-md-4">
                                <label for="revenue_center" class="label">Revenue center</label>
                            </div>
                            <div class="col-md-8">
                                <select name="revenue_center" id="revenue_center" class="form-select m-0">
                                    <option value="Revenue center">Revenue center</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="guest_no" class="label">Guest no.</label>
                            </div>
                            <div class="col-md-8">
                                <input 
                                    type="text"
                                    name="guest_no"
                                    class="form-control m-0"
                                >
                            </div> 
                        </div>
                    </div><!--.//modal-body -->
                    <div class="modal-footer">
                        <div class="btnBg">
                            <button type="submit" name="btnSbt" onclick="" class="deletelink btn sub-btn std-btn">Save</button>
                        </div>
                        <div class="btnBg">
                            <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <!-- Enter Guest No. Modal End -->


    <!-- ===== Revenue Center pdf popup new in div format======= -->
    <div class="modal" tabindex="-1" id="revenue_center_pdf" aria-labelledby="revenue_center_pdfModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md site-modal">
            <div class="modal-content p-2">
                <div class="modal-header pb-3">
                    <div class="d-md-flex align-items-center justify-content-between w-100 ">
                        <div class="d-flex align-items-start w-100 gap-3 w-auto mb-md-0 mb-2 modal-head-btn">
                        
                                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#modalfiltertop">
                                    <i class="fa fa-filter"></i>
                                </button>
                            
                                <div class="collapse" id="modalfiltertop">
                                    <div class="d-flex gap-3 modal-head-row">
                                    

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Headers<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="address" class="form-check-input" value="2">
                                                <span class="fs-13">Address</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                <span class="fs-13">Logo</span>
                                            </li>
                                        </ul>
                                    </div>                                  

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Summary<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="NoOfGuests" class="form-check-input" value="2">
                                                <span class="fs-13">No. of Guests</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="sales" class="form-check-input" value="3">
                                                <span class="fs-13">Sales</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="totalCost" class="form-check-input" value="4">
                                                <span class="fs-13">Total Cost</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="variance" class="form-check-input" value="5">
                                                <span class="fs-13">Variance</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class=" dropdown">
                                        <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Item Table<i class="fa-solid fa-angle-down ps-1"></i>
                                        </button>
                                        <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                            <li>
                                                <input type="checkbox" checked="checked" name="checkAll" class="form-check-input" value="1">
                                                <span class="fs-13">Check All</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="revenueCenter" class="form-check-input" value="2">
                                                <span class="fs-13">Revenue Center</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="guest" class="form-check-input" value="3">
                                                <span class="fs-13">Guest</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="outlet" class="form-check-input" value="4">
                                                <span class="fs-13">Outlet</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="sales" class="form-check-input" value="5">
                                                <span class="fs-13">Sales</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="totalCost" class="form-check-input" value="6">
                                                <span class="fs-13">Total Cost</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="salesCost" class="form-check-input" value="7">
                                                <span class="fs-13">Sales Cost</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="usage" class="form-check-input" value="8">
                                                <span class="fs-13">Usage</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="Variance" class="form-check-input" value="9">
                                                <span class="fs-13">variance</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>

                        

                        </div>
                        <a href="revenueCenterReportPdf.html" class="btn" target="_blank"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                    </div>
                </div> 
                <div class="modal-body px-2 py-3">
                    <div class="row pb-3">
                        <div class="col-6">
                            <div class="modal-address ">
                                <h6 class="semibold fs-14">Our Zazibar</h6>
                                <div class="fs-13 ">
                                    <p>P.o Box 4146</p>
                                    <p>Jambiani</p>
                                    <p>Zanzibar, TANZANIA</p>
                                    <p>inventory@our-zanzibar.com</p>
                                    <p>+255743419217</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="modal-logo">
                                <img src="Assets/icons/logo_Q.svg" alt="Logo">
                            </div>
                        </div>
                    </div>

                    <div class="model-title-with-date">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h6 class="semibold">Revenue Center Report</h6>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-end fs-15"><small>From</small> 16-04-2024 <small>To</small> 19-04-2024</p>
                            </div>
                        </div>
                    </div>

                    <div class="summery-row">
                        <div class="row">
                            <div class="col-3 NoOfGuestsSection pe-1">
                                <div class="modal-table fs-12 w-100 text-center">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">No. of Guests</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">80</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 SalesSection pe-1 ps-0">
                                <div class="modal-table fs-12 w-100 text-center">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">Sales</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">742.0000 $</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 totalCost pe-1 ps-0">
                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">Total Cost</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell">3,994.06 $ &nbsp;  34%</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Sales Cost</div>
                                        <div class="table-cell font-bold">3,600 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Usage</div>
                                        <div class="table-cell font-bold">245.06 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Variance</div>
                                        <div class="table-cell font-bold">140 $</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 varianceSection ps-0">
                                <div class="modal-table fs-12 w-100 text-center">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">Variance</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">-110 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell font-bold">13 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell font-bold text-danger">-123 $</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflowTable"> 
                        <div class="modal-table fs-12 w-100 mt-4 revenue_center_table">
                            <div class="table-row thead">
                                <div class="table-cell">#</div>
                                <div class="table-cell">Revenue Center</div>
                                <div class="table-cell">Guest</div>
                                <div class="table-cell">
                                    <div class="table-row flex-container">
                                        <div class="table-cell">Outlet</div>
                                        <div class="table-cell">Sales</div>
                                        <div class="table-cell">Total Cost</div>
                                        <div class="table-cell">Sales Cost</div>
                                        <div class="table-cell">Usage</div>
                                        <div class="table-cell">Variance</div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-row row-seprate">
                                <div class="table-cell">1</div>
                                <div class="table-cell medium">Casa</div>
                                <div class="table-cell">20</div>
                                <div class="table-cell">
                                    <div class="table-row flex-container row-seprate">
                                        <div class="table-cell medium">Casa Bar</div>
                                        <div class="table-cell">727.00 $</div>
                                        <div class="table-cell medium">252.23 $ &nbsp;  86.3 %</div>
                                        <div class="table-cell">240.44 $</div>
                                        <div class="table-cell">240.44 $</div>
                                        <div class="table-cell">7 $ <span class="text-danger d-block">-15 $</span></div>
                                    </div>
                                    <div class="table-row flex-container row-seprate">
                                        <div class="table-cell medium">Casa Kitchen</div>
                                        <div class="table-cell">677.00 $</div>
                                        <div class="table-cell medium">160.23 $  &nbsp;  0.2 %</div>
                                        <div class="table-cell">178.34 $</div>
                                        <div class="table-cell">178.34 $</div>
                                        <div class="table-cell">8 $ <span class="text-danger d-block">-23 $</span></div>
                                    </div>
                                    <div class="table-row flex-container">
                                        <div class="table-cell medium">Casa Laundry</div>
                                        <div class="table-cell"> &nbsp;</div>
                                        <div class="table-cell medium">45 $</div>
                                        <div class="table-cell"> &nbsp;</div>
                                        <div class="table-cell">45 $</div>
                                        <div class="table-cell">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-row row-seprate">
                                <div class="table-cell">2</div>
                                <div class="table-cell medium">Casa</div>
                                <div class="table-cell">20</div>
                                <div class="table-cell">
                                    <div class="table-row flex-container row-seprate">
                                        <div class="table-cell medium">Nur Bar</div>
                                        <div class="table-cell">727.00 $</div>
                                        <div class="table-cell medium">252.23 $ &nbsp;  86.3 %</div>
                                        <div class="table-cell">240.44 $</div>
                                        <div class="table-cell">240.44 $</div>
                                        <div class="table-cell">&nbsp;</div>
                                    </div>
                                    <div class="table-row flex-container row-seprate">
                                        <div class="table-cell medium">Nur Kitchen</div>
                                        <div class="table-cell">677.00 $</div>
                                        <div class="table-cell medium">160.23 $  &nbsp;  0.2 %</div>
                                        <div class="table-cell">178.34 $</div>
                                        <div class="table-cell">178.34 $</div>
                                        <div class="table-cell">8 $ <span class="text-danger d-block">-23 $</span></div>
                                    </div>
                                    <div class="table-row flex-container">
                                        <div class="table-cell medium">Nur Laundry</div>
                                        <div class="table-cell"> &nbsp;</div>
                                        <div class="table-cell medium">45 $</div>
                                        <div class="table-cell"> &nbsp;</div>
                                        <div class="table-cell">45 $</div>
                                        <div class="table-cell">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-row row-seprate">
                                <div class="table-cell">3</div>
                                <div class="table-cell medium">H.K.</div>
                                <div class="table-cell">20</div>
                                <div class="table-cell">
                                    <div class="table-row flex-container row-seprate">
                                        <div class="table-cell medium">H.K. Bar</div>
                                        <div class="table-cell">727.00 $</div>
                                        <div class="table-cell medium">252.23 $ &nbsp;  86.3 %</div>
                                        <div class="table-cell">240.44 $</div>
                                        <div class="table-cell">240.44 $</div>
                                        <div class="table-cell">7 $ <span class="text-danger d-block">-15 $</span></div>
                                    </div>
                                    <div class="table-row flex-container">
                                        <div class="table-cell medium">H.K. Kitchen</div>
                                        <div class="table-cell">677.00 $</div>
                                        <div class="table-cell medium">160.23 $  &nbsp;  0.2 %</div>
                                        <div class="table-cell">178.34 $</div>
                                        <div class="table-cell">178.34 $</div>
                                        <div class="table-cell">8 $ <span class="text-danger d-block">-23 $</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                
            </div>
        </div>
    </div>
    <!-- ===== Revenue Center pdf popup new in div format======= -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>