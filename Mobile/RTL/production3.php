<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Production3 - Queue1 Mobile</title>
    <link rel="icon" type="image/x-icon" href="../Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/css/custom.css">

</head>

<body>

    <section class="headSec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 bckClm">
                    <a href="production2.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
                    <h2 class="mblFnt2">התחל ייצור</h2>
                </div>
                <div class="col-md-6">
                    <div class="mblUsr-clm d-flex align-items-center justify-content-end">
                        <div class="usrLg-Col d-flex align-items-center">
                            <div class="dropdown mbl-Out">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-angle-down"></i> <span class="mblFnt1">מִשׁתַמֵשׁ</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0)">להתנתק</a></li>
                                </ul>
                            </div>
                            <img src="../Assets/images/zanzibar.png" alt="User-logo" class="usrLogo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="production4.php" class="fnshBtn doneBtn">בוצע</a>
            </div>
        </div>
    </section>

    <section class="storeSection">
        <div class="container prdcn-Cntr">
            <div class="d-flex align-items-center justify-content-between flex-wrap prdcnProcess">
                <div class="prdcnDetails">
                    <div class="d-flex justify-content-between align-items-center prdcnShow">
                        <p>בתהליך</p>
                        <p>0:00</p>
                    </div>
                    <div class="prdcnHide">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fw-bold">זמן התחלה:</p>
                            <p>12:35</p>
                        </div>
                        <p class="prcsDate">12/01/2023</p>
                    </div>
                </div>
                <div class="prdcnLnk">
                    <a href="javascript:void(0)" class="timeLink">
                        <i class="fa-solid fa-angle-down"></i>
                    </a>
                </div>
            </div>
            <div class="storeDtl production-Store">
                <div class="row receiveRow">
                    <div class="col-md-4">
                        <p class="recQuantity"><span>0</span>/2</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="prdcnTtl">מטבח הכנה</p>
                    </div>
                    <div class="col-md-4">
                        <p class="recDate">16/01/2023</p>
                    </div>
                </div>
                <div class="mblOrd-ttl d-flex pt-2">
                    <p class="ordCol1">סה"כ:</p>
                    <p class="ordCol2">140 $</p>
                </div>
                <div class="mblOrd-Subttl d-flex pt-1">
                    <p class="ordCol1">סה"כ משנה:</p>
                    <p class="ordCol2">140 $</p>
                </div>
                <div class="mblOrd-extra d-flex pt-1">
                    <p class="ordCol1">עלויות נוספות:</p>
                    <p class="ordCol2">0 $</p>
                </div>

                <div class="receiveProduct productionList">
                    <div class="recPrd-List d-flex align-items-center prdcn-List">
                        <div class="prdctImg">
                            <img src="../Assets/images/burger.png" alt="Product">
                        </div>
                        <div class="prdctDtl">
                            <p class="prdName">קציצת בורגר</p>
                            <div class="d-flex justify-contnet-betweeen align-items-center prdCnt-Clm">
                                <p class="prd-Qty">לְחַבֵּר</p>
                                <p class="prd-Count fw-bold">100</p>
                            </div>
                        </div>
                    </div>
                    <div class="recPrd-List d-flex align-items-center prdcn-List mt-2">
                        <div class="prdctImg">
                            <img src="../Assets/images/burger.png" alt="Product">
                        </div>
                        <div class="prdctDtl">
                            <p class="prdName">קציצת בורגר</p>
                            <div class="d-flex justify-contnet-betweeen align-items-center prdCnt-Clm">
                                <p class="prd-Qty">לְחַבֵּר</p>
                                <p class="prd-Count fw-bold">100</p>
                            </div>
                        </div>
                    </div>
                    <div class="recPrd-List d-flex align-items-center prdcn-List mt-2">
                        <div class="prdctImg">
                            <img src="../Assets/images/burger.png" alt="Product">
                        </div>
                        <div class="prdctDtl">
                            <p class="prdName">קציצת בורגר</p>
                            <div class="d-flex justify-contnet-betweeen align-items-center prdCnt-Clm">
                                <p class="prd-Qty">לְחַבֵּר</p>
                                <p class="prd-Count fw-bold">100</p>
                            </div>
                        </div>
                    </div>
                    <div class="recPrd-List d-flex align-items-center prdcn-List mt-2">
                        <div class="prdctImg">
                            <img src="../Assets/images/burger.png" alt="Product">
                        </div>
                        <div class="prdctDtl">
                            <p class="prdName">קציצת בורגר</p>
                            <div class="d-flex justify-contnet-betweeen align-items-center prdCnt-Clm">
                                <p class="prd-Qty">לְחַבֵּר</p>
                                <p class="prd-Count fw-bold">100</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>