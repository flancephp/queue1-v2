<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Add Product - Queue1</title>
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
                            <h1 class="h1">Add Product</h1>
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

                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Item</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Barcode</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Status</label>
                                        </div>
                                        <div class="col-7 d-flex align-items-center gap-4">
                                            <span class="d-flex align-items-center gap-2">
                                                <input type="radio" name="Status" id="active" class="form-check-input m-0" checked>
                                                <label for="active" class="fs-13">Active</label>
                                            </span>

                                            <span class="d-flex align-items-center gap-2">
                                                <input type="radio" name="Status" id="inactive"  class="form-check-input m-0">
                                                <label for="inactive"  class="fs-13">Inactive</label>
                                            </span>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Item</label>
                                        </div>
                                        <div class="col-9  d-flex align-items-center gap-3">
                                           
                                                <span class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="Item" id="Normal" class="form-check-input m-0" checked>
                                                    <label for="Normal" class="fs-13">Normal</label>
                                                </span>
    
                                                <span class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="Item" id="Dividable"  class="form-check-input m-0">
                                                    <label for="Dividable"  class="fs-13">Dividable</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="Item" id="Raw"  class="form-check-input m-0">
                                                    <label for="Raw"  class="fs-13">Raw</label>
                                                </span>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Image</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="file" class="form-control d-none" id="fileupload">
                                            <label for="fileupload" class="sub-btn px-2 w-100 d-block">Click to upload your image</label>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Storage</label>
                                        </div>
                                        <div class="col-7">
                                            <select name="" id="" class="form-select">
                                                <option value="">Select</option>                                                    
                                                    <option value="5">Drinks Store </option>                                                    
                                                    <option value="10">Dry Goods Central Store</option>                                                    
                                                    <option value="11">Vegetables &amp; Diary </option>
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Parent Category</label>
                                        </div>
                                        <div class="col-7">
                                            <select name="" id="" class="form-select">
                                                <option value="">Select</option>
                                                <option value="1">
Food</option>
                                                <option value="4">
Beverage</option>
                                                <option value="6">
Operation Materials</option>
                                                <option value="7">
Fabric</option>
                                                <option value="8">
Utensils </option>
                                                <option value="21">
Cleaning Materials </option>
                                                <option value="26">
Ice Cream</option>
                                                <option value="177">
