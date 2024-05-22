<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Stock take - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>


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
                            <h1 class="h1">View Mobile Stock Take</h1>
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
                                    <h1 class="h1">Stock take</h1>
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

                <section class="ordDetail stockDetail">
                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="stockView.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-stkBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>
                           
                        </div>
                        <!-- Feature Btn End -->
                        <div class="col-md-12 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="chkStore">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#stocktake_pdf">
                                                <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>

                                    </div>
                                </div>

                        <div class="stktakeTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="stktakeTbl-Head align-items-center itmTable">
                                    <div class="stktake-Cnt d-flex align-items-center">
                                        <div class="tb-head stkno-Clm">
                                            <p>#</p>
                                        </div>
                                        <div class="tb-head stkMuser-Clm">
                                            <p>User</p>
                                        </div>
                                        <div class="tb-head stktime-Clm">
                                            <p>Stock take time</p>
                                        </div>
                                    </div>
                                    <div class="stktakeTbl-Icns">
                                        <div class="tb-head stktakeOpt-Clm text-center">
                                            <p>Options</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->

                                <!-- Table Body Start -->
                                <div class="stocktakeTask">
                                    <div class="stkTbl-body align-items-center itmBody">
                                        <div class="stkTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy stkNum-Clm">
                                                <p class="stockNumber"><span class="mb-stkSpan">No. </span>1</p>
                                            </div>
                                            <div class="tb-bdy stkName-Clm">
                                                <p class="MuserName">QDesign</p>
                                            </div>
                                            <div class="tb-bdy stkTtl-Clm">
                                                <p class="stocktaketime">07:05:24 AM-07:05:24 AM</p>
                                            </div>
                                        </div>
                                        <div class="stkTbl-Icns">
                                            <div class="tb-bdy stkOpt-Clm d-flex align-items-center">
                                                <a href="StockOverwrite.php" class="stockLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="stkLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="stockLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="stkLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="stocktakeTask">
                                    <div class="stkTbl-body align-items-center itmBody">
                                        <div class="stkTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy stkNum-Clm">
                                                <p class="stockNumber"><span class="mb-stkSpan">No. </span>2</p>
                                            </div>
                                            <div class="tb-bdy stkName-Clm">
                                                <p class="MuserName">QDesign</p>
                                            </div>
                                            <div class="tb-bdy stkTtl-Clm">
                                                <p class="stocktaketime">07:05:24 AM-07:05:24 AM</p>
                                            </div>
                                        </div>
                                        <div class="stkTbl-Icns">
                                            <div class="tb-bdy stkOpt-Clm d-flex align-items-center">
                                                <a href="StockOverwrite.php" class="stockLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="stkLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="stockLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="stkLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="stocktakeTask">
                                    <div class="stkTbl-body align-items-center itmBody">
                                        <div class="stkTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy stkNum-Clm">
                                                <p class="stockNumber"><span class="mb-stkSpan">No. </span>3</p>
                                            </div>
                                            <div class="tb-bdy stkName-Clm">
                                                <p class="MuserName">QDesign</p>
                                            </div>
                                            <div class="tb-bdy stkTtl-Clm">
                                                <p class="stocktaketime">07:05:24 AM-07:05:24 AM</p>
                                            </div>
                                        </div>
                                        <div class="stkTbl-Icns">
                                            <div class="tb-bdy stkOpt-Clm d-flex align-items-center">
                                                <a href="StockOverwrite.php" class="stockLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="stkLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="stockLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="stkLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="stocktakeTask">
                                    <div class="stkTbl-body align-items-center itmBody">
                                        <div class="stkTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy stkNum-Clm">
                                                <p class="stockNumber"><span class="mb-stkSpan">No. </span>4</p>
                                            </div>
                                            <div class="tb-bdy stkName-Clm">
                                                <p class="MuserName">QDesign</p>
                                            </div>
                                            <div class="tb-bdy stkTtl-Clm">
                                                <p class="stocktaketime">07:05:24 AM-07:05:24 AM</p>
                                            </div>
                                        </div>
                                        <div class="stkTbl-Icns">
                                            <div class="tb-bdy stkOpt-Clm d-flex align-items-center">
                                                <a href="StockOverwrite.php" class="stockLink">
                                                    <img src="Assets/icons/dots.svg" alt="Dots" class="stkLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="stockLink">
                                                    <img src="Assets/icons/delete.svg" alt="Delete" class="stkLnk-Img">
                                                </a>
                                            </div>
                                        </div>
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
    <!-- ===== Stock view pdf popup new in div format======= -->
    <div class="modal" tabindex="-1" id="stocktake_pdf" aria-labelledby="stock_pdfModal" aria-hidden="true">
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
                                                <input type="checkbox" checked="checked" name="issuedIn" class="form-check-input" value="2">
                                                <span class="fs-13">+Variances</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="defaultCurrency" class="form-check-input" value="3">
                                                <span class="fs-13">-Variances</span>
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
                                                <input type="checkbox" checked="checked" name="taskNo" class="form-check-input" value="2">
                                                <span class="fs-13">Photo</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="date" class="form-check-input" value="3">
                                                <span class="fs-13">Item</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="user" class="form-check-input" value="4">
                                                <span class="fs-13">Barcode</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="supInvoiceNo" class="form-check-input" value="5">
                                                <span class="fs-13">Stock Qty</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="type" class="form-check-input" value="6">
                                                <span class="fs-13">Stock take Qty</span>
                                            </li>
                                            <li>
                                                <input type="checkbox" checked="checked" name="referTo" class="form-check-input" value="7">
                                                <span class="fs-13">Variances</span>
                                            </li>
                                        </ul>
                                    </div>

                                    </div>
                                
                                </div>

                        

                        </div>
                        <a href="stocktakepdf.html" target=" " class="btn"><span class="align-middle">Press</span> <i class="fa-solid fa-download ps-1"></i></a>
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
                            <div class="col-sm-4">
                                <h6 class="semibold">Stock View</h6>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="semibold text-center">Drinks store</h6>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-end fs-15">07-05-2024</p>
                            </div>
                        </div>
                    </div>

                    <div class="summery-row">
                        <div class="row">
                            <div class="col-6 storesection pe-1">
                                <div class="modal-table fs-12 w-100 ta-c">
                                    <div class="table-row">
                                        <div class="table-cell">&nbsp;</div>
                                        <div class="table-cell medium">+Variance</div>
                                        
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell">Total</div>
                                        <div class="table-cell">+20</div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 storesection pe-6 ps-0">
                                <div class="modal-table fs-12 w-100 ta-c">
                                    <div class="table-row header-row">
                                        <div class="table-cell medium">-Variance</div>
                                    </div>
                                    <div class="table-row thead">
                                        <div class="table-cell negative-t">-10</div>
                                    </div>
                        </div>
                    </div>
                    

                    <div class="overflowTable"> 
                        <div class="modal-table fs-12 w-100 mt-4">
                            <div class="table-row thead">
                                <div class="table-cell">#</div>
                                <div class="table-cell">Photo</div>
                                <div class="table-cell">Item</div>
                                <div class="table-cell">Barcode</div>  
                                <div class="table-cell">Stock Qty</div>
                                <div class="table-cell">Stock take Qty</div>
                                <div class="table-cell">Variances</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">1</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple</div>
                                <div class="table-cell">777666</div>
                                <div class="table-cell">80 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell negative-t">-30 Kg</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">2</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple</div>
                                <div class="table-cell">777666</div>
                                <div class="table-cell">80 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell negative-t">-30 Kg</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">3</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple</div>
                                <div class="table-cell">777666</div>
                                <div class="table-cell">80 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">+30 Kg</div>
                            </div>
                            <div class="table-row">
                                <div class="table-cell">4</div>
                                <div class="table-cell table-cell-photo"><img src="Assets/images/Apple.png" alt="Outlet_Photo"></div>
                                <div class="table-cell">Apple</div>
                                <div class="table-cell">777666</div>
                                <div class="table-cell">80 Kg</div>
                                <div class="table-cell font-bold">50 Kg</div>
                                <div class="table-cell">+30 Kg</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- ===== Stock pdf popup new in div format======= -->
    

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>