<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>History - Queue1</title>
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
                                    <a class="nav-link active" href="history_rtl.php"><img src="../Assets/icons/Book_open.svg"
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
                <section class="usr-info history-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">הִיסטוֹרִיָה</h1>
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
                                    <h1 class="h1">הִיסטוֹרִיָה</h1>
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

                <section class="hisParent-sec">
                    <section class="ordDetail hisTory">

                    <div class="alrtMessage">
                            <div class="container">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <p><strong>שלום משתמש!</strong> ההקלטה נמחקה בהצלחה.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>

                                <!-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Hello User!</strong> You should check your order carefully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div> -->
                            </div>
                        </div>

                        <div class="container hisData">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                            <!-- Filter Btn Start -->
                                            <div class="his-filtBtn">
                                                <a href="javascript:void(0)" class="head-Filter">
                                                    <img src="../Assets/icons/filter.svg" alt="Filter">
                                                </a>
                                            </div>
                                            <!-- Filter Btn End -->
                                        </div>
                                        <!-- Date Box Start -->
                                        <div class="prtDate">
                                            <div class="hstDate">
                                                <input type="text" size="10" class="datepicker" placeholder="15/01/2023"
                                                    name="fromDate" autocomplete="off" value="">
                                                <span>-</span>
                                                <input type="text" size="10" class="datepicker" placeholder="15/02/2023"
                                                    name="fromDate" autocomplete="off" value="">
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i
                                                        class="fa-solid fa-arrows-rotate"></i></a>
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                            </div>
                                        </div>
                                        <!-- Date Box End -->
                                    </div>

                                </div>
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="../Assets/icons/history-stock.svg" alt="history stock">
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
                            </div>
                        </div>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->

                        <div class="container mb-hisDtl">
                            <div class="row">
                                <div class="col-md-5 is-Incol">
                                    <p class="rd-In">הנפק ב</p>
                                    <p class="ttlAmount">4,322.05 $</p>
                                </div>
                                <div class="col-md-5 is-Outcol">
                                    <p class="gr-Out">הוצאת</p>
                                    <p class="ttlAmount-rec">3,998.06 $</p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="../Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="container detailPrice">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl">
                                    <div class="issueIn">
                                        <div class="dspBlk">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">שולם</p>
                                                    <p class="pendStatus">ממתין ל</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="rd-In">הנפק ב</p>
                                                    <p class="ttlAmount">4,322.05 $</p>
                                                    <p class="pdAmount">2,718.05 $</p>
                                                    <p class="pendAmount">1,604 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="usdCurr text-center">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">שולם</p>
                                                    <p class="pendStatus">ממתין ל</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="usd-In">Usd</p>
                                                    <p class="ttlAmount">318.50 $</p>
                                                    <p class="pdAmount">318.50 $</p>
                                                    <p class="pendAmount">-</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="otrCurr text-center">
                                            <div class="paidIsue d-flex">
                                                <div class="col-md-3">
                                                    <p class="pdStatus">שולם</p>
                                                    <p class="pendStatus">ממתין ל</p>
                                                </div>
                                                <div class="col-md-9 text-center">
                                                    <p class="otr-In">Tzs</p>
                                                    <p class="ttlAmount">9,209,200 Tzs</p>
                                                    <p class="pdAmount">5,520,000 Tzs</p>
                                                    <p class="pendAmount">3,689,200 Tzs</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="issueOut">
                                        <div class="recIsue d-flex">
                                            <div class="col-md-5">
                                                <p class="recStatus">קיבלו</p>
                                                <p class="pendStatus">ממתין ל</p>
                                            </div>
                                            <div class="col-md-7 text-center">
                                                <p class="gr-Out">להוציא</p>
                                                <p class="ttlAmount-rec">3,998.06 $</p>
                                                <p class="pdAmount-rec">2,992.30 $</p>
                                                <p class="pendAmount-rec">1,005.76 $</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Variance text-center">
                                        <p class="varDtl">שונות</p>
                                        <p class="varValue">13 $</p>
                                        <p class="varDif">-123 $</p>
                                    </div>
                                </div>
                                <div class="accntDtl">
                                    <p class="accHead text-center">חשבונות</p>
                                    <div class="d-flex">
                                        <div class="bnkName">
                                            <p>בטוח 02 $</p>
                                            <p>בנק סטנדרד $</p>
                                        </div>
                                        <div class="bnkBalance">
                                            <p class="negBlnc">-123 $</p>
                                            <p class="posBlnc">23,990 $</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container position-relative hstTbl-head">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="numRef align-items-center">
                                    <div class="tb-head srHisclm">&nbsp;</div>
                                    <div class="tb-head numItmclm">
                                        <div class="d-flex align-items-center">
                                            <p>מספר</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="tb-head hisDateclm">
                                        <p class="date-txt">תַאֲרִיך</p>
                                        <div class="align-items-center date-Drpdwn">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>תַאֲרִיך</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">תאריך הגשה</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">הסדר תאריך</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">תאריך תשלום</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head hisTypclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>סוּג</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סוּג
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סוּג
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סוּג
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סוּג
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head hisRefrclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>מתייחס</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item isuIn-grReq"
                                                            href="javascript:void(0)">דְרִישָׁה</a>
                                                    </li>
                                                    <li><a class="dropdown-item isuOut-rdSup"
                                                            href="javascript:void(0)">להזמין</a>
                                                    </li>
                                                    <li><a class="dropdown-item stockTake-pr"
                                                            href="javascript:void(0)">קח מלאי</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head hisValclm">
                                    <div class="d-flex align-items-center">
                                        <p>ערך</p>
                                        <span class="dblArrow">
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-up"></i></a>
                                            <a href="javascript:void(0)" class="d-block aglStock"><i
                                                    class="fa-solid fa-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="stsHiscol d-flex align-items-center">
                                    <div class="tb-head hisStatusclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>סטָטוּס</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סטָטוּס
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סטָטוּס
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סטָטוּס
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">סטָטוּס
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head hisAcntclm">
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>חֶשְׁבּוֹן</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">חֶשְׁבּוֹן
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tb-head shrtHisclm">
                                    <div class="tab-mbFltr">
                                        <a href="javascript:void(0)" class="tab-lnkFltr"><i
                                                class="fa-solid fa-arrow-left"></i></a>
                                    </div>
                                    <div class="tab-Filttxt">
                                        <p>לְסַנֵן</p>
                                    </div>
                                    <a href="javascript:void(0)">
                                        <img src="../Assets/icons/chkColumn.svg" alt="Check Column">
                                    </a>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>
                    </section>

                    <section class="hisTblbody">
                        <div id="boxscroll">
                            <div class="container position-relative hstTbl-bd">
                                <!-- Item Table Body Start -->
                                <div class="hisTask">
                                    <div class="mb-hstryBareq">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>1</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo">לא. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisReq-typ">
                                                    <div class="reqBar-gr">&nbsp;</div>
                                                    <p>דְרִישָׁה</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">חבר</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-pendStatus">ממתין ל</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">בטוח 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>חֶשְׁבּוֹן</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">חשבונית</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">מסמכים <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarord">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>2</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo  hide-hisNo">לא. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisOrd-typ">
                                                    <div class="ordBar-rd">&nbsp;</div>
                                                    <p>להזמין</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">ספק</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr">357,900 Tzs</p>
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-pendStatus">ממתין ל</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">בטוח 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>חֶשְׁבּוֹן</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">לְשַׁלֵם</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">מסמכים <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarord">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>3</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo hide-hisNo">לא. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisOrd-typ">
                                                    <div class="ordBar-rd">&nbsp;</div>
                                                    <p>להזמין</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">ספק</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-paidStatus">שולם</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">בטוח 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>חֶשְׁבּוֹן</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">לְשַׁלֵם</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">מסמכים <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBarstk">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>4</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo  hide-hisNo">לא. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisStk-typ">
                                                    <div class="stckBar-bl">&nbsp;</div>
                                                    <p>קח מלאי</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">אִחסוּן</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <p class="othrValcurr-ngive">-144.30 $</p>
                                        </div>
                                        <div class="tb-bdy shrtHisclm stkCol-blnk">
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" class="runLink">
                                                        <span class="vwDtl"></span>
                                                    </a>
                                                </div>
                                                <div
                                                    class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" class="runLink">
                                                        <span class="dlTe"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                                <div class="hisTask mt-2">
                                    <div class="mb-hstryBareq">&nbsp;</div>
                                    <div class="align-items-center itmBody">
                                        <div class="numRef align-items-center">
                                            <div class="tb-bdy srHisclm">
                                                <p>5</p>
                                            </div>
                                            <div class="tb-bdy numItmclm">
                                                <p class="hisNo hide-hisNo">לא. V0002349</p>
                                                <p class="hisOrd">#10221855</p>
                                            </div>
                                            <div class="tb-bdy hisDateclm">
                                                <p class="fstDt">23.05.22</p>
                                                <p class="lstDt">23.05.22</p>
                                            </div>
                                            <div class="tb-bdy hisTypclm">
                                                <div class="d-flex align-items-center hisReq-typ">
                                                    <div class="reqBar-gr">&nbsp;</div>
                                                    <p>דְרִישָׁה</p>
                                                </div>
                                            </div>
                                            <div class="tb-bdy hisRefrclm">
                                                <p class="refTomember">חבר</p>
                                            </div>
                                        </div>
                                        <div class="tb-bdy hisValclm">
                                            <p class="dolValcurr">174.30 $</p>
                                            <!-- <p class="othrValcurr">357,900 Tzs</p> -->
                                        </div>
                                        <div class="stsHiscol d-flex align-items-center">
                                            <div class="tb-bdy hisStatusclm">
                                                <p class="his-recStatus">קיבלו</p>
                                            </div>
                                            <div class="tb-bdy hisAcntclm">
                                                <p class="hisAccount">בטוח 02 $</p>
                                            </div>
                                        </div>

                                        <div class="tb-bdy shrtHisclm">
                                            <div class="mb-Acntdetail">
                                                <div class="tb-bdy">
                                                    <p>חֶשְׁבּוֹן</p>
                                                    <p class="mb-Acntnum"></p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end his-Paybtn">
                                                <div
                                                    class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                    <a href="javascript:void(0)">
                                                        <p class="h3">חשבונית</p>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="doc-bx text-center d-flex justify-content-center align-items-center position-relative">
                                                        <a href="javascript:void(0)" class="dropdown-toggle runLink"
                                                            role="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <span class="docMent"></span>
                                                            <p class="btn2">מסמכים   <i
                                                                    class="fa-solid fa-angle-down"></i>
                                                            </p>
                                                        </a>

                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">מסמך 3</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="dlTe"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="align-items-center mbTask">
                                        <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>
                                
                                <!-- Item Table Body End -->
                            </div>
                        </div>
                    </section>

                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>