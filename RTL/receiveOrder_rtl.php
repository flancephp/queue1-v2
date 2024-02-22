<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Receive Order - Queue1</title>
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
    <main>
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
                                    <a class="nav-link active" aria-current="page" href="index_rtl.php"><img
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
                                <h1 class="h1">קבלו הזמנה</h1>
                            </div>
                            <div class="col-md-8 d-flex align-items-center justify-content-end">
                                <div class="mbPage">
                                    <div class="mb-nav" id="mb-nav">
                                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#navbarSupportedContent"
                                            aria-controls="navbarSupportedContent" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                                        </button>
                                    </div>
                                    <div class="mbpg-name">
                                        <h1 class="h1">קבלו הזמנה</h1>
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
                            <h1 class="h1 text-center">לחוויה טובה יותר, השתמש בתצוגה לאורך..</h1>
                        </div>
                    </section>

                    <section class="ordDetail">
                        <div class="stcPart">
                            <div class="row ordRow">
                                <div class="col-md-6">
                                    <div class="ordNum">
                                        <h4 class="subTittle1">מְשִׁימָה#: <span>332974</span></h4>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-end">
                                    <!-- <div class="ordDate">
                                        <h4 class="subTittle1">23/05/2022</h4>
                                    </div> -->
                                </div>
                            </div>

                            <div class="container topOrder rcvOrder">
                                <div class="row">
                                    <div class="suppDetl">
                                        <div class="recvInv">
                                            <p>ספק: <span class="supName">Qmb</span></p>
                                            <div class="d-flex gap-2 align-items-center">
                                                <p class="flex-fill flex-grow-0 flex-shrink-0">מס' חשבונית: </p>
                                                <input type="text" class="form-control invNum" placeholder="1234">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ordInfo rcvInfo">
                                        <div class="container">
                                            <div class="mbFeature">
                                                <div class="row gx-3 justify-content-end">

                                                    <div class="col-md-4 text-center">
                                                        <div class="row featRow">

                                                            <div
                                                                class="col-md-6 ordFeature dropdown drpCurr brdLft position-relative">
                                                                <a href="javascript:void(0)" class="tabFet">
                                                                    <span class="importFile"></span>
                                                                    <p class="btn2">ייבוא ​​קובץ מקבל</p>
                                                                </a>

                                                            </div>
                                                            <div class="col-md-6 ordFeature drpFee position-relative">
                                                                <a href="javascript:void(0)" class="tabFet">
                                                                    <span class="sampleFile"></span>
                                                                    <p class="btn2">הורד קובץ לדוגמה</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="prcTable">
                                                <div class="price justify-content-between taxRow">
                                                    <div class="p-1 txnmRow">
                                                        <p>מַס 10%</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-1">
                                                            <p>11.362 $</p>
                                                        </div>
                                                        <div class="p-1 otherCurr">
                                                            <p>11.362 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price justify-content-between">
                                                    <div class="p-1 txnmRow">
                                                        <p>סכום סופי</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-end curRow">
                                                        <div class="p-1">
                                                            <p>113.62 $</p>
                                                        </div>
                                                        <div class="p-1 otherCurr">
                                                            <p>113.62 €</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 smBtn">
                                        <div class="btnBg">
                                            <a href="javascript:void(0)" class="btn sub-btn std-btn">לְקַבֵּל</a>
                                        </div>
                                        <div class="btnBg mt-3">
                                            <a href="editOrder_rtl.php" class="btn sub-btn std-btn">חזור</a>
                                        </div>
                                        <div class="fetBtn">
                                            <a href="javascript:void(0)">
                                                <img src="../Assets/icons/dashboard.svg" alt="dashboard">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container recPrice position-relative">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="prdtImg tb-head">
                                    <p>תמונה</p>
                                </div>
                                <div class="recItm-Name tb-head">
                                    <p>פריט</p>
                                </div>
                                <div class="recQty-Code d-flex align-items-center">
                                    <div class="recItm-brCode tb-head">
                                        <p>ברקוד</p>
                                    </div>
                                    <div class="qty-Ordred tb-head">
                                        <p>כמות שהוזמנה</p>
                                    </div>
                                </div>
                                <div class="recPrc-Unit d-flex align-items-center">
                                    <div class="recItm-Unit tb-head">
                                        <p>יחידת רכישה</p>
                                    </div>
                                    <div class="qty-Rcvd tb-head">
                                        <p>כמות שהתקבלה</p>
                                    </div>
                                    <div class="recCr-Type d-flex align-items-center">
                                        <div class="dflt-RecPrc tb-head">
                                            <p>מחיר הרכישה($)</p>
                                        </div>
                                        <div class="othr-RecPrc tb-head">
                                            <p>מחיר הרכישה(€)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="recTtlPrc-Type d-flex align-items-center">
                                    <div class="ttlDft-RecPrc tb-head">
                                        <p>סה"כ($)</p>
                                    </div>
                                    <div class="ttlOtr-RecPrc tb-head">
                                        <p>סה"כ(€)</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>


                        <div id="boxscroll">
                            <div class="container cntTable">
                                <!-- Item Table Body Start -->

                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="newOrdTask recOrdTask">
                                    <div class="d-flex align-items-center border-bottom itmBody recOrd-TblBody">
                                        <div class="prdtImg tb-bdy">
                                            <img src="../Assets/images/Apple.png" alt="Item" class="ordItm-Img">
                                        </div>
                                        <div class="recItm-Name tb-bdy">
                                            <p class="recive-Item">תפוח עץ</p>
                                        </div>
                                        <div class="recQty-Code cloneQty-Code align-items-center">
                                            <div class="recItm-brCode tb-bdy">
                                                <p>9781462570123</p>
                                            </div>
                                            <div class="qty-Ordred tb-bdy">
                                                <p>10 <span class="tabOn-Qty">במלאי</span></p>
                                            </div>
                                        </div>
                                        <div class="recPrc-Unit d-flex">
                                            <div class="tab-RecItm"></div>
                                            <div class="recItm-Unit tb-bdy">
                                                <p>ק"ג</p>
                                            </div>
                                            <div class="qty-Rcvd tb-bdy">
                                                <input type="text" class="form-control qty-itm recQty-Receive"
                                                    placeholder="1">
                                            </div>
                                            <div class="recCr-Type d-flex align-items-center">
                                                <div class="dflt-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                                <div class="othr-RecPrc tb-bdy">
                                                    <input type="text" class="form-control qty-itm" placeholder="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="recTtlPrc-Type d-flex align-items-center">
                                            <div class="tabTtl-Price"></div>
                                            <div class="ttlDft-RecPrc tb-bdy">
                                                <p>2.6144 $</p>
                                            </div>
                                            <div class="ttlOtr-RecPrc tb-bdy">
                                                <p>2.6144 €</p>
                                            </div>
                                        </div>
                                        <div class="recBr-Hide">
                                        </div>
                                    </div>
                                    <div class="mbLnk-Order">
                                        <a href="javascript:void(0)" class="orderLink">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                

                                <!-- Item Table Body End -->
                            </div>

                        </div>
                    </section>


                </div>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>