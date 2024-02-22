<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Requisition - Queue1</title>
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
                                    <a class="nav-link active" href="newRequisition_rtl.php"> <img
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
                            <h1 class="h1">ערוך דרישה</h1>
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
                                    <h1 class="h1">ערוך דרישה</h1>
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

                <section class="ordDetail">
                    <div class="stcPart">
                        <div class="row ordRow">
                            <div class="col-md-6">
                                <div class="ordNum">
                                    <h4 class="subTittle1">להזמין#: <span>332974</span></h4>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center justify-content-end">
                                <div class="ordDate">
                                    <h4 class="subTittle1">23/05/2022</h4>
                                </div>
                            </div>
                        </div>

                        <div class="container topOrder edtReq">
                            <div class="row">
                                <div class="sltSupp">
                                    <!-- Select Supplier -->
                                    <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span>בַּר</span> <i
                                                class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">מִטְבָּח</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">משק בית</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">אחרים</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="btn-group glb-btn">
                                        <button type="button"
                                            class="btn body3 drp-btn dropdown-toggle dropdown-toggle-split d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false"><span>דרישה מאת:</span>
                                            <i class="fa-solid fa-angle-down"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)">דְרִישָׁה 1</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">דְרִישָׁה 2</a>
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">דְרִישָׁה 3</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="input-group srchBx">
                                        <input type="text" class="form-control" placeholder="לחפש" id="srch">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="ordInfo edtreqInfo">
                                    <div class="container">
                                        <div class="mbFeature">
                                            <div class="row gx-3 justify-content-end">

                                                <!-- <div class="col-md-2 text-center">
                                                    <div class="row featRow">
                                                        <div class="ordFeature">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="autoFill"></span>
                                                                <p class="btn2">Auto Fill</p>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-md-4 text-center">
                                                    <div class="row featRow">

                                                        <!-- <div class="ordFeature mbreqFill">
                                                            <a href="javascript:void(0)" class="tabFet">
                                                                <span class="autoFill"></span>
                                                                <p class="btn2">Auto Fill</p>
                                                            </a>
                                                        </div> -->

                                                        <div class="col-md-6 ordFeature brdLft"><a
                                                                href="javascript:void(0)" class="tabFet">
                                                                <span class="clear"></span>
                                                                <p class="btn2">ברור</p>
                                                            </a>
                                                        </div>

                                                        <div class="col-md-6 ordFeature drpFee position-relative">
                                                            <a href="javascript:void(0)" class="dropdown-toggle tabFet"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <span class="fee"></span>
                                                                <p class="btn2">תַשְׁלוּם <i
                                                                        class="fa-solid fa-angle-down"></i>
                                                                </p>
                                                            </a>

                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">עמלה קבועה</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">עמלת פתיחה</a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:void(0)">אַחֵר</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="prcTable">
                                            <div class="price justify-content-between reqPrice taxRow">
                                                <div class="p-1 delIcn text-center">
                                                    <a href="javascript:void(0)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                                <div class="p-1">
                                                    <p>מַס 10%</p>
                                                </div>
                                                <div class="p-1 reqPrc-end">
                                                    <p>11.362 $</p>
                                                </div>
                                            </div>
                                            <div class="price justify-content-between reqPrice">
                                                <div class="p-1 delIcn text-center"></div>
                                                <div class="p-1">
                                                    <p>סכום סופי</p>
                                                </div>
                                                <div class="p-1 reqPrc-end">
                                                    <p>113.62 $</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="smBtn">
                                    <div class="btnBg">
                                        <a href="javascript:void(0)" class="btn sub-btn"><span
                                                class="align-middle">עדכון</span> <i
                                                class="fa-solid fa-angles-right"></i></a>
                                    </div>
                                    <div class="btnBg mt-3">
                                        <a href="newRequisition_rtl.php" class="sub-btn std-btn">עדכון</a>
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

                    <div class="container nordPrice position-relative">
                        <!-- Item Table Head Start -->
                        <div class="d-flex align-items-center itmTable">
                            <div class="reqImg tb-head">
                                <p>תמונה</p>
                            </div>
                            <div class="reqCnt-Fst d-flex align-items-center">
                                <div class="reqClm-Itm tb-head">
                                    <p>פריט</p>
                                </div>
                                <div class="reqClm-Br tb-head">
                                    <p>ברקוד</p>
                                </div>
                                <div class="reqClm-Prc tb-head">
                                    <p>מחיר</p>
                                </div>
                                <div class="reqClm-Unit tb-head">
                                    <p>יחידת ג</p>
                                </div>
                            </div>
                            <div class="reqSt-Qty tb-head">
                                <p>כמות מלאי</p>
                            </div>
                            <div class="reqCnt-Scnd d-flex align-items-center">
                                <div class="reqClm-Qty tb-head">
                                    <p>כַּמוּת</p>
                                </div>
                                <div class="reqClm-Ttl tb-head">
                                    <p>סה"כ</p>
                                </div>
                            </div>
                            <div class="requi-Hide">
                                <div class="reqClm-Note tb-head">
                                    <div class="mb-ReqCode"></div>
                                    <p>הערה</p>
                                </div>
                            </div>
                        </div>
                        <!-- Item Table Head End -->
                    </div>

                    <div id="boxscroll">
                        <div class="container cntTable">
                            <!-- Item Table Body Start -->

                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
                                    <a href="javascript:void(0)" class="orderLink">
                                        <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                </div>

                            </div>
                            <div class="newReqTask">
                                <div class="d-flex align-items-center border-bottom itmBody reqCnt-Part">
                                    <div class="reqImg tb-bdy">
                                        <img src="../Assets/images/Mango.png" alt="Item" class="ordItm-Img">
                                    </div>
                                    <div class="reqCnt-Fst d-flex align-items-center">
                                        <div class="reqClm-Itm tb-bdy">
                                            <p>מנגו</p>
                                        </div>
                                        <div class="reqClm-Br tb-bdy">
                                            <p class="reqBarCode">9781462570123</p>
                                        </div>
                                        <div class="reqClm-Prc tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                        <div class="reqClm-Unit tb-bdy">
                                            <p>ק"ג</p>
                                        </div>
                                    </div>
                                    <div class="reqSt-Qty tb-bdy">
                                        <p class="reqStockQty">10 <span class="mbl-ReqStk">במלאי</span></p>
                                    </div>
                                    <div class="reqCnt-Scnd d-flex align-items-center">
                                        <div class="reqClm-Qty tb-bdy">
                                            <input type="text" class="form-control qty-itm" placeholder="1">
                                        </div>
                                        <div class="reqClm-Ttl tb-bdy">
                                            <p>2.6144 $</p>
                                        </div>
                                    </div>
                                    <div class="requi-Hide">
                                        <div class="reqClm-Note tb-bdy">
                                            <div class="mb-ReqCode"></div>
                                            <input type="text" class="form-control note-itm" placeholder="הערה">
                                        </div>
                                    </div>
                                </div>
                                <div class="mbLnk-Reqtn">
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



    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>





</body>

</html>