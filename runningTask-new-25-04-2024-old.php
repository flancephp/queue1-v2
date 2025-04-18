<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Running Tasks - Queue1</title>
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
                                    <a class="nav-link active text-center" href="runningTask.php">
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
                            <h1 class="h1">Running Tasks</h1>
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
                                    <h1 class="h1">Running Tasks</h1>
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

                <!-- <div class="alrtMessage">
                    <div class="container">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p><strong>Hello User!</strong> Your order has been placed Successfully.</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p><strong>Hello User!</strong> You should check your order carefully.</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div> -->

                <section class="rntskHead">
                    <div class="container">
                        <div class="d-flex align-items-center taskHead">
                            <div style="width: 3%;">&nbsp;</div>
                            <div class="d-flex align-items-center runTable py-1" style="width: 97%;">

                                <div class="d-flex align-items-center" style="width: 55%;">
                                    <div style="width: 3%;">&nbsp;</div>
                                    <div class="d-flex align-items-center" style="width: 72%;">
                                        <div class="p-1" style="width: 30%;">
                                            <p>Refer to</p>
                                        </div>
                                        <div class="p-1" style="width: 17%;">
                                            <p>Date</p>
                                        </div>
                                        <div class="rnTsk-Num">
                                            <p>Number</p>
                                        </div>
                                    </div>
                                    <div style="width: 25%;">
                                        <div class="p-1">
                                            <p>Value</p>
                                        </div>
                                    </div>


                                </div>
                                <div class="d-flex align-items-center" style="width: 45%;">
                                    <div class="p-1">
                                        <p>Status</p>
                                    </div>
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

                <section class="runTask">

                    <div class="container">

                        <div class="task">
                            <!-- Confirmed Member Part Start -->
                            <div class="d-flex align-items-center mt-0 mainRuntsk">
                                <div class="srNum">
                                    <p>1</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail mbrDtl-Bg">
                                            <div class="reqType">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 reqDtl member-Name">
                                                    <p>Member</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p># 10221855</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 px-1 d-flex align-items-center stsBar justify-content-end">
                                                <div class="task-status">
                                                    <p>Confirmed</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink" data-bs-toggle="modal" data-bs-target="#issueout">
                                                            <span class="isuOut"></span>
                                                            <p class="btn2 cn-btn">Issue out</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink"  data-bs-toggle="modal" data-bs-target="#assign">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">Assign</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#issueout2">
                                                            <p class="h3">Inv</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#order_details">Order Detail</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#order_details_supplier">Doc2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#requisition_details">Doc3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <!-- <div class="py-1 tmpBar">&nbsp;</div> -->
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Confirmed Member Part End -->
                        </div>
                        <div class="task">
                            <!-- Assigned Member Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum">
                                    <p>2</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail mbrDtl-Bg">
                                            <div class="reqType">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 reqDtl member-Name">
                                                    <p>Member</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p># 10221855</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="task-status">
                                                    <p>Assigned</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="isuOut"></span>
                                                            <p class="btn2 cn-btn">Issue out</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">Assign</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">Inv</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <!-- <div class="py-1 tmpBar">&nbsp;</div> -->
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Assigned Member Part End -->
                        </div>
                        <div class="task">
                            <!-- Supplier Pending Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum">
                                    <p>3</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbarord">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail reqDtl-Bg">

                                            <div class="ordType">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 reqDtl supMem-Name">
                                                    <p>Supplier</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p># 10221855</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpOrd">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="task-status pending-Sts">
                                                    <p>Pending</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="conFirm"></span>
                                                            <p class="btn2 cn-btn">Confirm</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <!-- <div class="py-1 tmpBar">&nbsp;</div> -->
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Supplier Pending Part End -->
                        </div>
                        <div class="task">
                            <!-- Supplier Confirm Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum">
                                    <p>4</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbarord">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail reqDtl-Bg">

                                            <div class="ordType">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 reqDtl supMem-Name">
                                                    <p>Supplier</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p># 10221855</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbcnfReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center conStatus togleOrder">
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="task-status">
                                                    <p>Confirmed</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="isuOut"></span>
                                                            <p class="btn2 cn-btn">Issue out</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">Assign</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">Inv</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <!-- <div class="py-1 conBar">&nbsp;</div> -->
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Supplier Confirm Part End -->
                        </div>
                        <div class="task">
                            <!-- Member Pending Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum">
                                    <p>5</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail mbrDtl-Bg">
                                            <div class="reqType">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 reqDtl member-Name">
                                                    <p>Member</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p># 10221855</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="task-status pending-Sts">
                                                    <p>Pending</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="conFirm"></span>
                                                            <p class="btn2 cn-btn">Confirm</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <!-- <div class="py-1 tmpBar">&nbsp;</div> -->
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Member Pending Part End -->
                        </div>
                    </div>

                </section>


                <!-- Modal -->
                <div class="modal" id="issueout" tabindex="-1" aria-labelledby="issueoutlabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                    <div class="modal-body text-center fs-13">
                       <p>Please approve one more time the Issue out</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-primary">Issue out</button>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End Modal -->

                <!-- Modal -->
                <div class="modal" id="assign" tabindex="-1" aria-labelledby="assignlabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered ">
                    <div class="modal-content">

                    <div class="modal-body  fs-15">
                       <p>Assign order to user</p>

                       <div class="cstmSelect  fs-14 pt-4">
                                                <span class="semibold d-flex align-items-center gap-1 mb-2 fs-14">
                                                    <input type="checkbox" class="CheckAllOptions form-check-input mt-0" id="CheckAllOptions">
                                                    <label> Check all </label>
                                                </span>

                                                <span class="d-flex align-items-center gap-1 mb-1 fs-13">
                                                <input type="checkbox" id="supplierOptionCheck" class="supplierOptionCheck form-check-input mt-0" name="supplierId[]" value="37">
                                                    <label>M</label>
                                                </span>

                                                                                                <span class="d-flex align-items-center gap-1 mb-1 fs-13">
                                                <input type="checkbox" id="supplierOptionCheck" class="supplierOptionCheck form-check-input mt-0" name="supplierId[]" value="187">
                                                    <label>M2 </label>
                                                </span>

                                                                                                <span class="d-flex align-items-center gap-1 mb-1 fs-13">
                                                <input type="checkbox" id="supplierOptionCheck" class="supplierOptionCheck form-check-input mt-0" name="supplierId[]" value="33">
                                                    <label>Saleh MBL</label>
                                                </span>

                                                       </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" >Save</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End Modal -->


                 <!-- Modal -->
                <div class="modal" id="issueout2" tabindex="-1" aria-labelledby="issueout2label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-550">
                    <div class="modal-content">

                    <div class="modal-body  fs-15">
                        <div class="pb-3">
                            <p>Below items has less stocks than added in this requisition so please edit it:</p>
                            <p class="mt-3"># 123119</p>
                        </div>

                       <table class="issueout2-table w-100 fs-13">
                            <tr class="semibold">
                                <th>Item</th>
                                <th>S.qty</th>
                                <th>Qty</th>
                            </tr>
                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>
                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>
                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>
                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>

                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>
                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>

                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>
                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>

                            <tr>
                                <td>Broken Ice Cubes 7.5kg</td>
                                <td>0</td>
                                <td><input type="number" placeholder="0"></td>
                            </tr>
                       </table>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" >Approve</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End Modal -->

                <!-- ===== order details new in div format======= -->
                <div class="modal" tabindex="-1" id="order_details" aria-labelledby="orderdetails" aria-hidden="true">
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
                                                            <input type="checkbox" checked="checked" name="orderDetails" class="form-check-input" value="3">
                                                            <span class="fs-13">Order details</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                            <span class="fs-13">Logo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="currentDate" class="form-check-input" value="5">
                                                            <span class="fs-13">Current Date</span>
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
                                                            <input type="checkbox" checked="checked" name="defaultCurrencyAmount" class="form-check-input" value="2">
                                                            <span class="fs-13">Default Currency Amount</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyAmount" class="form-check-input" value="3">
                                                            <span class="fs-13">Second Currency Amount</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="supplierInvoice" class="form-check-input" value="4">
                                                            <span class="fs-13">Supplier Invoice #</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="payment" class="form-check-input" value="5">
                                                            <span class="fs-13">Payment #</span>
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
                                                            <input type="checkbox" checked="checked" name="Photo" class="form-check-input" value="2">
                                                            <span class="fs-13">Photo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="itemName" class="form-check-input" value="3">
                                                            <span class="fs-13">Item Name</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="barcode" class="form-check-input" value="4">
                                                            <span class="fs-13">Barcode</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="price" class="form-check-input" value="5">
                                                            <span class="fs-13">Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyPrice" class="form-check-input" value="6">
                                                            <span class="fs-13">Second Currency Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="unit" class="form-check-input" value="7">
                                                            <span class="fs-13">Unit</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="qty" class="form-check-input" value="8">
                                                            <span class="fs-13">Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="receivedQty" class="form-check-input" value="9">
                                                            <span class="fs-13">Received Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="total" class="form-check-input" value="10">
                                                            <span class="fs-13">Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="11">
                                                            <span class="fs-13">Second Currency Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="note" class="form-check-input" value="12">
                                                            <span class="fs-13">Note</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class=" dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Task Record<i class="fa-solid fa-angle-down ps-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="taskRecord" class="form-check-input" value="1">
                                                            <span class="fs-13">Task Record</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                </div>
                                            
                                            </div>

                                    

                                    </div>
                                    <a href="#" class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                                </div>
                            </div> 
                            <div class="modal-body px-2 py-3">
                                <div class="row pb-3">
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <h4 class="text-center semibold">Order details</h4>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="modal-logo">
                                            <img src="Assets/icons/logo_Q.svg" alt="Logo">
                                        </div>
                                        <div class="modal-date pt-1">
                                            <p>08/03/2024</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-table">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">Task No.</div>
                                        <div class="table-cell medium">Supplier</div>
                                        <div class="table-cell medium">Order by</div>
                                        <div class="table-cell p-0">
                                            <div class="sub-table w-100" style="display: table;">
                                                <div class="table-row">
                                                    <div class="table-cell" style="width: 40%;"></div>
                                                    <div class="table-cell medium">Total</div>
                                                    <div class="table-cell" style="width: 20%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">123095</div>
                                        <div class="table-cell">Active Store</div>
                                        <div class="table-cell">Our Zazibar</div>
                                        <div class="table-cell p-0">
                                            <div class="sub-table w-100" style="display: table;">
                                                <div class="table-row">
                                                    <div class="table-cell" style="width: 40%;"></div>
                                                    <div class="table-cell">29.28$</div>
                                                    <div class="table-cell" style="width: 20%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 16%;"></div>
                                        <div class="table-cell" style="width: 34%;">
                                            <div class="sub-table w-100" style="display: table;">
                                                <div class="table-row">
                                                    <div class="table-cell">Grand Total</div>
                                                    <div class="table-cell">157 $</div>
                                                    <div class="table-cell">138.888 €</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">#</div>
                                        <div class="table-cell">Item</div>
                                        <div class="table-cell">Barcode</div>
                                        <div class="table-cell">Price</div>
                                        <div class="table-cell">P. Unit</div>
                                        <div class="table-cell">P. Qty.</div>
                                        <div class="table-cell">Rec Qty.</div>
                                        <div class="table-cell">Total($)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Bitter Lemon 300 Ml</div>
                                        <div class="table-cell">569856285</div>
                                        <div class="table-cell">5.32 $</div>
                                        <div class="table-cell">box</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">13.32 $</div>
                                        <div class="table-cell">Lorem Ipsum dolor sit amet</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Bitter Lemon 300 Ml</div>
                                        <div class="table-cell">569856285</div>
                                        <div class="table-cell">5.32 $</div>
                                        <div class="table-cell">box</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">13.32 $</div>
                                        <div class="table-cell">Lorem Ipsum dolor sit amet</div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">Status</div>
                                        <div class="table-cell">Date</div>
                                        <div class="table-cell">User</div>
                                        <div class="table-cell">Price($)</div>
                                        <div class="table-cell">Price(Tsh)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- ===== order details new in div format======= -->

                <!-- ===== order details new in div format======= -->
                <div class="modal" tabindex="-1" id="order_details_supplier" aria-labelledby="orderdetailsSupplier" aria-hidden="true">
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
                                                            <input type="checkbox" checked="checked" name="orderDetails" class="form-check-input" value="3">
                                                            <span class="fs-13">Order details</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                            <span class="fs-13">Logo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="currentDate" class="form-check-input" value="5">
                                                            <span class="fs-13">Current Date</span>
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
                                                            <input type="checkbox" checked="checked" name="defaultCurrencyAmount" class="form-check-input" value="2">
                                                            <span class="fs-13">Default Currency Amount</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyAmount" class="form-check-input" value="3">
                                                            <span class="fs-13">Second Currency Amount</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="supplierInvoice" class="form-check-input" value="4">
                                                            <span class="fs-13">Supplier Invoice #</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="payment" class="form-check-input" value="5">
                                                            <span class="fs-13">Payment #</span>
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
                                                            <input type="checkbox" checked="checked" name="Photo" class="form-check-input" value="2">
                                                            <span class="fs-13">Photo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="itemName" class="form-check-input" value="3">
                                                            <span class="fs-13">Item Name</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="barcode" class="form-check-input" value="4">
                                                            <span class="fs-13">Barcode</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="price" class="form-check-input" value="5">
                                                            <span class="fs-13">Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyPrice" class="form-check-input" value="6">
                                                            <span class="fs-13">Second Currency Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="unit" class="form-check-input" value="7">
                                                            <span class="fs-13">Unit</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="qty" class="form-check-input" value="8">
                                                            <span class="fs-13">Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="receivedQty" class="form-check-input" value="9">
                                                            <span class="fs-13">Received Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="total" class="form-check-input" value="10">
                                                            <span class="fs-13">Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyTotal" class="form-check-input" value="11">
                                                            <span class="fs-13">Second Currency Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="note" class="form-check-input" value="12">
                                                            <span class="fs-13">Note</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class=" dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Task Record<i class="fa-solid fa-angle-down ps-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="taskRecord" class="form-check-input" value="1">
                                                            <span class="fs-13">Task Record</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                </div>
                                            
                                            </div>

                                    

                                    </div>
                                    <a href="#" class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                                </div>
                            </div> 
                            <div class="modal-body px-2 py-3">
                                <div class="row pb-3">
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <h4 class="text-center semibold">Order details</h4>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="modal-logo">
                                            <img src="Assets/icons/logo_Q.svg" alt="Logo">
                                        </div>
                                        <div class="modal-date pt-1">
                                            <p>08/03/2024</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">Task No.</div>
                                        <div class="table-cell medium">Supplier</div>
                                        <div class="table-cell medium">Total</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">123095</div>
                                        <div class="table-cell">Active Store</div>
                                        <div class="table-row">
                                            <div class="table-cell">199.16 $</div>
                                            <div class="table-cell">488,132 Tsh</div>
                                        </div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell" style="width: 30%;">
                                            <div class="sub-table w-100" style="">
                                                <div class="table-row">
                                                    <div class="table-cell"># Supplier Invoice</div>
                                                    <div class="table-cell">6443</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-cell" style="width: 35%;"></div>
                                        <div class="table-cell" style="width: 35%;"></div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">#</div>
                                        <div class="table-cell">Item</div>
                                        <div class="table-cell">Unit</div>
                                        <div class="table-cell">Qty.</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Transport</div>
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">2</div>
                                        <div class="table-cell">Coca Cola Zero Plastic 500 Ml</div>
                                        <div class="table-cell">Box</div>
                                        <div class="table-cell">10</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">Corona beer</div>
                                        <div class="table-cell">Box</div>
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">4</div>
                                        <div class="table-cell">Fanta Orange 350 Ml</div>
                                        <div class="table-cell">Crate</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">5</div>
                                        <div class="table-cell">Fanta Passion 350 Ml</div>
                                        <div class="table-cell">Crate</div>
                                        <div class="table-cell">5</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">6</div>
                                        <div class="table-cell">Fanta Pineaple 350 Ml</div>
                                        <div class="table-cell">Crate</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">7</div>
                                        <div class="table-cell">Safari Beer</div>
                                        <div class="table-cell">Crate</div>
                                        <div class="table-cell">10</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">8</div>
                                        <div class="table-cell">Sprite 350 Ml</div>
                                        <div class="table-cell">Crate</div>
                                        <div class="table-cell">4</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">9</div>
                                        <div class="table-cell">Stoney Tangawizi 350 Ml</div>
                                        <div class="table-cell">Crate</div>
                                        <div class="table-cell">3</div>
                                        <div class="table-cell">town ase</div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">Status</div>
                                        <div class="table-cell">Date</div>
                                        <div class="table-cell">User</div>
                                        <div class="table-cell">Price($)</div>
                                        <div class="table-cell">Price(Tsh)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">344.62 $</div>
                                        <div class="table-cell">844,318.02</div>
                                        <div class="table-cell">Added new order</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- ===== order details new in div format======= -->

                <!-- ===== Requisition  details new in div format======= -->
                <div class="modal" tabindex="-1" id="requisition_details" aria-labelledby="requisition_details" aria-hidden="true">
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
                                                            <input type="checkbox" checked="checked" name="orderDetails" class="form-check-input" value="3">
                                                            <span class="fs-13">Requisition details</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="logo" class="form-check-input" value="4">
                                                            <span class="fs-13">Logo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="currentDate" class="form-check-input" value="5">
                                                            <span class="fs-13">Current Date</span>
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
                                                            <input type="checkbox" checked="checked" name="taskNo" class="form-check-input" value="2">
                                                            <span class="fs-13">Task No.</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="department" class="form-check-input" value="3">
                                                            <span class="fs-13">Department</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="Member" class="form-check-input" value="4">
                                                            <span class="fs-13">Member</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="Amounts" class="form-check-input" value="5">
                                                            <span class="fs-13">Amounts</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="Invoice" class="form-check-input" value="5">
                                                            <span class="fs-13">Invoice</span>
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
                                                            <input type="checkbox" checked="checked" name="Photo" class="form-check-input" value="2">
                                                            <span class="fs-13">Photo</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="itemName" class="form-check-input" value="3">
                                                            <span class="fs-13">Item Name</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="barcode" class="form-check-input" value="4">
                                                            <span class="fs-13">Barcode</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="price" class="form-check-input" value="5">
                                                            <span class="fs-13">Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="secondCurrencyPrice" class="form-check-input" value="6">
                                                            <span class="fs-13">Second Currency Price</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="unit" class="form-check-input" value="7">
                                                            <span class="fs-13">Unit</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="qty" class="form-check-input" value="8">
                                                            <span class="fs-13">Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="RequestedQty" class="form-check-input" value="9">
                                                            <span class="fs-13">Requested  Qty</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="total" class="form-check-input" value="10">
                                                            <span class="fs-13">Total</span>
                                                        </li>
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="note" class="form-check-input" value="12">
                                                            <span class="fs-13">Note</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class=" dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle fs-13 py-2" type="button" id="headers" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Task Record<i class="fa-solid fa-angle-down ps-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu px-3" aria-labelledby="headers">
                                                        <li>
                                                            <input type="checkbox" checked="checked" name="taskRecord" class="form-check-input" value="1">
                                                            <span class="fs-13">Task Record</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                </div>
                                            
                                            </div>

                                    

                                    </div>
                                    <a href="#" class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
                                </div>
                            </div> 
                            <div class="modal-body px-2 py-3">
                                <div class="row pb-3">
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <h4 class="text-center semibold">Requisition details</h4>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="modal-logo">
                                            <img src="Assets/icons/logo_Q.svg" alt="Logo">
                                        </div>
                                        <div class="modal-date pt-1">
                                            <p>08/03/2024</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">Task No.</div>
                                        <div class="table-cell medium">Department</div>
                                        <div class="table-cell medium">Member</div>
                                        <div class="table-cell medium">Total</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">123214</div>
                                        <div class="table-cell">Bar</div>
                                        <div class="table-cell">Casa Bar1</div>
                                        <div class="table-cell">19.70 $</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell" style="width: 25%;">
                                            <div class="sub-table w-100" style="">
                                                <div class="table-row">
                                                    <div class="table-cell"># Invoice</div>
                                                    <div class="table-cell">000131</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;"></div>
                                        <div class="table-cell" style="width: 25%;">
                                            <div class="sub-table w-100" style="display: table;">
                                                <div class="table-row">
                                                    <div class="table-cell">Grand Total</div>
                                                    <div class="table-cell">157 $</div>
                                                    <div class="table-cell">138.888 €</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">#</div>
                                        <div class="table-cell">Item</div>
                                        <div class="table-cell">Barcode</div>
                                        <div class="table-cell">Price($)</div>
                                        <div class="table-cell">C.Unit</div>
                                        <div class="table-cell">Req. Qty</div>
                                        <div class="table-cell">Qty</div>
                                        <div class="table-cell">Total($)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Safari Beer</div>
                                        <div class="table-cell">6201100060138</div>
                                        <div class="table-cell">0.80 $</div>
                                        <div class="table-cell">Bottle</div>
                                        <div class="table-cell">12</div>
                                        <div class="table-cell">12</div>
                                        <div class="table-cell">9.60 $</div>
                                        <div class="table-cell"></div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">1</div>
                                        <div class="table-cell">Kilimanjaro  Beer</div>
                                        <div class="table-cell">6201100060138</div>
                                        <div class="table-cell">0.80 $</div>
                                        <div class="table-cell">Bottle</div>
                                        <div class="table-cell">12</div>
                                        <div class="table-cell">12</div>
                                        <div class="table-cell">9.60 $</div>
                                        <div class="table-cell"></div>
                                    </div>
                                </div>

                                <div class="modal-table fs-12 w-100 mt-4">
                                    <div class="table-row thead">
                                        <div class="table-cell">Status</div>
                                        <div class="table-cell">Date</div>
                                        <div class="table-cell">User</div>
                                        <div class="table-cell">Price($)</div>
                                        <div class="table-cell">Note</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">19.70 $</div>
                                        <div class="table-cell">Added new Requisition</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">19.70 $</div>
                                        <div class="table-cell">Added new Requisition</div>
                                    </div>
                                    <div class="table-row">
                                        <div class="table-cell">Submit</div>
                                        <div class="table-cell">07/04/2024 <br>09:54AM</div>
                                        <div class="table-cell">Salehqa(Supervisor)</div>
                                        <div class="table-cell">19.70 $</div>
                                        <div class="table-cell">Added new Requisition</div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- ===== order details new in div format======= -->
            </div>
        </div>
    </div>


    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>

</body>

</html>