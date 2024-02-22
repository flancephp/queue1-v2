<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Manage Units - Queue1</title>
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
                            <h1 class="h1">ניהול יחידות</h1>
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
                                    <h1 class="h1">ניהול יחידות</h1>
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

                <section class="ordDetail userDetail">
                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="setup_rtl.php" class="sub-btn std-btn mb-usrBkbtn"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">חזור</span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <a href="javascript:void(0)" class="sub-btn std-btn mb-usrBkbtn"
                                        data-bs-toggle="modal" data-bs-target="#add-Unit"><span class="mb-UsrBtn"><i
                                                class="fa-solid fa-plus"></i></span> <span
                                            class="dsktp-Btn">לְהוֹסִיף</span></a>
                                </div>
                            </div>
                        </div>

                        <div class="mng-UntTable">
                            <div class="container">
                                <!-- Table Head Start -->
                                <div class="mng-UntTbl-Head align-items-center itmTable">
                                    <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                        <div class="tb-head mng-UntNum-Clm">
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
                                        <div class="tb-head mng-UntName-Clm">
                                            <p>שֵׁם</p>
                                        </div>
                                    </div>
                                    <div class="mng-UntTbl-Icns">
                                        <div class="tb-head outOpt-Clm text-center">
                                            <p>אפשרויות</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table Head End -->

                                <!-- Table Body Start -->
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>1</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">קופסא</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>2</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">בקבוק</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>3</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">לְחַבֵּר</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>4</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">טוֹן</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>5</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">ק"ג</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>1</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">קופסא</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>2</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">בקבוק</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>3</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">לְחַבֵּר</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>4</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">טוֹן</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="userTask">
                                    <div class="mng-UntTbl-body align-items-center itmBody">
                                        <div class="mng-UntTbl-Cnt d-flex align-items-center">
                                            <div class="tb-bdy mng-UntNum-Clm">
                                                <p class="userNumber"><span class="mb-UsrSpan">לא. </span>5</p>
                                            </div>
                                            <div class="tb-bdy mng-UntName-Clm">
                                                <p class="userName">ק"ג</p>
                                            </div>
                                        </div>
                                        <div class="mng-UntTbl-Icns">
                                            <div class="tb-bdy mng-UntOpt-Clm d-flex align-items-center">
                                                <a href="javascript:void(0)" class="userLink" data-bs-toggle="modal"
                                                    data-bs-target="#edit-Unit">
                                                    <img src="../Assets/icons/dots.svg" alt="Dots" class="usrLnk-Img">
                                                </a>
                                                <a href="javascript:void(0)" class="userLink">
                                                    <img src="../Assets/icons/delete.svg" alt="Delete" class="usrLnk-Img">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Body End -->

                            </div>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>


    <!-- Add Unit Popup Start -->
    <div class="modal" tabindex="-1" id="add-Unit" aria-labelledby="add-UnitLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">הוסף יחידה</h1>
                </div>
                <div class="modal-body">
                    <form class="addUser-Form row">
                        <input type="text" class="form-control" id="addUnit" placeholder="שֵׁם">
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">לְהוֹסִיף</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Unit Popup End -->

    <!-- Edit Unit Popup Start -->
    <div class="modal" tabindex="-1" id="edit-Unit" aria-labelledby="edit-UnitLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">ערוך יחידה</h1>
                </div>
                <div class="modal-body">
                    <form class="addUser-Form row">
                        <input type="text" class="form-control" id="editUnit" placeholder="שֵׁם">
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">להציל</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Unit Popup End -->


    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>