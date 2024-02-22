<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Setup - Queue1</title>
    <link rel="icon" type="image/x-icon" href="../Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Assets/css/style.css">

</head>

<body class="mb-Bgbdy">

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <nav class="navbar d-flex flex-wrap align-items-stretch">
                        <div>
                            <div class="logo">
                                <h1><span>Q</span>ueue1.com</h1>
                                <div class="clsBar" id="clsBar">
                                    <a href="javascript:void(0)"><i class="fa-solid fa-arrow-left"></i></a>
                                </div>
                            </div>
                            <div class="nav-bar">
                                <ul class="nav flex-column h2">
                                    <li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="index_rtl.php"><img
                                                src="../Assets/icons/Bag.svg" alt="Bag" class="navIcon me-2 align-middle">
                                            <span class="align-middle">הזמנה חדשה</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="newRequisition_rtl.php"> <img
                                                src="../Assets/icons/Chat_alt_add.svg" alt="chat"
                                                class="navIcon me-2 align-middle"> <span class="align-middle">דרישה חדשה</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="runningTask_rtl.php"><img src="../Assets/icons/Desk_alt.svg"
                                                alt="Desk" class="navIcon me-2 align-middle"> <span
                                                class="align-middle">הפעלת משימות</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="history_rtl.php"><img src="../Assets/icons/Book_open.svg"
                                                alt="History" class="navIcon me-2 align-middle"> <span
                                                class="align-middle">הִיסטוֹרִיָה</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="stockView_rtl.php"><img src="../Assets/icons/Shop.svg"
                                                alt="Stock" class="navIcon me-2 align-middle"> <span
                                                class="align-middle">המניה</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="revenueCenter_rtl.php"><img src="../Assets/icons/Database.svg"
                                                alt="Revenue" class="navIcon me-2 align-middle"> <span
                                                class="align-middle">מרכזי הכנסות</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="setup_rtl.php"><img src="../Assets/icons/Setting_line.svg"
                                                alt="Setup" class="navIcon me-2 align-middle"> <span
                                                class="align-middle">להכין</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="nav-bar lgOut">
                            <ul class="nav flex-column h2">
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:void(0)"><img src="../Assets/icons/Sign_out_squre.svg"
                                            alt="Logout" class="navIcon me-2 align-middle"> <span class="align-middle">להתנתק</span></a>
                                </li>
                            </ul>
                        </div>
                </nav>
            </div>
            <div class="cntArea setupArea">
                <section class="usr-info stockUser">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">להכין</h1>
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
                                    <h1 class="h1">להכין</h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="../Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">מִשׁתַמֵשׁ</p>
                            </div>
                            <div class="acc-info">
                                <h1>Q</h1>
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> חֶשְׁבּוֹן</span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן 1</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן 2</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן 3</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן 4</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">לחוויה טובה יותר, השתמש בתצוגה לאורך.</h1>
                    </div>
                </section>

                <section class="ordDetail setupDetail">
                    <div class="container">
                        <div class="stupIcons">
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="../Assets/icons/setup-user.svg" alt="Setup User" class="stupImg">
                                        <p class="stUsr-para">משתמשים וחשבון</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="accountSetup_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/Account-setup.svg" alt="Account Setup">
                                            </div>
                                            <p class="sub-Text">הגדרת חשבון</p>
                                        </a>
                                        <a href="users_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-user.svg" alt="Setup User">
                                            </div>
                                            <p class="sub-Text">מִשׁתַמֵשׁ</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="../Assets/icons/setup-member.svg" alt="Setup Member" class="stupImg">
                                        <p class="stMem-para">חברים</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="manageSuppliers_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-supplier.svg" alt="Suppliers">
                                            </div>
                                            <p class="sub-Text">ספקים</p>
                                        </a>
                                        <a href="manageOutlets_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-outlet.svg" alt="Outlets">
                                            </div>
                                            <p class="sub-Text">שקעים</p>
                                        </a>
                                        <a href="revenueCenterSetup_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-revnue.svg" alt="Revenue Centers">
                                            </div>
                                            <p class="sub-Text">מרכזי הכנסות</p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/contactstup.svg" alt="Contact List">
                                            </div>
                                            <p class="sub-Text">רשימת אנשי קשר</p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-member.svg" alt="Employees">
                                            </div>
                                            <p class="sub-Text">עובדים </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="itemsManager_rtl.php" class="usrAcnt-Dtl usrItm-Manager">
                                        <img src="../Assets/icons/setup-itmmanager.svg" alt="Setup Manager"
                                            class="stupImg">
                                        <p class="stItm-para">מנהל פריטים</p>
                                    </a>

                                    <!-- <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a> -->
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="../Assets/icons/setup-storage.svg" alt="Setup Storage" class="stupImg">
                                        <p class="stStrge-para">הגדרת אחסון</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="physicalStorages_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-storage.svg" alt="Physical Storages">
                                            </div>
                                            <p class="sub-Text">אחסון פיזי</p>
                                        </a>
                                        <a href="manageDepartments_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-department.svg" alt="Departments Type">
                                            </div>
                                            <p class="sub-Text">סוג מחלקות</p>
                                        </a>
                                        <a href="categories_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-category.svg" alt="Categories">
                                            </div>
                                            <p class="sub-Text">קטגוריות</p>
                                        </a>
                                        <a href="manageUnits_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-unit.svg" alt="Units">
                                            </div>
                                            <p class="sub-Text">יחידות</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="stup-clm-2">
                                <div class="stup-UsrAcnt">
                                    <a href="javascript:void(0)" class="usrAcnt-Dtl">
                                        <img src="../Assets/icons/setup-finance.svg" alt="Setup Finance" class="stupImg">
                                        <p class="stFin-para">כספים</p>
                                    </a>

                                    <a href="javascript:void(0)" class="stupLink"><i
                                            class="fa-solid fa-angle-down"></i></a>
                                </div>
                                <div class="stup-SubMenu">
                                    <div class="triangle-down"></div>
                                    <div class="subMenu-Dtl">
                                        <a href="manageCurrency_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-currency.svg" alt="Currency">
                                            </div>
                                            <p class="sub-Text">מַטְבֵּעַ</p>
                                        </a>
                                        <a href="manageAccounts_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-account.svg" alt="Accounts">
                                            </div>
                                            <p class="sub-Text">חשבונות</p>
                                        </a>
                                        <a href="manageServiceFee_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-service.svg" alt="Service item">
                                            </div>
                                            <p class="sub-Text">פריט שירות</p>
                                        </a>
                                        <a href="manageAdditionalFee_rtl.php" class="sbMnu-acntStup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-additional.svg" alt="Additional Fees">
                                            </div>
                                            <p class="sub-Text">עמלות נוספות</p>
                                        </a>
                                        <a href="javascript:void(0)" class="sbMnu-acntStup disable-Stup">
                                            <div class="sub-Icns">
                                                <img src="../Assets/icons/setup-department.svg" alt="Payments Type">
                                            </div>
                                            <p class="sub-Text">סוג תשלומים</p>
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

    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>