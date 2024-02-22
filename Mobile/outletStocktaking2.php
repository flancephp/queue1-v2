<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Outlet Stocktaking2 - Queue1 Mobile</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/custom.css">

</head>

<body>

    <section class="headSec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 bckClm">
                    <a href="outletStocktaking1.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
                    <h2 class="mblFnt2">Outlet Stocktaking</h2>
                </div>
                <div class="col-md-6">
                    <div class="mblUsr-clm d-flex align-items-center justify-content-end">
                        <div class="usrLg-Col d-flex align-items-center">
                            <div class="dropdown mbl-Out">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-angle-down"></i> <span class="mblFnt1">User</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0)">Logout</a></li>
                                </ul>
                            </div>
                            <img src="Assets/images/zanzibar.png" alt="User-logo" class="usrLogo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="outletStocktaking3.php" class="stockTake-Btn">Start Stocktake</a>
            </div>
        </div>
    </section>

    <section class="storeSection">
        <div class="container">
            <div class="storeDtl">
                <h3 class="mblFnt2">Outlet Stocktaking</h3>
                <div class="d-flex justify-content-between align-items-center flex-wrap pt-3">
                    <div class="stkData">
                        <p class="strHead">Date</p>
                        <p class="storeDate">01/08/2022</p>
                    </div>
                    <div class="stkData">
                        <p class="strHead">Time</p>
                        <p class="storeTime">10:00</p>
                    </div>
                    <div class="stkData">
                        <p class="strHead">Revenue Center</p>
                        <p class="storeType">Fun Beach</p>
                    </div>
                    <div class="stkData">
                        <p class="strHead">Outlet</p>
                        <p class="storeType">Fun Bar</p>
                    </div>
                    <div class="stkData">
                        <p class="strHead">Total Items To Count</p>
                        <p class="storeCount">22/22</p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>