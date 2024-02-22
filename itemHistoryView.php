<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Item Profile - Queue1</title>
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
                                    <a class="nav-link active text-center" href="stockView.php">
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
                            <h1 class="h1">Item Profile</h1>
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
                                    <h1 class="h1">Item Profile</h1>
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

                <section class="itmhstDetail">

                    <section class="itm-profileDtl">
                        <div class="btnBg">
                            <a href="stockView.php" class="sub-btn std-btn">Back</a>
                        </div>
                        <div class="itemImage">
                            <div class="itemImage-bg">
                                <img src="Assets/images/cola.png" alt="Item">
                            </div>
                        </div>

                        <div class="view-tblHead d-flex align-items-center">
                            <div class="vw-clm-8">
                                <p class="productName">Coca Cola 350 MI</p>
                            </div>
                            <div class="vw-clm-4">
                                <p class="productQty">217 Bottle</p>
                            </div>
                        </div>

                        <div class="view-tblBody d-flex align-items-center mt-2">
                            <div class="vw-clm-8">
                                <p class="vwBdy-ttl">Barcode</p>
                                <p class="vwBdy-ttl">Last Purchase Price</p>
                                <p class="vwBdy-ttl">Stock Price</p>
                            </div>
                            <div class="vw-clm-4">
                                <p class="viewBarcode">54490246</p>
                                <p class="viewLst-prc">0.2688 $</p>
                                <p class="viewStock-prc">0.2664 $</p>
                            </div>
                        </div>

                        <div class="view-tblBody d-flex align-items-center mt-3">
                            <div class="vw-clm-8">
                                <p class="vwBdy-ttl">Category</p>
                                <p class="vwBdy-ttl">Sub Category</p>
                                <p class="vwBdy-ttl">Department</p>
                                <p class="vwBdy-ttl">Supplier</p>
                            </div>
                            <div class="vw-clm-4">
                                <p class="viewCategory">Beverage</p>
                                <p class="viewSub-category">Soda</p>
                                <p class="viewDepartment">Bar,Others</p>
                                <p class="viewSupplier">Active Store</p>
                            </div>
                        </div>

                        <div class="itmUnit-tbl">
                            <div class="heading-Unt d-flex align-items-center">
                                <div class="prhead-Unit">
                                    <p>Purchase Unit</p>
                                </div>
                                <div class="arhead-Unit">
                                    <p>@</p>
                                </div>
                                <div class="prhead-Unit">
                                    <p>Counting Unit</p>
                                </div>
                            </div>

                            <div class="body-Unt d-flex align-items-center">
                                <div class="prhead-Unit">
                                    <p class="purchase-Unit">Crate</p>
                                </div>
                                <div class="arhead-Unit">
                                    <p class="num-Unit">24</p>
                                </div>
                                <div class="prhead-Unit">
                                    <p class="count-Unit">Bottle</p>
                                </div>
                            </div>
                        </div>

                    </section>

                    <section class="ordDetail itm-viewDtl">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 itmVw-fetBtnClm">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                            <!-- Filter Btn Start -->
                                            <div class="his-filtBtn">
                                                <a href="javascript:void(0)" class="head-Filter itmVw-Filter">
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
                                <div class="col-md-4 expStrdt d-flex justify-content-end align-items-end">
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
                                        <div class="chkStore itmVw-chkClm">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/chkColumn.svg" alt="Check Column">
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

                        <div class="container mb-hisDtl tb-itmVwdtl">
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

                        <div class="container detailPrice dtlitmVw-Prc">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl itmHis-Dtlview">
                                    <div class="itmView-isuTbl d-flex justify-content-center text-center">
                                        <div class="itmVw-isuIn">
                                            <p class="rd-In">Issue in</p>
                                            <p class="viewItm-isuIn">4,322.05 $</p>
                                            <p class="isuIn-countUnt">1176 Bottle</p>
                                        </div>
                                        <div class="itmVw-isuOut">
                                            <p class="gr-Out">Issue Out</p>
                                            <p class="viewItm-isuOut">3,998.06 $</p>
                                            <p class="isuOut-countUnt">1176 Bottle</p>
                                        </div>
                                        <div class="itmVw-varPtive">
                                            <p class="Vw-varHead"> Variances (+)</p>
                                            <p class="viewItm-varPlus">13 $</p>
                                            <p class="varPlus-countUnt">5 Bottle</p>
                                        </div>
                                        <div class="itmVw-varNtive">
                                            <p class="Vw-varHead"> Variances (-)</p>
                                            <p class="viewItm-varMinus varDif">-123 $</p>
                                            <p class="varMinus-countUnt varDif">-16 Bottle</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container position-relative itmView-Table">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="tb-head d-flex align-items-center dt-Member">
                                    <div class="fltVw-itm">
                                        <div class="tab-mbFltr">
                                            <a href="javascript:void(0)" class="tab-lnkFltr"><i
                                                    class="fa-solid fa-arrow-left"></i></a>
                                        </div>
                                        <div class="tab-Filttxt">
                                            <p>Filter</p>
                                        </div>
                                        <div class="tab-itmVwchk">

                                        </div>
                                    </div>
                                    <div class="itmVw-dateClm">
                                        <p>Date</p>
                                    </div>
                                    <div class="itmVw-memberClm">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Member</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item isuIn-grReq" href="javascript:void(0)">Issue
                                                        In</a>
                                                </li>
                                                <li><a class="dropdown-item isuOut-rdSup"
                                                        href="javascript:void(0)">Issue
                                                        Out</a>
                                                </li>
                                                <li><a class="dropdown-item stockTake-pr"
                                                        href="javascript:void(0)">Stock
                                                        Take</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head d-flex align-items-center isu-Varance">
                                    <div class="itmVw-inClm">
                                        <p>Issue In</p>
                                    </div>
                                    <div class="itmVw-outClm">
                                        <p>Issue Out</p>
                                    </div>
                                    <div class="itmVw-varClm">
                                        <p class="bl-Head">Variances</p>
                                    </div>
                                </div>
                                <div class="tb-head d-flex align-items-center last-Stock">
                                    <div class="itmVw-lstClm">
                                        <p>Last <br> Price</p>
                                    </div>
                                    <div class="itmVw-stkClm">
                                        <p>Stock <br> Price</p>
                                    </div>
                                </div>
                                <div class="tb-head itmVw-qtyClm">
                                    <p>Stock <br> Quantity</p>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>

                        <div id="boxscroll">
                            <div class="container position-relative">
                                <!-- Item Table Body Start -->
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarOut"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="isuOut-Mem">Casa Bar</p>
                                            </div>
                                            <div class="itmVw-stkQtyOut">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                            </div>
                                            <div class="itmVw-outbdClm">
                                                <p class="mb-isuHead">Issue Out</p>
                                                <p>6.3936 $</p>
                                                <p class="ctUnit-var">3 Bottle</p>
                                            </div>
                                            <div class="itmVw-varbdClm">
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>217 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarIn"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="isuIn-Mem">Paje one</p>
                                            </div>
                                            <div class="itmVw-stkQtyIn">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                                <p class="mb-isuHead">Issue In</p>
                                                <p>18.62 $</p>
                                                <p class="ctUnit-var">7 Bottle</p>
                                            </div>
                                            <div class="itmVw-outbdClm">
                                            </div>
                                            <div class="itmVw-varbdClm">
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>285 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarVar"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="varStk-Mem">Stock Take</p>
                                            </div>
                                            <div class="itmVw-stkQtyVar">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                            </div>
                                            <div class="itmVw-outbdClm">
                                            </div>
                                            <div class="itmVw-varbdClm">
                                                <p class="mb-varHead">Variances</p>
                                                <p class="vr-Red">-2.599 $</p>
                                                <p class="vr-Red">-1 Bottle</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>280 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarOut"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="isuOut-Mem">Casa Bar</p>
                                            </div>
                                            <div class="itmVw-stkQtyOut">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                            </div>
                                            <div class="itmVw-outbdClm">
                                                <p class="mb-isuHead">Issue Out</p>
                                                <p>6.3936 $</p>
                                                <p class="ctUnit-var">3 Bottle</p>
                                            </div>
                                            <div class="itmVw-varbdClm">
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>217 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarIn"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="isuIn-Mem">Paje one</p>
                                            </div>
                                            <div class="itmVw-stkQtyIn">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                                <p class="mb-isuHead">Issue In</p>
                                                <p>18.62 $</p>
                                                <p class="ctUnit-var">7 Bottle</p>
                                            </div>
                                            <div class="itmVw-outbdClm">
                                            </div>
                                            <div class="itmVw-varbdClm">
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>285 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarVar"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="varStk-Mem">Stock Take</p>
                                            </div>
                                            <div class="itmVw-stkQtyVar">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                            </div>
                                            <div class="itmVw-outbdClm">
                                            </div>
                                            <div class="itmVw-varbdClm">
                                                <p class="mb-varHead">Variances</p>
                                                <p class="vr-Red">-2.599 $</p>
                                                <p class="vr-Red">-1 Bottle</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>280 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarOut"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="isuOut-Mem">Casa Bar</p>
                                            </div>
                                            <div class="itmVw-stkQtyOut">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                            </div>
                                            <div class="itmVw-outbdClm">
                                                <p class="mb-isuHead">Issue Out</p>
                                                <p>6.3936 $</p>
                                                <p class="ctUnit-var">3 Bottle</p>
                                            </div>
                                            <div class="itmVw-varbdClm">
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>217 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarIn"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="isuIn-Mem">Paje one</p>
                                            </div>
                                            <div class="itmVw-stkQtyIn">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                                <p class="mb-isuHead">Issue In</p>
                                                <p>18.62 $</p>
                                                <p class="ctUnit-var">7 Bottle</p>
                                            </div>
                                            <div class="itmVw-outbdClm">
                                            </div>
                                            <div class="itmVw-varbdClm">
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>285 Bottle</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="itmVw-Task border-bottom">
                                    <div class="mb-itmVwbarVar"></div>
                                    <div class="align-items-center itmBody">
                                        <div class="tb-bdy d-flex align-items-center tbdy-dtMbr">
                                            <div class="itmVw-datebdClm">
                                                <p>12/01/23</p>
                                            </div>
                                            <div class="itmVw-memberbdClm">
                                                <p class="varStk-Mem">Stock Take</p>
                                            </div>
                                            <div class="itmVw-stkQtyVar">

                                            </div>
                                        </div>
                                        <div class="tb-bdy d-flex align-items-center tbdy-isuVar">
                                            <div class="itmVw-inbdClm">
                                            </div>
                                            <div class="itmVw-outbdClm">
                                            </div>
                                            <div class="itmVw-varbdClm">
                                                <p class="mb-varHead">Variances</p>
                                                <p class="vr-Red">-2.599 $</p>
                                                <p class="vr-Red">-1 Bottle</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy align-items-center tbdy-lstStk">
                                            <div class="d-flex align-items-center tabDiv-prc">
                                                <div class="itmVw-lstbdClm">
                                                    <p class="mb-isuHead">Last Price</p>
                                                    <p>0.2664 $</p>
                                                </div>
                                                <div class="itmVw-stkbdClm">
                                                    <p class="mb-isuHead">Stock Price</p>
                                                    <p>0.2884 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tb-bdy itmVw-qtybdClm">
                                            <p>280 Bottle</p>
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