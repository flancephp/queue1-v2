<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Additional Fees - Queue1</title>
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
                            <h1 class="h1">Manage Additional Fees</h1>
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
                                    <h1 class="h1">Manage Additional Fees</h1>
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

                <section class="ordDetail userDetail">
                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="addAdditionalFee.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                            <span class="nstdSpan">Fee</span></span> <span class="dsktp-Btn">Add
                                            Fee</span></a>
                                </div>
                            </div>
                        </div>

                        <div class="srvFeeTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="srvFeeTbl-Head align-items-center itmTable">
                                    <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                        <div class="tb-head feeNum-Clm">
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
                                        <div class="tb-head feeName-Clm">
                                            <p>Fee Name</p>
                                        </div>
                                    </div>
                                    <div class="srvFeeTbl-UntCol d-flex align-items-center">
                                        <div class="tb-head feeUnit-Clm">
                                            <p>Type</p>
                                        </div>
                                        <div class="tb-head feeAmnt-Clm">
                                            <p>Amount</p>
                                        </div>
                                    </div>
                                    <div class="srvFeeTbl-IcnCol">
                                        <div class="tb-head feeOpt-Clm">
                                            <p>Options</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->

                                <!-- Table Body Start -->
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>1</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Tax</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Tax</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>10 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>2</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Tax</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Tax</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>50 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>3</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Gate charge</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Fixed discount</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>10 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>4</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Discount</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Fixed discount</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>60 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>5</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Discount</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Fixed discount</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>30 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>1</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Tax</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Tax</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>10 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>2</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Tax</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Tax</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>50 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>3</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Gate charge</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Fixed discount</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>10 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>4</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Discount</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Fixed discount</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>60 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                <div class="SerFeeTask">
                                    <div class="serFeeTbl-body itmBody">
                                        <div class="srvFeeTbl-NmCol d-flex align-items-center">
                                            <div class="tb-bdy feeNum-Clm">
                                                <p class="feeSrNumber"><span class="mb-UsrSpan">No. </span>5</p>
                                            </div>
                                            <div class="tb-bdy feeName-Clm">
                                                <p class="suplName">Discount</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-UntCol align-items-center">
                                            <div class="tb-head feeUnit-Clm">
                                                <p>Fixed discount</p>
                                            </div>
                                            <div class="tb-head feeAmnt-Clm">
                                                <p>30 %</p>
                                            </div>
                                        </div>
                                        <div class="srvFeeTbl-IcnCol">
                                            <div class="tb-bdy feeOpt-Clm d-flex align-items-center">
                                                <a href="editAdditionalFee.php" class="userLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask-Fee">
                                        <a href="javascript:void(0)" class="serFeeLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <!-- Table Body End -->

                            </div>
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