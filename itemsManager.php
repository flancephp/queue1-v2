<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Items Manager - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>

<body class="mb-Bgbdy">

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
                                <a class="nav-link active text-center" href="setup.php">
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
                            <h1 class="h1">Items Manager</h1>
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
                                    <h1 class="h1">Items Manager</h1>
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

                <section class="ordDetail userDetail itmMngDetail">
                    <div class="container">
                        <div class="row itm-Manage">
                            <div class="col-md-6 bkItm-MngBtn">
                                <div class="btnBg">
                                    <a href="setup.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>
                            <div class="mbItm-MngIcns">

                            </div>
                            <div class="col-md-6 fetItm-Mng">
                                <div class="itmLnk-Row">
                                    <div class="itmFeat-Div itmMng-Feature">
                                        <a href="javascript:void(0)" class="itm-Feature">
                                            <span class="add-Itm"></span>
                                            <p class="btn2 ad-Span">Add Item</p>
                                        </a>
                                    </div>

                                    <div class="itmFeat-Div itmMng-Feature">
                                        <a href="javascript:void(0)" class="dropdown-toggle itm-Feature" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="impItem"></span>
                                            <p class="btn2 d-flex justify-content-center align-items-center">
                                                <span>Import Items List</span> <i class="fa-solid fa-angle-down"></i>
                                            </p>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item ent-Gstno" href="javascript:void(0)">Import
                                                    Items List</a>
                                            </li>
                                            <li><a class="dropdown-item gt-Pos" href="javascript:void(0)">Upload Photos
                                                    (zip file)</a>
                                            </li>
                                            <li><a class="dropdown-item dwn-Sample" href="javascript:void(0)"> <i
                                                        class="fa-solid fa-arrow-down"></i> <span>Download sample
                                                        file</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="itmMng-Src">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Search Box Start -->
                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" placeholder="Search" id="srch"
                                            aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Search Box End -->
                                </div>
                                <!-- Filter Btn Start -->
                                <div class="itmMng-filtBtn">
                                    <div class="itmFilt-Mng">
                                        <a href="javascript:void(0)" class="head-Filter itmMng-FilterBtn">
                                            <img src="Assets/icons/filter.svg" alt="Filter">
                                        </a>
                                    </div>
                                </div>
                                <!-- Filter Btn End -->
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex align-items-center itmMng-xlIcn">
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

                                <div class="clnItm-MngDrp"></div>

                            </div>
                        </div>
                    </div>

                    <div class="container itmMng-Tblhead position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="tb-head imgItm-MngClm">
                                <div class="d-flex align-items-center">
                                    <p>Photo</p>
                                    <span class="dblArrow">
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-up"></i></a>
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="align-items-center brItm-MngClm">
                                <div class="tb-head d-flex align-items-center item-MngClm">
                                    <p>Item</p>
                                    <span class="dblArrow">
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-up"></i></a>
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </span>
                                </div>
                                <div class="tb-head d-flex align-items-center brCode-MngClm">
                                    <p>Bar Code</p>
                                    <span class="dblArrow">
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-up"></i></a>
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </span>
                                </div>
                                <div class="tb-head d-flex align-items-center unit-MngClm">
                                    <p class="untPf">Unit P/F/ <br> Unit C.</p>
                                    <span class="dblArrow">
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-up"></i></a>
                                        <a href="javascript:void(0)" class="d-block aglStock"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="align-items-center drpItm-MngClm drpHead-ItmMng">
                                <div class="tb-head drpDwn-ItmMng subCat-ItmMng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Sub Catagory</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Sub Catagory
                                                        4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head drpDwn-ItmMng supItm-Mng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Supplier</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Supplier 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head drpDwn-ItmMng deptItm-Mng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Department</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Department 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head drpDwn-ItmMng strgItm-Mng">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Storage</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Storage 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="align-items-center prcItm-MngClm">
                                <div class="tb-head lastItm-PrcCol">
                                    <div class="d-flex align-items-center">
                                        <p>Last <br>
                                            Price</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="tb-head stockItm-PrcCol">
                                    <div class="d-flex align-items-center">
                                        <p>Stock <br>
                                            Price</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="icnItm-MngClm">
                                &nbsp;
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>

                    <!-- Item Table Body Start -->
                    <div id="boxscroll">
                        <div class="container cntTable">
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody itemManager-Task">
                                <div class="itmTask-Mng align-items-center">
                                    <div class="tb-bdy imgItm-MngClm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center brItm-MngClm">
                                        <div class="tb-bdy item-MngClm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCode-MngClm">
                                            <p><span class="mb-CodeItm">Code</span> <strong>18</strong> kg</p>
                                        </div>
                                        <div class="tb-bdy unit-MngClm bdyCol-Itm">
                                            <p><span class="mb-UnitItm">Unit P/F/C</span> 2.2$</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center drpItm-MngClm">
                                        <div class="tb-bdy drpDwn-ItmMng subCat-ItmMng">
                                            <p>Vegetables</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng supItm-Mng">
                                            <p>Mbero shop</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng deptItm-Mng">
                                            <p>Kitchen</p>
                                        </div>
                                        <div class="tb-bdy drpDwn-ItmMng strgItm-Mng">
                                            <p>Vegetables/Diary</p>
                                        </div>
                                    </div>
                                    <div class="align-items-center prcItm-MngClm">
                                        <div class="tb-bdy lastItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">L. Price</span> 1$</p>
                                        </div>
                                        <div class="tb-bdy stockItm-PrcCol bdyCol-Itm">
                                            <p><span class="mbLst-ItmMng">S. Price</span> 1$</p>
                                        </div>
                                    </div>
                                    <div class="icnItm-MngClm">
                                        <div class="tb-bdy usrOpt-Clm d-flex align-items-center">
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                            </a>
                                            <a href="javascript:void(0)" class="userLink">
                                                <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <a href="javascript:void(0)" class="statusLink mb-itmMngLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- Item Table Body End -->


                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>