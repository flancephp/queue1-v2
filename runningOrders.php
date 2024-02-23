<?php 
include('inc/dbConfig.php'); //connection details


if(!isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

?>
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


            </div>
        </div>
    </div>


    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>

</body>

</html>