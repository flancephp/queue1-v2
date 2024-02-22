<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Stock View - Queue1</title>
    <link rel="icon" type="image/x-icon" href="../Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Assets/css/style.css">

</head>

<body>

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
                                    <a class="nav-link active" href="stockView_rtl.php"><img src="../Assets/icons/Shop.svg"
                                            alt="Stock" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">המניה</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="revenueCenter_rtl.php"><img src="../Assets/icons/Database.svg"
                                            alt="Revenue" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">מרכזי הכנסות</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="setup_rtl.php"><img src="../Assets/icons/Setting_line.svg"
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
            <div class="cntArea">
                <section class="usr-info stockUser">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">תצוגת מלאי</h1>
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
                                    <h1 class="h1">תצוגת מלאי</h1>
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

                <section class="ordDetail stockView">

                    <div class="alrtMessage">
                        <div class="container">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><strong>שלום משתמש!</strong> הפריט שלך הוחלף בהצלחה.</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <p><strong>שלום משתמש!</strong> שגיאה בעת החלפת פריט.</p>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>

                    <div class="stkView">
                        <div class="container">
                            <div class="row">
                                <div class="storeCol">
                                    <div class="store text-center d-flex">

                                        <a href="javascript:void(0)" class="dskAll-str">
                                            <div class="allStr">
                                                <h2 class="h2">כל החנויות</h2>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="allStore">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">כל החנויות</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">אחסון משקאות</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">ירקות/יומן</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">ירקות/יומן</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">משק בית</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">יבש טוב</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">חֶלְבּוֹן</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0)" class="otrStock">
                                            <div class="othStr">
                                                <div class="storeCont">
                                                    <h2 class="h2">חֶלְבּוֹן</h2>
                                                    <hr>
                                                    <p class="body2">12,732 $</p>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                </div>
                                <!-- <div class="strRemcol"></div> -->
                                <div class="strfetCol text-center">
                                    <div class="row stkRow">
                                        <div class="col-md-6 stockFeat brdLft">
                                            <a href="javascript:void(0)" class="tabFet">
                                                <span class="prdItm"></span>
                                                <p class="btn2">תיצור פריט</p>
                                            </a>
                                        </div>

                                        <div class="col-md-6 stockFeat dropStk">
                                            <a href="javascript:void(0)" class="dropdown-toggle tabFet" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="edIt"></span>
                                                <p class="btn2 d-flex justify-content-center align-items-center">
                                                    <span>בדיקת מלאי</span> <i class="fa-solid fa-angle-down"></i>
                                                </p>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">בדיקת מלאי1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">בדיקת מלאי2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">אַחֵר</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stkSrcicn">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 mbCol-stk">
                                    <!-- Search Box Start -->
                                    <div class="input-group srchBx">
                                        <input type="text" class="form-control" placeholder="לחפש" id="srch">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Search Box End -->
                                    <!-- Feature Btn Start -->
                                    <div class="fetBtn">
                                        <a href="javascript:void(0)">
                                            <img src="../Assets/icons/dashboard.svg" alt="dashboard">
                                        </a>
                                    </div>

                                    <!-- Feature Btn End -->
                                </div>
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="../Assets/icons/chkColumn.svg" alt="Check Column">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="../Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="../Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <div class="hdfetCol text-center">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mb-srcBox">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Search Box Start -->
                            <div class="mbClone-src">

                            </div>
                            <!-- Search Box End -->

                            <!-- Feature Btn Start -->
                            <div class="filtBtn">
                                <a href="javascript:void(0)">
                                    <img src="../Assets/icons/filter.svg" alt="Filter">
                                </a>
                            </div>
                            <!-- Feature Btn End -->
                        </div>
                        <!-- Filter Part Mobile Start -->
                        <div class="filter-mb">

                        </div>
                        <!-- Filter Part Mobile End -->
                    </div>

                    <div class="container stkTblhead position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="mbShw d-flex align-items-center">
                                <div class="tb-bdy stkImgcol"></div>
                                <div class="stkNamcol d-flex align-items-center">
                                    <div class="tb-head stkItmclm">
                                        <div class="d-flex align-items-center">
                                            <p>פריט</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head stkQtyclm">
                                        <div class="d-flex align-items-center">
                                            <p>כַּמוּת</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="stkPrcol d-flex align-items-center">
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <p>אחרון <br>
                                            מחיר</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <p>המניה <br>
                                            מחיר</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head lstPrcol">
                                        <div class="d-flex align-items-center">
                                            <p>המניה <br>
                                            ערך</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mbHde align-items-center supData-Head">
                                <div class="tb-head supStkclm">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>קטגוריית משנה</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">קטגוריית משנה
                                                        1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">קטגוריית משנה
                                                        2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">קטגוריית משנה
                                                        3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">קטגוריית משנה
                                                        4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head supStkclm">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>ספק</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">ספק 1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">ספק 2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">ספק 3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">ספק 4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>
                    <!-- Item Table Body Start -->
                    <div id="boxscroll">
                        <div class="container cntTable">
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom itmBody">
                                <div class="mbShw d-flex align-items-center">
                                    <div class="tb-bdy stkImgcol">
                                        <img src="../Assets/images/Tomato.png" alt="Item" class="imgItm">
                                    </div>
                                    <div class="stkNamcol d-flex align-items-center">
                                        <div class="tb-bdy stkItmclm">
                                            <a href="itemHistoryView_rtl.php" class="itm-Profile">עגבנייה</a>
                                        </div>
                                        <div class="tb-bdy stkQtyclm stkQtybdy">
                                            <p>18 ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="stkPrcol d-flex align-items-center">
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Last">
                                            <p><span class="mbLst-value">אחרון</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Stock">
                                            <p><span class="mbLst-value">המניה</span> 2.2$</p>
                                        </div>
                                        <div class="tb-bdy lstPrcol stkPrcbdy mb-Value">
                                            <p><span class="mbLst-value">ערך</span> 17.2$</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mbHde align-items-center suppData-stk">
                                    <div class="tb-bdy supStkclm">
                                        <p>ירקות</p>
                                    </div>
                                    <div class="tb-bdy supStkclm">
                                        <p>איש חיזגי, טמרה מרקט</p>
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                    <!-- Item Table Body End -->





                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>