<?php
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if (!isset($_SESSION['adminidusername'])) 
{
    echo "<script>window.location='login.php'</script>";    
}


//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);

if (!in_array('8',$checkPermission))
{
    echo "<script>window.location='index.php'</script>";
}

$accessAccountSetup = access_account_setup($_SESSION['designation_id'],$_SESSION['accountId']);

$accessDesignation = access_designation($_SESSION['designation_id'],$_SESSION['accountId']);

$accessUser = access_user($_SESSION['designation_id'],$_SESSION['accountId']);

$accessOutlet = access_outlet($_SESSION['designation_id'],$_SESSION['accountId']);

$accessSupplier = access_supplier($_SESSION['designation_id'],$_SESSION['accountId']);

$accessRevenueCenter = access_revenue_center($_SESSION['designation_id'],$_SESSION['accountId']);

$accessPhysicalStorage = access_physical_storage($_SESSION['designation_id'],$_SESSION['accountId']);

$accessDepartmentType = access_department_type($_SESSION['designation_id'],$_SESSION['accountId']);

$accessCategory = access_category($_SESSION['designation_id'],$_SESSION['accountId']);

$accessUnit = access_unit($_SESSION['designation_id'],$_SESSION['accountId']);

$accessItemManager = access_item_manager($_SESSION['designation_id'],$_SESSION['accountId']);

$accessCurrency = access_currency($_SESSION['designation_id'],$_SESSION['accountId']);

$accessAccount = access_account($_SESSION['designation_id'],$_SESSION['accountId']);

$accessServiceItem = access_service_item($_SESSION['designation_id'],$_SESSION['accountId']);

$accessAdditionalFee = access_additional_fee($_SESSION['designation_id'],$_SESSION['accountId']);

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">

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
    <link rel="stylesheet" href="Assets/css/style1.css">

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
                            <h1 class="h1"><?php echo showOtherLangText('Setup'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Setup'); ?></h1>
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

                <section class="ordDetail setupDetail">
                    <div class="container">
                        <div class="stupIcons">
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-user.svg" alt="Setup User" class="stupImg">
                                        <p class="stUsr-para"><?php echo showOtherLangText('Users & Account'); ?></p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="accountSetup.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/Account-setup.svg" alt="<?php echo showOtherLangText('Account Setup'); ?>">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Account Setup'); ?></p>
                                        </a>
                                        <a href="users.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-user.svg" alt="Setup User">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Users') ?></p>
                                        </a>
                                        <a href="listDesignation.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-service.svg" alt="Service item">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Title') ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-member.svg" alt="Setup Member" class="stupImg">
                                        <p class="stMem-para"><?php echo showOtherLangText('Members'); ?></p>
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
                                            <p class="sub-Text"><?php echo showOtherLangText('Suppliers') ?></p>
                                        </a>
                                        <a href="manageOutlets.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-outlet.svg" alt="Outlets">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Outlets') ?></p>
                                        </a>
                                        <a href="revenueCenterSetup.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-revnue.svg" alt="Revenue Centers">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Revenue Centers'); ?></p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/contactstup.svg" alt="Contact List">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Contact List'); ?></p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-member.svg" alt="Employees">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Employees'); ?> </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="itemsManager.php" class="usrAcnt-Dtl usrItm-Manager">
                                        <img src="Assets/icons/setup-itmmanager.svg" alt="Setup Manager"
                                            class="stupImg">
                                        <p class="stItm-para"><?php echo showOtherLangText('Items Manager'); ?></p>
                                    </a>

                                    <!-- <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a> -->
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-storage.svg" alt="Setup Storage" class="stupImg">
                                        <p class="stStrge-para"><?php echo showOtherLangText('Storage Setup'); ?></p>
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
                                            <p class="sub-Text"><?php echo showOtherLangText('Physical Storages'); ?></p>
                                        </a>
                                        <a href="manageDepartments.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-department.svg" alt="Departments Type">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Departments Type'); ?></p>
                                        </a>
                                        <a href="categories.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-category.svg" alt="Categories">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Categories'); ?></p>
                                        </a>
                                        <a href="manageUnits.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-unit.svg" alt="Units">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Units'); ?></p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="Assets/icons/setup-finance.svg" alt="Setup Finance" class="stupImg">
                                        <p class="stFin-para"><?php echo showOtherLangText('Finances'); ?></p>
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
                                            <p class="sub-Text"><?php echo showOtherLangText('Currency'); ?></p>
                                        </a>
                                        <a href="manageAccounts.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-account.svg" alt="Accounts">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Accounts'); ?></p>
                                        </a>
                                        <a href="manageServiceFee.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-service.svg" alt="Service item">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Service item'); ?></p>
                                        </a>
                                        <a href="manageAdditionalFee.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-additional.svg" alt="Additional Fees">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Additional Fees'); ?></p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="Assets/icons/setup-department.svg" alt="Payments Type">
                                            </div>
                                            <p class="sub-Text"><?php echo showOtherLangText('Payments Type'); ?></p>
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