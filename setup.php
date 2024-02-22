<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Setup - Queue1</title>
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
                            <h1 class="h1">Setup</h1>
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
                                    <h1 class="h1">Setup</h1>
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

                <section class="ordDetail setupDetail">
                    <div class="container">
                        <div class="stupIcons">
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-user.svg" alt="Setup User" class="stupImg">
                                        <p class="stUsr-para">Users & Account</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="accountSetup.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/Account-setup.svg" alt="Account Setup">
                                            </div>
                                            <p class="sub-Text">Account Setup</p>
                                        </a>
                                        <a href="users.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-user.svg" alt="Setup User">
                                            </div>
                                            <p class="sub-Text">User</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-member.svg" alt="Setup Member" class="stupImg">
                                        <p class="stMem-para">Members</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="manageSuppliers.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-supplier.svg" alt="Suppliers">
                                            </div>
                                            <p class="sub-Text">Suppliers</p>
                                        </a>
                                        <a href="manageOutlets.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-outlet.svg" alt="Outlets">
                                            </div>
                                            <p class="sub-Text">Outlets</p>
                                        </a>
                                        <a href="revenueCenterSetup.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-revnue.svg" alt="Revenue Centers">
                                            </div>
                                            <p class="sub-Text">Revenue Centers</p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/contactstup.svg" alt="Contact List">
                                            </div>
                                            <p class="sub-Text">Contact List</p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-member.svg" alt="Employees">
                                            </div>
                                            <p class="sub-Text">Employees </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="itemsManager.php" class="usrAcnt-Dtl usrItm-Manager">
                                        <img src="Assets/icons/setup-itmmanager.svg" alt="Setup Manager"
                                            class="stupImg">
                                        <p class="stItm-para">Items Manager</p>
                                    </a>

                                    <!-- <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a> -->
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-storage.svg" alt="Setup Storage" class="stupImg">
                                        <p class="stStrge-para">Storage Setup</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="physicalStorages.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-storage.svg" alt="Physical Storages">
                                            </div>
                                            <p class="sub-Text">Physical Storages</p>
                                        </a>
                                        <a href="manageDepartments.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-department.svg" alt="Departments Type">
                                            </div>
                                            <p class="sub-Text">Departments Type</p>
                                        </a>
                                        <a href="categories.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-category.svg" alt="Categories">
                                            </div>
                                            <p class="sub-Text">Categories</p>
                                        </a>
                                        <a href="manageUnits.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-unit.svg" alt="Units">
                                            </div>
                                            <p class="sub-Text">Units</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-finance.svg" alt="Setup Finance" class="stupImg">
                                        <p class="stFin-para">Finances</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="manageCurrency.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-currency.svg" alt="Currency">
                                            </div>
                                            <p class="sub-Text">Currency</p>
                                        </a>
                                        <a href="manageAccounts.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-account.svg" alt="Accounts">
                                            </div>
                                            <p class="sub-Text">Accounts</p>
                                        </a>
                                        <a href="manageServiceFee.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-service.svg" alt="Service item">
                                            </div>
                                            <p class="sub-Text">Service item</p>
                                        </a>
                                        <a href="manageAdditionalFee.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-additional.svg" alt="Additional Fees">
                                            </div>
                                            <p class="sub-Text">Additional Fees</p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-department.svg" alt="Payments Type">
                                            </div>
                                            <p class="sub-Text">Payments Type</p>
                                        </a>
                                    </div>
                                </div>
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