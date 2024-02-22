<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Order - Queue1</title>
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
    <main>
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
                                        <a class="nav-link active text-center dropdown-toggle" aria-current="page"
                                            href="index.php" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="Assets/icons/new_task.svg" alt="Task" class="navIcon">
                                            <img src="Assets/icons/new_task_hv.svg" alt="Task" class="mb_navIcn">
                                            <p>New Task</p>
                                        </a>
                                        <ul class="dropdown-menu nwSub-Menu" aria-labelledby="navbarDropdown">
                                            <li><a class="nav-link nav_sub sbActive" aria-current="page"
                                                    href="index.php">
                                                    <img src="Assets/icons/new_order.svg" alt="New order"
                                                        class="navIcon align-middle">
                                                    <img src="Assets/icons/new_order_hv.svg" alt="New order"
                                                        class="mb_nvSubIcn align-middle">
                                                    <span class="align-middle">New Order</span>
                                                </a>
                                            </li>
                                            <li><a class="nav-link nav_sub" aria-current="page"
                                                    href="newRequisition.php">
                                                    <img src="Assets/icons/new_req.svg" alt="Req"
                                                        class="navIcon align-middle">
                                                    <img src="Assets/icons/new_req_hv.svg" alt="Req"
                                                        class="mb_nvSubIcn align-middle">
                                                    <span class="align-middle">New Requisition</span></a>
                                            </li>
                                            <li><a class="nav-link nav_sub" aria-current="page"
                                                    href="javascript:void(0)">
                                                    <img src="Assets/icons/new_stock.svg" alt="Stock"
                                                        class="navIcon align-middle">
                                                    <img src="Assets/icons/new_stock_hv.svg" alt="Stock"
                                                        class="mb_nvSubIcn align-middle">
                                                    <span class="align-middle">New Stocktake</span></a>
                                            </li>
                                            <li><a class="nav-link nav_sub" aria-current="page"
                                                    href="javascript:void(0)">
                                                    <img src="Assets/icons/new_prod.svg" alt="Product"
                                                        class="navIcon align-middle">
                                                    <img src="Assets/icons/new_prod_hv.svg" alt="Product"
                                                        class="mb_nvSubIcn align-middle">
                                                    <span class="align-middle">New Production</span></a>
                                            </li>
                                            <li><a class="nav-link nav_sub" aria-current="page"
                                                    href="javascript:void(0)">
                                                    <img src="Assets/icons/new_payment.svg" alt="Payment"
                                                        class="navIcon align-middle">
                                                    <img src="Assets/icons/new_payment_hv.svg" alt="Payment"
                                                        class="mb_nvSubIcn align-middle">
                                                    <span class="align-middle">New Payment</span></a>
                                            </li>
                                            <li><a class="nav-link nav_sub" aria-current="page"
                                                    href="javascript:void(0)">
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
                                            <img src="Assets/icons/revenue_center_hv.svg" alt="Revenue"
                                                class="mb_navIcn">
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
                                <h1 class="h1">Edit Order</h1>
                            </div>
                            <div class="col-md-8 d-flex align-items-center justify-content-end">
                                <div class="mbPage">
                                    <div class="mb-nav" id="mb-nav">
                                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#navbarSupportedContent"
                                            aria-controls="navbarSupportedContent" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                        </button>
                                    </div>
                                    <div class="mbpg-name">
                                        <h1 class="h1">Edit Order</h1>
                                    </div>
                                </div>
                                <div class="user d-flex align-items-center">
                                    <img src="Assets/images/user.png" alt="user">
                                    <p class="body3 m-0 d-inline-block">User</p>
                                </div>
                                <div class="acc-info">
                                    <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
                                    <!-- <h1>Q</h1> -->
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

                    <section class="ordDetail">
                        <div class="tpBar-grn"></div>
                        <div class="stcPart">
                            <div class="container erdOrder nwOrder-Div">
                                <div class="row">
                                    <div class="sltSupp nwOrd-Num">
                                        <div class="ord-Box">
                                            <div class="ordNum">
                                                <h4 class="subTittle1"><span>Order#:</span> <span>332974</span></h4>
                                            </div>
                                            <div class="ordDate">
                                                <h4 class="subTittle1">23/05/2022</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ordInfo erdInfo newFeatures">
                                        <div class="container">
                                            <div class="mbFeature">
                                                <div class="row gx-3 justify-content-end">
                                                    <div class="col-md-4 text-center">
                                                        <div class="row featRow">
                                                            <div
                                                                class="col-md-6 ordFeature dropdown drpCurr brdLft position-relative">
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-toggle tabFet" role="button"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span class="currency"></span>
                                                                    <p class="btn2">Currency <i
                                                                            class="fa-solid fa-angle-down"></i></p>
                                                                </a>

                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)">Dollar</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)">Euro</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)">Tzs</a></li>
                                                                </ul>

                                                            </div>
                                                            <div class="col-md-6 ordFeature drpFee position-relative">
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-toggle tabFet" role="button"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <span class="fee"></span>
                                                                    <p class="btn2">Fee <i
                                                                            class="fa-solid fa-angle-down"></i>
                                                                    </p>
                                                                </a>

                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)">Fixed
                                                                            Fee</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)">Open
                                                                            Fee</a></li>
                                                                    <li><a class="dropdown-item"
                                                                            href="javascript:void(0)">Other</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 smBtn nwNxt-Btn">
                                        <div class="btnBg">
                                            <a href="receiveOrder.php" class="btn sub-btn"><span
                                                    class="align-middle">Update</span> <i
                                                    class="fa-solid fa-angle-right"></i></a>
                                        </div>
                                        <div class="btnBg mt-3">
                                            <a href="index.php" class="sub-btn std-btn">Back</a>
                                        </div>
                                        <div class="fetBtn">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container topOrder erdOrder">
                                <div class="row">
                                    <div class="sltSupp">
                                        <div class="input-group srchBx">
                                            <input type="search" class="form-control" placeholder="Search" id="srch"
                                                aria-label="Search">
                                            <div class="input-group-append">
                                                <button class="btn" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="ordInfo erdInfo">
                                        <div class="container">
                                            <div class="prcTable">
                                                <div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>Total</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>23,990 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>23,990 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price justify-content-between taxRow">
                                                    <div class="p-2 delIcn text-center">
                                                        <a href="javascript:void(0)">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                    <div class="p-2 txnmRow">
                                                        <p>VAT 19%</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>11.362 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>11.362 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price justify-content-between">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>Sub Total</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>23,990 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>23,990 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price justify-content-between grdTtl-Row">
                                                    <div class="p-2 delIcn text-center"></div>
                                                    <div class="p-2 txnmRow">
                                                        <p>Grand Total</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-2">
                                                            <p>23,990 $</p>
                                                        </div>
                                                        <div class="p-2 otherCurr">
                                                            <p>23,990 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-2 smBtn">
                                        <div class="btnBg">
                                            <a href="receiveOrder.php" class="btn sub-btn"><span
                                                    class="align-middle">Update</span> <i
                                                    class="fa-solid fa-angles-right"></i></a>
                                        </div>
                                        <div class="btnBg mt-3">
                                            <a href="index.php" class="sub-btn std-btn">Back</a>
                                        </div>
                                        <div class="fetBtn">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/dashboard.svg" alt="dashboard">
                                            </a>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="container nordPrice position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="prdtImg tb-head">
                                    <p>Image</p>
                                </div>
                                <div class="prdtCnt-Fst d-flex align-items-center">
                                    <div class="Itm-Name tb-head">
                                        <p>Item</p>
                                    </div>
                                    <div class="Itm-brCode tb-head">
                                        <p>Bar Code</p>
                                    </div>
                                    <div class="prdtCr-Unit d-flex align-items-center">
                                        <div class="crncy-Type d-flex align-items-center">
                                            <div class="dflt-Currency tb-head">
                                                <p>P.Price($)</p>
                                            </div>
                                            <div class="othr-Currency tb-head">
                                                <p>P.Price(€)</p>
                                            </div>
                                        </div>
                                        <div class="itm-Unit tb-head">
                                            <p>P.Unit</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="prdtStk-Qty tb-head">
                                    <p>S.Quantity</p>
                                </div>
                                <div class="prdtCnt-Scnd d-flex align-items-center">
                                    <div class="itm-Quantity tb-head">
                                        <p>Quantity</p>
                                    </div>
                                    <div class="ttlCr-Type d-flex align-items-center">
                                        <div class="ttlDft-Crcy tb-head">
                                            <p>Total($)</p>
                                        </div>
                                        <div class="ttlOtr-Crcy tb-head">
                                            <p>Total(€)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="prdt-Hide">
                                    <div class="prdt-Note tb-bdy">
                                        <div class="mb-brCode"></div>
                                        <p>Note</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>

                        <div id="boxscroll">
                            <div class="container cntTable">
                                <!-- Item Table Body Start -->
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                                <div class="newOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody newOrd-CntPrt">
                                        <div class="prdtImg tb-bdy">
                                            <img src="Assets/images/meat.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="prdtCnt-Fst d-flex align-items-center">
                                            <div class="Itm-Name tb-bdy">
                                                <p>Meat</p>
                                            </div>
                                            <div class="Itm-brCode tb-bdy">
                                                <p class="ord-brCode">9781462570123</p>
                                            </div>
                                            <div class="prdtCr-Unit d-flex">
                                                <div class="crncy-Type d-flex align-items-center">
                                                    <div class="dflt-Currency tb-bdy">
                                                        <p>2.6144 $</p>
                                                    </div>
                                                    <div class="othr-Currency tb-bdy">
                                                        <p>2.6144 €</p>
                                                    </div>
                                                </div>
                                                <div class="itm-Unit tb-bdy">
                                                    <p>Kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdtStk-Qty tb-bdy">
                                            <p class="ord-StockQty">10 <span class="tabOn-Stk">On stock</span></p>
                                        </div>
                                        <div class="prdtCnt-Scnd d-flex align-items-center">
                                            <div class="itm-Quantity tb-bdy">
                                                <input type="text" class="form-control qty-itm" placeholder="1">
                                            </div>
                                            <div class="ttlCr-Type d-flex align-items-center">
                                                <div class="ttlDft-Crcy tb-bdy">
                                                    <p>2.6144 $</p>
                                                </div>
                                                <div class="ttlOtr-Crcy tb-bdy">
                                                    <p>2.6144 €</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prdt-Hide">
                                            <div class="prdt-Note tb-bdy">
                                                <div class="mb-brCode"></div>
                                                <input type="text" class="form-control note-itm" placeholder="Note">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </section>


                </div>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>