sss11</option>
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Subcategory</label>
                                        </div>
                                        <div class="col-7">
                                            <select name="" id="" class="form-select">
                                                <option value="">Select</option>
                                               
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Purchase unit</label>
                                        </div>
                                        <div class="col-7">
                                            <select class="form-select">
                                                <option value="">Select</option>
                                                                                                    <option value="1">
                                                    Bottle</option>

                                                                                                    <option value="2">
                                                    Crate</option>

                                                                                                    <option value="3">
                                                    Box</option>

                                                                                                    <option value="4">
                                                    Packet</option>

                                                                                                    <option value="5">
                                                    Pc</option>

                                                                                                    <option value="6">
                                                    Gallon</option>

                                                                                                    <option value="7">
                                                    Bag1</option>

                                                                                                    <option value="8">
                                                    Portion</option>

                                                                                                    <option value="9">
                                                    Tin</option>

                                                                                                    <option value="10">
                                                    KG</option>

                                                                                                    <option value="11">
                                                    Bunch</option>

                                                                                                    <option value="12">
                                                    Roll</option>

                                                                                                    <option value="13">
                                                    Litter</option>

                                                                                                    <option value="14">
                                                    Tray</option>

                                                                                                    <option value="15">
                                                    Glass</option>

                                                                                                    <option value="16">
                                                    Can</option>

                                                                                                    <option value="17">
                                                    TOT</option>

                                                                                                    <option value="18">
                                                    Set</option>

                                                                                                    <option value="19">
                                                    Container</option>

                                                                                                    <option value="40">
                                                    Bus</option>

                                                                                                    <option value="44">
                                                    d</option>

                                                                                                </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Factor</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Counting Unit</label>
                                        </div>
                                        <div class="col-7">
                                            <select name="" id="" class="form-select">
                                                <option value="">Select</option>
                                               
                                            </select>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">C.price($)</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        </div>
                                    </div>

                                    
                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Min level</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <div class="row align-items-center">
                                        <div class="col-3">
                                            <label for="Name" class="form-label">Max level</label>
                                        </div>
                                        <div class="col-7">
                                            <input type="text" class="form-control" id="" placeholder="">
                                        </div>
                                        </div>
                                    </div>


                                    </div>

                                    <div class="col-md-6">

                                     
                                        <div class="pb-4">
                                            <div class="row">
                                            <div class="col-3">
                                                <label for="Name" class="form-label">Supplier</label>
                                            </div>
                                            <div class="col-7">
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="semibold fs-13">Check all</label>
                                                </span>

                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Active Store</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Agora</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">AquaRT</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Azam Uhai-water (town)</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Baghdadi</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Coca-Cola ( Paje ) Zenji</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Endeavour Linen Limited</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Focus</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Frozen Italian Ice Cream</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Green Grocery</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Hamisa Shop</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Hotel Solutions</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">HotelSa FoodService</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Inaya Zanzibar</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Jad Hotel Supplies</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Jambiani One</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Joker</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Juma</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Kisere</label>
                                                </span>

                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Local Fish Market</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Local market</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Mahmoud ( Dubai )</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Mbero shop (Kibigiga)</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Mdungi Haji ( Paje )</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Michanvi Store</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Mushus Detergent</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Nyaime Liqueur Store</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Paje One</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Pawan kumar</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Puzzle coffee</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Qmb</label>
                                                </span>

                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Samora</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Shafa dairy</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Smith Hotel Supplies</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Suvacor Kahawa (Town)</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Tadey Chicken & Eggs</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Zalt</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Zan Aqua ( Town )</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Zanchick</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">ZanIce</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Zanzibar Coffee House</label>
                                                </span>
                                                <span class="d-flex align-items-center gap-2 pb-1">
                                                    <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                    <label for="" class="fs-13">Zmmi</label>
                                                </span>
                                                                                             
                                            </div>
                                            </div>
                                        </div>

                                        


                                        <div class="pb-4">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label for="Name" class="form-label">Department</label>
                                                </div>
                                                <div class="col-7">
                                                    <span class="d-flex align-items-center gap-2 pb-1">
                                                        <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                        <label for="" class="semibold fs-13">Check all</label>
                                                    </span>

                                                    <span class="d-flex align-items-center gap-2 pb-1">
                                                        <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                        <label for="" class="fs-13">Bar</label>
                                                    </span>

                                                    <span class="d-flex align-items-center gap-2 pb-1">
                                                        <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                        <label for="" class="fs-13">House Kepping</label>
                                                    </span>

                                                    <span class="d-flex align-items-center gap-2 pb-1">
                                                        <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                        <label for="" class="fs-13">Kitchen</label>
                                                    </span>
                                                    <span class="d-flex align-items-center gap-2 pb-1">
                                                        <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                        <label for="" class="fs-13">Operation</label>
                                                    </span>
                                                    <span class="d-flex align-items-center gap-2 pb-1">
                                                        <input type="checkbox" class="form-check-input m-0" id="" placeholder="">
                                                        <label for="" class="fs-13">Others</label>
                                                    </span>
                                                  
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                    <div class="usrBtns d-flex align-items-center justify-content-between">
                                        <div class="usrBk-Btn">
                                            <div class="btnBg">
                                                <a href="manageAdditionalFee.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span class="dsktp-Btn">Back</span></a>
                                            </div>
                                        </div>
                                        <div class="usrAd-Btn">
                                            <div class="btnBg">
                                                <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span class="dsktp-Btn">Save</span></button>
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