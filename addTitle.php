<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Additional Fee - Queue1</title>
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
                            <h1 class="h1">Add title</h1>
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
                                    <h1 class="h1">Add title</h1>
                                </div>
                            </div>
                             <?php require_once('header.php'); ?>
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
                                    <a href="manageAdditionalFee.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                            class="dsktp-Btn">Save</span></button>
                                </div>
                            </div>
                        </div>

                        <div class="edtSup-Div w-100">
                            <form class="addUser-Form acntSetup-Form">

                                <div class="row align-items-center acntStp-Row w-50">
                                    <div class="col-md-2">
                                        <label for="Name" class="form-label">Name</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="designation_name" placeholder="">
                                    </div>
                                </div>


                                <div id="titletype">

                                    <div class="row align-items-center acntStp-Row w-50">
                                        <div class="col-md-2">
                                            <label for="Type" class="form-label d-block">Title Type</label>
                                        </div>
                                        <div class="col-md-10">
                                            <span class="d-inline-flex align-items-center gap-2">
                                                <input type="radio" name="type" class="form-check-input m-0" id="type_web" data-bs-toggle="collapse" data-bs-target="#web" checked>
                                                <label for="type_web" class="form-label mb-0">Web</label>
                                            </span>
                                            <span class="ps-3 d-inline-flex align-items-center gap-2">
                                                <input type="radio" name="type" class="form-check-input m-0" id="type_mobile" data-bs-toggle="collapse" data-bs-target="#mobile">
                                                <label  for="type_mobile" class="form-label mb-0">Mobile</label>
                                            </span>

                                        </div>
                                    </div>


                                    <div class="webItem pt-4 collapse " id="web"  data-bs-parent="#titletype">

                                        <div>
                                            <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#newOrder">
                                                <input type="checkbox" name="" class="form-check-input" id="new_order" value="1">
                                                <label class="medium" for="new_order">New order</label>
                                            </div>

                                            <div class="collapse py-4" id="newOrder">
                                                <p class="supplier-text bold pb-2">Supplier</p>
                                                <div class="supplier-text pb-3">
                                                    <input type="checkbox" class="supplierCheckall form-check-input" class="form-check-input" id="suppliercheckall">
                                                    <label for="suppliercheckall" class="fs-13 semibold">Check all</label>
                                                </div>


                                                <div class="row title-listing-item">

                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]" id="supplierCheckbox  form-check-input" class="supplierCheckbox  form-check-input" value="5">
                                                            <label>Mdungi Haji ( Paje )</label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]" id="supplierCheckbox  form-check-input" class="supplierCheckbox  form-check-input" value="9">
                                                            <label>
                                                                Coca-Cola ( Paje ) Zenji </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="10">
                                                            <label>
                                                                Qmb </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="11">
                                                            <label>
                                                                Zmmi </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="12">
                                                            <label>
                                                                Azam Uhai-water (town) </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="14">
                                                            <label>
                                                                Suvacor Kahawa (Town) </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="15">
                                                            <label>
                                                                Mbero shop (Kibigiga) </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="16">
                                                            <label>
                                                                Zan Aqua ( Town ) </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="17">
                                                            <label>
                                                                Paje One </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="18">
                                                            <label>
                                                                Local Fish Market </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="19">
                                                            <label>
                                                                Zanchick </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="20">
                                                            <label>
                                                                ZanIce </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="21">
                                                            <label>
                                                                Michanvi Store </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="22">
                                                            <label>
                                                                Inaya Zanzibar </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="23">
                                                            <label>
                                                                Focus </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="24">
                                                            <label>
                                                                Jambiani One </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="25">
                                                            <label>
                                                                Smith Hotel Supplies </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="26">
                                                            <label>
                                                                Mushus Detergent </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="27">
                                                            <label>
                                                                Green Grocery </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="28">
                                                            <label>
                                                                Puzzle coffee </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="29">
                                                            <label>
                                                                Tadey Chicken &amp; Eggs </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="30">
                                                            <label>
                                                                Jad Hotel Supplies </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="31">
                                                            <label>
                                                                Kisere </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="32">
                                                            <label>
                                                                Zalt </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="33">
                                                            <label>
                                                                AquaRT </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="34">
                                                            <label>
                                                                Baghdadi </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="35">
                                                            <label>
                                                                local market </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="36">
                                                            <label>
                                                                Hotel Solutions </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="37">
                                                            <label>
                                                                Active Store </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="38">
                                                            <label>
                                                                Shafa dairy </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="39">
                                                            <label>
                                                                Joker </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="78">
                                                            <label>
                                                                Mahmoud ( Dubai ) </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="79">
                                                            <label>
                                                                HotelSa FoodService </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="80">
                                                            <label>
                                                                Hamisa Shop </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="81">
                                                            <label>
                                                                Samora </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="116">
                                                            <label>
                                                                Zanzibar Coffee House </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="185">
                                                            <label>
                                                                Juma </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="187">
                                                            <label>
                                                                Agora </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="189">
                                                            <label>
                                                                Endeavour Linen Limited </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="191">
                                                            <label>
                                                                Frozen Italian Ice Cream </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="205">
                                                            <label>
                                                                Nyaime Liqueur Store </label>
                                                        </div>
                                                    </div>
                                                    <div class="supplier-main-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                        <div class="supplier-inner-listbox">
                                                            <input type="checkbox" name="new_supplierCheck[]"
                                                                id="supplierCheckbox  form-check-input"
                                                                class="supplierCheckbox  form-check-input" value="209">
                                                            <label>
                                                                Pawan kumar </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row  py-3 mt-2">
                                                    <div class="col-md-2 col-4"></div>
                                                    <div class="col-md-10 col-8">
                                                        <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="checkbox" class="form-check-input"
                                                                id="checkallOrder" name="check_all">
                                                            <label class="fs-13 semibold">Check all</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" class="form-check-input"
                                                                id="uncheckallOrder" name="uncheck_all">
                                                            <label class="fs-13 semibold">Uncheck all</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Access payment:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="access_payment"
                                                                class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="access_payment"
                                                                class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>


                                        <div>
                                            <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#newRequisition">
                                                <input type="checkbox" name="" class="form-check-input" id="new-requisition-section" value="1">
                                                <label class="medium" for="new-requisition-section">New Requisition</label>
                                            </div>
                                        
                                            <div class="collapse py-4" id="newRequisition">

                                                <p class="bold pb-2">Members</p>


                                                <div class="supplier-text pb-3">
                                                    <input type="checkbox" class="form-check-input" id="memberall">
                                                    <label for="memberall" class="fs-13 semibold">Check all</label>
                                                </div>

                                                <div class="row  title-listing-item">
                                                    <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6 ">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="4">
                                                    <label>Fun Kitchen</label>
                                                    </div>
                                                    </div>
                                                     <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="5">
                                                    <label> Nur Kitchen</label>
                                                    </div>
                                                    </div>
                                                    <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="7">
                                                    <label> Nur Bar</label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="8">
                                                    <label>
                                                        Home (Salim )                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="9">
                                                    <label>
                                                        Qambani Team                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="10">
                                                    <label>
                                                        Fun Team                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="11">
                                                    <label>
                                                        Nur Team                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="13">
                                                    <label>
                                                        Fun Bar                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="14">
                                                    <label>
                                                        Qambani Kitchen                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="15">
                                                    <label>
                                                        Qambani Bar                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="16">
                                                    <label>
                                                        Fun Office                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="17">
                                                    <label>
                                                        Nur Office                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="18">
                                                    <label>
                                                        Qambani Office                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="19">
                                                    <label>
                                                        Fun H.K                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="20">
                                                    <label>
                                                        Nur H.K                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="21">
                                                    <label>
                                                        Qambani H.K                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="22">
                                                    <label>
                                                        Bad Conditions123                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="23">
                                                    <label>
                                                        Casa Kitchen                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="24">
                                                    <label>
                                                        Casa Bar                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="25">
                                                    <label>
                                                        Casa Office                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="26">
                                                    <label>
                                                        Casa H.K.                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="27">
                                                    <label>
                                                        Casa Team                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="28">
                                                    <label>
                                                        Michanvi Store                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="29">
                                                    <label>
                                                        Central Laundry                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="30">
                                                    <label>
                                                        Fun STORE                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="31">
                                                    <label>
                                                        Fun Shop                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="175">
                                                    <label>
                                                        Fun Canteen                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="176">
                                                    <label>
                                                        Casa &amp; Nur Canteen                                                                     </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="218">
                                                    <label>
                                                        Bakery                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="221">
                                                    <label>
                                                        GM bar                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="223">
                                                    <label>
                                                        GM kitchen                                                                    </label>
                                                    </div>
                                                    </div>
                                                                                                        <div class="member-main-listbox  col-xl-3 col-md-4 col-sm-6 col-6">
                                                    <div class="member-inner-listbox">
                                                    <input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox form-check-input" value="227">
                                                    <label>
                                                        FunKitchentest1                                                                    </label>
                                                    </div>
                                                    </div>

                                                    

                                                    </div>


                                                <div class="row pb-2  py-3 mt-2">
                                                    <div class="col-md-2 col-4"></div>
                                                    <div class="col-md-10 col-8">
                                                        <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="checkallRunningtask" name="check_all" checked>
                                                            <label class="fs-13 semibold">Check all</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="uncheckallRunningtask" name="uncheck_all">
                                                            <label class="fs-13 semibold">Uncheck all</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Access invoice:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="access_invoice" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="access_invoice" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Extra charges:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="extra_charges" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="extra_charges" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Item price:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="item_price" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="item_price" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Available quantity:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="avl_qty" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="avl_qty" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                            </div>
                                        
                                        </div>




                                        <div>
                                            <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#runningtasks">
                                                <input type="checkbox"  class="form-check-input" id="running_tasks" value="1">
                                                <label class="medium" for="running_tasks">Running tasks</label>
                                            </div>
                                            <div class="collapse py-4" id="runningtasks">


                                                <div class="row pb-2">
                                                    <div class="col-md-2 col-4"></div>
                                                    <div class="col-md-10 col-8">
                                                        <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="checkallRunningtask" name="check_all" checked>
                                                            <label class="fs-13 semibold">Check all</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="uncheckallRunningtask" name="uncheck_all">
                                                            <label class="fs-13 semibold">Uncheck all</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Edit order:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="edit_order" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="edit_order" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Edit requisition:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="edit_requisition" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="edit_requisition" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Receive order:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="receive_order" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="receive_order" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Issue out:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="issue_out" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="issue_out" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Assign to mobile:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="assign_mobile" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="assign_mobile" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Access delete function:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="access_function" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="access_function" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                            </div>
                                        
                                        
                                        </div>


                                        <div>
                                            <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#history">
                                                <input type="checkbox"  class="form-check-input" id="t_history" value="1">
                                                <label class="medium" for="t_history">History</label>
                                            </div>
                                            <div class="collapse py-4" id="history">


                                                <div class="row pb-2">
                                                    <div class="col-md-2 col-4"></div>
                                                    <div class="col-md-10 col-8">
                                                        <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="checkallhistory" name="check_all" checked>
                                                            <label class="fs-13 semibold">Check all</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="uncheckallhistory" name="uncheck_all">
                                                            <label class="fs-13 semibold">Uncheck all</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Access xcl/pdf file:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="access_xcl_pdf" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="access_xcl_pdf" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Access delete function:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="access_delete" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="access_delete" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Access accounts detail:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="access_account" class="form-check-input" value="1" checked>
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="access_account" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#stock">
                                                    <input type="checkbox"  class="form-check-input" id="stock1" value="1">
                                                    <label class="medium" for="stock1">Stock</label>
                                                </div>
                                                <div class="collapse py-4" id="stock">

                                                    <p class="supplier-text bold pb-2">Store access:</p>

                                                    <div class="row title-listing-item">
                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section form-check-input" value="5">
                                                            <label >Drinks Store</label>
                                                        </div>

                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section  form-check-input" value="10">
                                                            <label>Dry Goods Central Store </label>
                                                        </div>

                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section  form-check-input" value="11">
                                                            <label style="margin-right: 25px;">Vegetables &amp; Diary </label>
                                                        </div>
                                                        
                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section  form-check-input" value="12">
                                                            <label style="margin-right: 25px;">H.K Storage </label>
                                                        </div>
                                                        
                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section  form-check-input" value="13">
                                                            <label style="margin-right: 25px;">Protein </label>
                                                        </div>

                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section  form-check-input" value="14">
                                                            <label style="margin-right: 25px;">Michanvi Store</label>
                                                        </div>
                                                        
                                                        <div class="strAcs-inner-listbox col-xl-3 col-md-4 col-sm-6 col-6">
                                                            <input type="checkbox" name="store_check[]" id="stock-section" class="stock-section  form-check-input" value="15">
                                                            <label style="margin-right: 25px;">Swimming Pool Utensils</label>
                                                        </div>




                                                    </div>
    
    
                                                    <div class="row pb-2 mt-2  py-3 ">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="checkallstock1" name="check_all" checked>
                                                                <label class="fs-13 semibold">Check all</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="uncheckallstock1" name="uncheck_all">
                                                                <label class="fs-13 semibold">Uncheck all</label>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Convert raw items:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="convert_raw" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="convert_raw" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">View stock take:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="view_stock" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="view_stock" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Import stock take:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="import_stock" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="import_stock" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    


                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Access xcl/pdf file:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="acess_xcl" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="acess_xcl" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                </div>                                        
                                        
                                            </div>



                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#newstock">
                                                    <input type="checkbox"  class="form-check-input" id="new_stock" value="1">
                                                    <label class="medium" for="new_stock">New Stock</label>
                                                </div>
                                                <div class="collapse py-4" id="newstock">
    
    
                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="checkallstock" name="check_all" checked>
                                                                <label class="fs-13 semibold">Check all</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="uncheckallstock" name="uncheck_all">
                                                                <label class="fs-13 semibold">Uncheck all</label>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Edit stocktake:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="edit_stock" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="edit_stock" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Delete stocktake:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="delete_stock" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="delete_stock" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                </div>                                        
                                        
                                            </div>


                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#revenuecenter">
                                                    <input type="checkbox"  class="form-check-input" id="revenue_center" value="1">
                                                    <label class="medium" for="revenue_center">Revenue center</label>
                                                </div>
                                                <div class="collapse py-4" id="revenuecenter">
    
    
                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="checkallstock" name="check_all" checked>
                                                                <label class="fs-13 semibold">Check all</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="uncheckallstock" name="uncheck_all">
                                                                <label class="fs-13 semibold">Uncheck all</label>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Enter guest no:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="enter_guest" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="enter_guest" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Get ezee data:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="get_ezee" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="get_ezee" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Reimport data:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="reimport" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="reimport" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                </div>                                        
                                        
                                            </div>


                                            <div>
                                                <div class="checkbox-list border-bottom pt-3 pb-2" data-bs-toggle="collapse" data-bs-target="#setup">
                                                    <input type="checkbox"  class="form-check-input" id="setup1" value="1">
                                                    <label class="medium" for="setup1">Setup</label>
                                                </div>
                                                <div class="collapse py-4" id="setup">
    
    
                                                    <div class="row pb-2">
                                                        <div class="col-md-2 col-4"></div>
                                                        <div class="col-md-10 col-8">
                                                            <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="checkallstock" name="check_all" checked>
                                                                <label class="fs-13 semibold">Check all</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" class="form-check-input" id="uncheckallstock" name="uncheck_all">
                                                                <label class="fs-13 semibold">Uncheck all</label>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Account setup:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="account" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="account" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    
    
                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Title:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="title" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="title" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Users:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="users" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="users" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Outlets:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="outlets" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="outlets" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Suppliers:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="suppliers" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="suppliers" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Revenue centers:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="revenuecenters" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="revenuecenters" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
    

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Physical storages:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="physicalstorages" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="physicalstorages" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Departments type:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="departmentstype" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="departmentstype" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Categories:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="categories" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="categories" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Units:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="units" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="units" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Items manager:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="itemsmanager" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="itemsmanager" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Currency:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="currency" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="currency" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Accounts:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="accounts" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="accounts" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Service item:</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="serviceitem" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="serviceitem" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center pb-2">
                                                        <div class="col-md-2 col-4"><p class="semibold fs-13">Additional fees</p></div>
                                                        <div class="col-md-10 col-8">
                                                        
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div>
                                                                <input type="radio" name="additionalfees" class="form-check-input" value="1" checked>
                                                                <label class="fs-13">Enable</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" name="additionalfees" class="form-check-input" value="0">
                                                                <label class="fs-13">Disable</label>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>









    
    
                                                </div>                                        
                                        
                                            </div>




                                    </div>


                                    </div>

                                    <div class="collapse mobileItem" id="mobile"  data-bs-parent="#titletype">

                                        <div>
                                            <div class="checkbox-list border-bottom pt-3 pb-2" >
                                                <input type="checkbox" class="form-check-input" id="revenue_center" value="1">
                                                <label class="medium" for="revenue_center">Mobile section</label>
                                            </div>
                                            <div class="py-4" >
                                                <div class="row pb-2">
                                                    <div class="col-md-2 col-4"></div>
                                                    <div class="col-md-10 col-8">
                                                        <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="checkallstock" name="check_all" checked="">
                                                            <label class="fs-13 semibold">Check all</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" class="form-check-input" id="uncheckallstock" name="uncheck_all">
                                                            <label class="fs-13 semibold">Uncheck all</label>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Storage stocktaking:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="storage" class="form-check-input" value="1" checked="">
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="storage" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Outlet stocktaking:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="outlet" class="form-check-input" value="1" checked="">
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="outlet" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Receiving order:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="rec_order" class="form-check-input" value="1" checked="">
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="rec_order" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Issuing out:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="issuing_out" class="form-check-input" value="1" checked="">
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="issuing_out" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center pb-2">
                                                    <div class="col-md-2 col-4"><p class="semibold fs-13">Bar control sales:</p></div>
                                                    <div class="col-md-10 col-8">
                                                    
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div>
                                                            <input type="radio" name="bar_control" class="form-check-input" value="1" checked="">
                                                            <label class="fs-13">Enable</label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="bar_control" class="form-check-input" value="0">
                                                            <label class="fs-13">Disable</label>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>


                                            </div>                                        
                                    
                                        </div>


                                    </div>


                            </form>
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