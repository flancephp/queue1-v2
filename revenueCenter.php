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

</head>

<body>

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
                    <section class="ordDetail hisTory revCenter">

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
                                <div
                                    class="col-md-6 expStrdt revenue-Feature d-flex justify-content-end align-items-end">
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
                                            <li><a class="dropdown-item ent-Gstno" href="javascript:void(0)">Enter Guest
                                                    No.</a>
                                            </li>
                                            <li><a class="dropdown-item gt-Pos" href="javascript:void(0)">Get POS
                                                    Sales</a>
                                            </li>
                                            <li><a class="dropdown-item imp-Data" href="javascript:void(0)">Import Data
                                                    File</a></li>
                                            <li><a class="dropdown-item dwn-Sample" href="javascript:void(0)"> <i
                                                        class="fa-solid fa-arrow-down"></i> <span>Download sample
                                                        file</span></a></li>
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
                                    <div class="tb-head guest-List">
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
                                                <p>Total Cost</p>
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
                                                <p>Sales Cost</p>
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
                                                <p>Usage</p>
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
                                                <p>Variance</p>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="grey-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="grey-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="grey-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
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
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div>
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

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>