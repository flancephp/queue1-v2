<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Outlet - Queue1</title>
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
                            <h1 class="h1">Add Outlet</h1>
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
                                    <h1 class="h1">Add Outlet</h1>
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
                        <div class="row">
                            <div class="col-md-6 bkOutlet-Btn">
                                <div class="btnBg">
                                    <a href="manageOutlets.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>
                            <div class="col-md-6 addOutlet-Btn">
                                <div class="itmLnk-Row">
                                    <div class="btnBg">
                                        <a href="javascript:void(0)" class="sub-btn std-btn mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-solid fa-plus"></i>
                                                <span class="nstdSpan">Item</span></span> <span class="dsktp-Btn">Add
                                                Item</span></a>
                                    </div>
                                    <div class="btnBg">
                                        <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                                class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                                class="dsktp-Btn">Save</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container itmMng-Src outletFrm">
                        <div class="row">
                            <div class="col-md-8 oltCol-8">
                                <div class="acntStp">
                                    <form class="addUser-Form acntSetup-Form row">
                                        <div class="acnt-Div nmOutlet">
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Name" class="form-label">Name</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="oltName"
                                                        placeholder="Casa Kitchen">
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row">
                                                <div class="col-md-4">
                                                    <label for="Department" class="form-label">Department</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="cstmSelect">
                                                        <select class="form-select selectOption"
                                                            aria-label="Default select example" id="selectDept">
                                                            <option selected>Select Department</option>
                                                            <option value="1">Kitchen</option>
                                                            <option value="2">Bar</option>
                                                            <option value="3">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="receiveInvoice" class="form-label">Receive
                                                        Invoice</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="recInvoice">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="acntLg-Upld setOutlet">
                                            <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                <div class="col-md-4">
                                                    <label for="setOutlet" class="form-label">Set as Outlet</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="setOtlt" checked>
                                                </div>
                                            </div>
                                            <div class="outletChk">
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="revenueCenter" class="form-label">Revenue
                                                            Center</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                            <select class="form-select selectOption"
                                                                aria-label="Default select example" id="selectRvcntr">
                                                                <option selected>Select Revenue Center</option>
                                                                <option value="1">Casa</option>
                                                                <option value="2">Nur</option>
                                                                <option value="3">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row">
                                                    <div class="col-md-4">
                                                        <label for="outletType" class="form-label">Outlet Type</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="cstmSelect">
                                                            <select class="form-select selectOption"
                                                                aria-label="Default select example" id="selectOtltp">
                                                                <option selected>Select Outlet Type</option>
                                                                <option value="1">Sales</option>
                                                                <option value="2">Sales2</option>
                                                                <option value="3">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center acntStp-Row chkOlt-Row">
                                                    <div class="col-md-4">
                                                        <label for="asgnEzcat" class="form-label">Assign Ezze
                                                            Category</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="asgnEzcat" checked>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4 oltCol-4"></div>
                        </div>
                    </div>
                    <div class="container">
                        <p class="subTittle1 flowItm">Follow Items List</p>
                    </div>
                    <div class="container outlet-Tblhead position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="tb-head imgOlt-Clm">
                                <p>Photo</p>
                            </div>
                            <div class="align-items-center shItmOlt-Clm">
                                <div class="tb-head itmOlt-Clm">
                                    <p>Item</p>
                                </div>
                                <div class="tb-head brCdOlt-Clm">
                                    <p>Bar Code</p>
                                </div>
                                <div class="tb-head typOlt-Clm">
                                    <p>Type</p>
                                </div>
                            </div>
                            <div class="align-items-center unitOlt-Clm">
                                <div class="tb-head sbUnOlt-Clm">
                                    <p>Sub Unit</p>
                                </div>
                                <div class="tb-head fctOlt-Clm">
                                    <p>Factor</p>
                                </div>
                                <div class="tb-head minOlt-Clm">
                                    <p>Min Qyt</p>
                                </div>
                                <div class="tb-head maxOlt-Clm">
                                    <p>Max Qyt</p>
                                </div>
                            </div>
                            <div class="icnOlt-Clm">
                                &nbsp;
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>

                    <!-- Item Table Body Start -->
                    <div id="boxscroll">
                        <div class="container cntTable adOtlTable">
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p>9784456667897</p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p>Usage</p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> Pkt</p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span> 1</p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span> 1</p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span> 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
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
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p>9784456667897</p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p>Usage</p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> Pkt</p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span> 1</p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span> 1</p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span> 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
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
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p>9784456667897</p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p>Usage</p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> Pkt</p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span> 1</p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span> 1</p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span> 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
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
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p>9784456667897</p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p>Usage</p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> Pkt</p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span> 1</p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span> 1</p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span> 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
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
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p>9784456667897</p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p>Usage</p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> Pkt</p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span> 1</p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span> 1</p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span> 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
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
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody outletBdy-Task">
                                <div class="itmTask-Mng oltTsk-Dtl align-items-center">
                                    <div class="tb-bdy imgOlt-Clm">
                                        <img src="Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="align-items-center shItmOlt-Clm">
                                        <div class="tb-bdy itmOlt-Clm">
                                            <p>Tomato</p>
                                        </div>
                                        <div class="tb-bdy brCdOlt-Clm">
                                            <p>9784456667897</p>
                                        </div>
                                        <div class="tb-bdy typOlt-Clm">
                                            <p>Usage</p>
                                        </div>
                                    </div>
                                    <div class="unitOlt-Clm">
                                        <div class="d-flex align-items-center flex-wrap mblFlx-Olt">
                                            <div class="tb-bdy sbUnOlt-Clm">
                                                <p><span class="mblOlt-head">Sub Unit</span> Pkt</p>
                                            </div>
                                            <div class="tb-bdy fctOlt-Clm">
                                                <p><span class="mblOlt-head">Factor</span> 1</p>
                                            </div>
                                            <div class="tb-bdy minOlt-Clm">
                                                <p><span class="mblOlt-head">Min Qyt</span> 1</p>
                                            </div>
                                            <div class="tb-bdy maxOlt-Clm">
                                                <p><span class="mblOlt-head">Max Qyt</span> 1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="icnOlt-Clm">
                                        <div class="tb-bdy d-flex align-items-center justify-content-end">
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
                                    <a href="javascript:void(0)" class="statusLink mb-oltLnk"><i
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