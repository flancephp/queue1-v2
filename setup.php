<?php 
include('inc/dbConfig.php'); //connection details


if(!isset($_SESSION['adminidusername']))
{
echo "<script>window.location='login.php'</script>";
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);


if (!in_array('3',$checkPermission))
{
echo "<script>window.location='index.php'</script>";
}
?>
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
                <?php require_once('nav.php');?>
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
                                        <a href="listDesignation.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-service.svg" alt="Service item">
                                            </div>
                                            <p class="sub-Text">Title</p>
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