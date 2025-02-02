<!DOCTYPE html>
<html lang="he" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Bar Control1 - Queue1 Mobile</title>
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
                    <a href="index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
                    <h2 class="mblFnt2">בר חוף כיף</h2>
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
                <a href="barControl2.php" class="stockTake-Btn">תתחיל לעשות מלאי</a>
            </div>
        </div>
    </section>

    <section class="storeSection finishSection">
        <div class="container">
            <div class="storeDtl brControl">
                <!-- <h3 class="mblFnt2">Storage Stocktaking</h3> -->
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="stkData">
                        <p class="strHead">תַאֲרִיך</p>
                        <p class="barStr-Date">01/08/2022</p>
                    </div>
                    <div class="stkData">
                        <p class="strHead">מרכז הכנסות</p>
                        <select class="form-select revCntr-Bar" aria-label="Default select example">
                            <option selected>בחר</option>
                            <option value="1">מלון כיף</option>
                            <option value="2">בר חוף כיף</option>
                            <option value="3">אַחֵר</option>
                        </select>
                    </div>
                    <div class="stkData">
                        <p class="strHead">Outlet</p>
                        <select class="form-select outlet-Bar" aria-label="Default select example">
                            <option selected>בחר</option>
                            <option value="1">מלון כיף</option>
                            <option value="2">בר חוף כיף</option>
                            <option value="3">אַחֵר</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="brClm-6 brClmhead">
                            <p>כל הפריטים</p>
                        </div>
                        <div class="brClm-6 brClm-Dtl">
                            <p>96</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                        <div class="brClm-6 brClmhead">
                            <p>נספר</p>
                        </div>
                        <div class="brClm-6 brClm-Dtl">
                            <p>0</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                        <div class="brClm-6 brClmhead">
                            <p>לא נספר</p>
                        </div>
                        <div class="brClm-6 brClm-Dtl">
                            <p>96</p>
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