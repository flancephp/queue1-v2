<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Queue1 Mobile</title>
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
                <div class="col-md-6">
                    <img src="../Assets/icons/logo.svg" alt="Logo">
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
        </div>
    </section>

    <section class="homeList">
        <div class="container homeCont d-flex justify-content-between flex-wrap">
            <a href="storageStocktaking1.php" class="mblBx-Lnk text-center">
                <img src="../Assets/icons/Stoctaking.svg" alt="Storage" class="fieldIcns">
                <p class="mblFnt1">בדיקת מלאי אחסון</p>
            </a>
            <a href="outletStocktaking1.php" class="mblBx-Lnk text-center">
                <img src="../Assets/icons/outlet_Stoctaking.svg" alt="Outlet" class="fieldIcns">
                <p class="mblFnt1">אאוטלט סקירת מלאי</p>
            </a>
            <a href="receiveOrder1.php" class="mblBx-Lnk text-center">
                <img src="../Assets/icons/Receving.svg" alt="Receving" class="fieldIcns">
                <p class="mblFnt1">קבלה</p>
                <span class="position-absolute translate-middle badge rounded-pill bg-danger homeBadge">345
                </span>
            </a>
            <a href="issueOut1.php" class="mblBx-Lnk text-center">
                <img src="../Assets/icons/Issue_Out.svg" alt="Issue Out" class="fieldIcns">
                <p class="mblFnt1">הוצאת</p>
                <span class="position-absolute translate-middle badge rounded-pill bg-danger homeBadge">45
                </span>
            </a>
            <a href="production1.php" class="mblBx-Lnk text-center">
                <img src="../Assets/icons/Production.svg" alt="Production" class="fieldIcns">
                <p class="mblFnt1">הפקה</p>
            </a>
            <a href="barControl1.php" class="mblBx-Lnk text-center">
                <img src="../Assets/icons/Bar_Control.svg" alt="Bar Control" class="fieldIcns">
                <p class="mblFnt1">בקרת בר</p>
            </a>
        </div>
    </section>



    <script type="text/javascript" src="../Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="../Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="../Assets/js/custom.js"></script>
</body>

</html>