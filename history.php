<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>History - Queue1</title>
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
                                    <a class="nav-link active text-center" href="history.php">
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
                                    <a class="nav-link text-center" href="revenueCenter.php">
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
                            <h1 class="h1">History</h1>
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
                                    <h1 class="h1">History</h1>
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

                <section class="hisParent-sec">

                    <section class="ordDetail hisTory">

                        <!-- <div class="alrtMessage">
                            <div class="container">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <p> <strong>Hello User!</strong> You record deleted Successfully.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>

                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hello User!</strong> You should check your order carefully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div> -->

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
                                <div class="col-md-5 is-Incol">
                                    <p class="rd-In">Issue in</p>
                                    <p class="ttlAmount">4,322.05 $</p>
                                </div>
                                <div class="col-md-5 is-Outcol">
                                    <p class="gr-Out">Issue Out</p>
                                    <p class="ttlAmount-rec">3,998.06 $</p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="container detailPrice">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl">
                                    <div class="issueIn">
                                        <div class="dspBlk">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">Paid</p>
                                                    <p class="pendStatus">Pending</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="rd-In">Issue in</p>
                                                    <p class="ttlAmount">4,322.05 $</p>
                                                    <p class="pdAmount">2,718.05 $</p>
                                                    <p class="pendAmount">1,604 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="usdCurr text-center">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">Paid</p>
                                                    <p class="pendStatus">Pending</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="usd-In">Usd</p>
                                                    <p class="ttlAmount">318.50 $</p>
                                                    <p class="pdAmount">318.50 $</p>
                                                    <p class="pendAmount">-</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="otrCurr text-center">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">Paid</p>
                                                    <p class="pendStatus">Pending</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="otr-In">Tzs</p>
                                                    <p class="ttlAmount">9,209,200 Tzs</p>
                                                    <p class="pdAmount">5,520,000 Tzs</p>
                                                    <p class="pendAmount">3,689,200 Tzs</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="issueOut">
                                        <div class="recIsue d-flex">
                                            <div class="col-md-5">
                                                <p class="recStatus">Received</p>
                                                <p class="pendStatus">Pending</p>
                                            </div>
                                            <div class="col-md-7 text-center">
                                                <p class="gr-Out">Issue out</p>
                                                <p class="ttlAmount-rec">3,998.06 $</p>
                                                <p class="pdAmount-rec">2,992.30 $</p>
                                                <p class="pendAmount-rec">1,005.76 $</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Variance text-center">
                                        <p class="varDtl">Variances</p>
                                        <p class="varValue">13 $</p>
                                        <p class="varDif">-123 $</p>
                                    </div>
                                </div>
                                <div class="accntDtl">
                                    <p class="accHead text-center">Accounts</p>
                                    <div class="d-flex">
                                        <div class="bnkName">
                                            <p>Safe 02 $</p>
                                            <p>Stander Bank $</p>
                                        </div>
                                        <div class="bnkBalance">
                                            <p class="negBlnc">-123 $</p>
                                            <p class="posBlnc">23,990 $</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container position-relative hstTbl-head">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="numRef align-items-center">
                                    <div class="tb-head srHisclm">&nbsp;</div>
                                    <div class="tb-head numItmclm">
                                        <div class="d-flex align-items-center">
                                            <p>Number</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head hisDateclm">
                                        <p class="date-txt">Date</p>
                                        <div class="align-items-center date-Drpdwn">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Date</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Submit
                                                            Date</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Settle
                                                            Date</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Payment
                                                            Date</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head hisTypclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Type</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Type
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Type
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Type
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Type
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head hisRefrclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Refer to</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item isuIn-grReq"
                                                            href="javascript:void(0)">Requisition</a>
                                                    </li>
                                                    <li><a class="dropdown-item isuOut-rdSup"
                                                            href="javascript:void(0)">Order</a>
                                                    </li>
                                                    <li><a class="dropdown-item stockTake-pr"
                                                            href="javascript:void(0)">Stock Take</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head hisValclm">
                                    <div class="d-flex align-items-center">
                                        <p>Value</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="stsHiscol d-flex align-items-center">
                                    <div class="tb-head hisStatusclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Status</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Status
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Status
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Status
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Status
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head hisAcntclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Account</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Account
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Account
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Account
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Account
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head shrtHisclm">
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
                            <!-- Item Table Head End -->
                        </div>
                    </section>

                    <section class="hisTblbody">
                        <div id="boxscroll">
                            <div class="container position-relative hstTbl-bd">
                                <!-- Item Table Body Start -->
                                <div class="hisTask">
                                    <div class="mb-hstryBareq">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>1</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisReq-typ">
                                                    <div class="reqBar-gr">&nbsp;</div>
                                                    <p>Requisition</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Member</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-pendStatus">Pending</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Inv</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarord">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>2</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo  hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisOrd-typ">
                                                    <div class="ordBar-rd">&nbsp;</div>
                                                    <p>Order</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Supplier</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr">357,900 Tzs</p>
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-pendStatus">Pending</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Pay</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarord">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>3</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisOrd-typ">
                                                    <div class="ordBar-rd">&nbsp;</div>
                                                    <p>Order</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Supplier</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-paidStatus">Paid</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Pay</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarstk">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>4</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo  hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisStk-typ">
                                                    <div class="stckBar-bl">&nbsp;</div>
                                                    <p>Stock Take</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Storage</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr-ngive">-144.30 $</p>
                                        </div>
                                        <div class="tb-bdy shrtHisclm stkCol-blnk">
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" class="runLink">
                                                        <span class="vwDtl"></span>
                                                    </a>
                                                </div>
                                                <div
                                                    class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" class="runLink">
                                                        <span class="dlTe"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBareq">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>5</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo hide-hisNo">No. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisReq-typ">
                                                    <div class="reqBar-gr">&nbsp;</div>
                                                    <p>Requisition</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">Member</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-recStatus">Received</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">Safe 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>Account</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">Inv</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">Documents <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">Doc3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
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