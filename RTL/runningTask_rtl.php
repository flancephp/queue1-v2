<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Running Tasks - Queue1</title>
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
                                    <a class="nav-link active" href="runningTask_rtl.php"><img src="../Assets/icons/Desk_alt.svg"
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
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">הפעלת משימות</h1>
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
                                    <h1 class="h1">הפעלת משימות</h1>
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

                <div class="alrtMessage">
                    <div class="container">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p><strong>שלום משתמש!</strong> ההזמנה שלך בוצעה בהצלחה.</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p><strong>שלום משתמש!</strong> כדאי
                            לבדוק היטב את ההזמנה.</p>
                        
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>

                <section class="rntskHead">
                    <div class="container">
                        <div class="d-flex align-items-center taskHead">
                            <div style="width: 3%;">&nbsp;</div>
                            <div class="d-flex align-items-center runTable py-1" style="width: 97%;">

                                <div class="d-flex align-items-center" style="width: 55%;">
                                    <div style="width: 3%;">&nbsp;</div>
                                    <div class="d-flex align-items-center" style="width: 72%;">
                                        <div class="rnTsk-Num">
                                            <p>מספר</p>
                                        </div>
                                        <div class="p-1" style="width: 17%;">
                                            <p>תַאֲרִיך</p>
                                        </div>
                                        <div class="p-1" style="width: 20%;">
                                            <p>מִשׁתַמֵשׁ</p>
                                        </div>
                                        <div class="p-1" style="width: 30%;">
                                            <p>מתייחס</p>
                                        </div>
                                    </div>
                                    <div style="width: 25%;">
                                        <div class="p-1">
                                            <p>ערך</p>
                                        </div>
                                    </div>


                                </div>
                                <div class="d-flex align-items-center" style="width: 45%;">
                                    <div class="p-1">
                                        <p>סטָטוּס</p>
                                    </div>
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

                <section class="runTask">

                    <div class="container">

                        <div class="task">
                            <!-- Temp Requisition Part Start -->
                            <div class="d-flex align-items-center mt-0 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>1</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="reqType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>דְרִישָׁה</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 tmpBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="gr-sts gr-tmpReq">
                                                    <p>דרישה זמנית</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="conFirm"></span>
                                                            <p class="btn2 cn-btn">לְאַשֵׁר</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 tmpBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Temp Requisition Part End -->
                        </div>
                        <div class="task">
                            <!-- Temp Order Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>2</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbarord">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="ordType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>להזמין</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpOrd">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 tmpBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="gr-sts gr-tmpOrd">
                                                    <p>הזמנה זמנית</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="conFirm"></span>
                                                            <p class="btn2 cn-btn">לְאַשֵׁר</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 tmpBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Temp Order Part End -->
                        </div>                      
                        <div class="task">
                            <!-- Confirm Requisition Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>3</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="reqType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>דְרִישָׁה</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbcnfReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center conStatus togleOrder">
                                            <div class="py-1 conBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="rd-sts rd-cnfReq">
                                                    <p>נָחוּץ</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="isuOut"></span>
                                                            <p class="btn2 cn-btn">להוציא</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">לְהַקְצוֹת</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">חשבונית</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 conBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Confirm Requisition Part End -->
                        </div>
                        <div class="task">
                            <!-- Confirm Order Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>4</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbarord">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="ordType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>להזמין</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbcnfOrd">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center conStatus togleOrder">
                                            <div class="py-1 conBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="rd-sts rd-cnfOrd">
                                                    <p>הוזמן</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="receIve"></span>
                                                            <p class="btn2 cn-btn">לְקַבֵּל</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">לְהַקְצוֹת</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">לְשַׁלֵם</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 conBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Confirm Order Part End -->
                        </div>                        
                        <div class="task">
                            <!-- Temp Requisition Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>1</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="reqType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>דְרִישָׁה</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 tmpBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="gr-sts gr-tmpReq">
                                                    <p>דרישה זמנית</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="conFirm"></span>
                                                            <p class="btn2 cn-btn">לְאַשֵׁר</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 tmpBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Temp Requisition Part End -->
                        </div>
                        <div class="task">
                            <!-- Temp Order Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>2</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbarord">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="ordType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>להזמין</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbtmpOrd">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center tmpStatus togleOrder">
                                            <div class="py-1 tmpBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="gr-sts gr-tmpOrd">
                                                    <p>הזמנה זמנית</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="conFirm"></span>
                                                            <p class="btn2 cn-btn">לְאַשֵׁר</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 tmpBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Temp Order Part End -->
                        </div>                      
                        <div class="task">
                            <!-- Confirm Requisition Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>3</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbar">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="reqType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>דְרִישָׁה</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbcnfReq">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center conStatus togleOrder">
                                            <div class="py-1 conBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="rd-sts rd-cnfReq">
                                                    <p>נָחוּץ</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="isuOut"></span>
                                                            <p class="btn2 cn-btn">להוציא</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">לְהַקְצוֹת</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">חשבונית</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 conBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Confirm Requisition Part End -->
                        </div>
                        <div class="task">
                            <!-- Confirm Order Part Start -->
                            <div class="d-flex align-items-center mt-3 mainRuntsk">
                                <div class="srNum" style="width: 3%;">
                                    <p>4</p>
                                </div>
                                <div class="fxdhtBox">
                                    <div class="mbSbarord">&nbsp;</div>
                                    <div class="align-items-center dskDetail">
                                        <div class="d-flex align-items-center runDetail">

                                            <div class="ordType" style="width: 3%;">&nbsp;</div>
                                            <div class="d-flex align-items-center ordeReq">
                                                <div class="p-1 d-flex align-items-center flex-wrap reqDtl">
                                                    <p>להזמין</p>
                                                    <p>#10221855</p>
                                                </div>
                                                <div class="p-1 dt-run">
                                                    <p>23.05.22</p>
                                                </div>
                                                <div class="p-1 usrRuntsk">
                                                    <p>מִשׁתַמֵשׁ</p>
                                                </div>
                                                <div class="p-1 mbrRuntsk">
                                                    <p>חבר</p>
                                                </div>
                                            </div>
                                            <div class="p-1 val-run">
                                                <p class="otherCurr">360,300 Tsh </p>
                                                <p>174.30 $</p>
                                                <div class="mbcnfOrd">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="align-items-center conStatus togleOrder">
                                            <div class="py-1 conBar">&nbsp;</div>
                                            <div class="py-1 px-1 d-flex align-items-center stsBar">
                                                <div class="rd-sts rd-cnfOrd">
                                                    <p>הוזמן</p>
                                                </div>
                                                <div class="d-flex align-items-center payOptnreq">
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="receIve"></span>
                                                            <p class="btn2 cn-btn">לְקַבֵּל</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="assIgn"></span>
                                                            <p class="btn2">לְהַקְצוֹת</p>
                                                        </a>
                                                    </div>
                                                    <div
                                                        class="cnfrm text-center d-flex justify-content-center align-items-center pyinvBtn">
                                                        <a href="javascript:void(0)">
                                                            <p class="h3">לְשַׁלֵם</p>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center dleOptnreq">
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
                                                                    href="javascript:void(0)">דוק1</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק2</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="javascript:void(0)">דוק3</a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div
                                                        class="dlt-bx text-center d-flex justify-content-center align-items-center">
                                                        <a href="javascript:void(0)" class="runLink">
                                                            <span class="edIt"></span>
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
                                    </div>
                                </div>
                                <div class="align-items-center mbTask">
                                    <div class="py-1 conBar">&nbsp;</div>
                                    <div class="drpOpn d-flex justify-content-center align-items-center">
                                        <a href="javascript:void(0)" class="statusLink run-stsLnk"><i
                                                class="fa-solid fa-angle-down"></i></a>
                                    </div>
                                </div>

                            </div>
                            <!-- Confirm Order Part End -->
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