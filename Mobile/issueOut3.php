<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Issue Out3 - Queue1 Mobile</title>
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
                    <a href="issueOut2.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
                    <h2 class="mblFnt2">Casa Bar</h2>
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
            <div class="row align-items-center pt-3">
                <div class="col-md-6 mblSrchClm d-flex align-items-center">
                    <div class="input-group srchBx" style="border-color: rgb(213, 214, 221);">
                        <input type="search" class="form-control" placeholder="Search" id="srch" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn" type="button" style="background-color: rgb(122, 137, 255);">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="resetClm">
                        <a href="javascript:void(0)" class="resetBtn"><i class="fa-solid fa-ellipsis"></i></a>
                    </div>
                </div>
                <div class="col-md-6 mblFnsClm d-flex justify-content-end">
                    <div class="d-flex align-items-center fnshGrp">
                        <p class="countValue"><span class="countedItm">0</span><span>/2</span></p>
                        <a href="issueOut4.php" class="finishBtn">Finish <span><i
                                    class="fa-solid fa-chevron-right"></i></span> </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="storeSection">
        <div class="container">
            <div class="storeDtl issueOut-Store">
                <div class="row receiveRow">
                    <div class="col-md-6">
                        &nbsp;
                    </div>
                    <div class="col-md-6">
                        <p class="recDate">16/01/2023</p>
                    </div>
                </div>

                <div class="issueOut-Div">
                    <div class="recPrd-List d-flex align-items-center">
                        <div class="prdctImg">
                            <img src="Assets/images/africafe.png" alt="Product">
                        </div>
                        <div class="prdctDtl rcvPrd-Dtl">
                            <p class="prdName">Africafe 100 G</p>
                            <p class="prdBarCode">988080186962</p>
                            <p class="prdName">Stock</p>
                        </div>
                        <div class="prdctHide">&nbsp;</div>
                        <div class="prdctValue rcvPrdVal">
                            <div class="receiveQty"><input type="text" value="" class="form-control prdForm"
                                    placeholder="1">
                                <p class="recQty-Unit">Bottle</p>
                            </div>
                            <div class="receiveQty">
                                <p class="recQty-Prc">99561</p>
                            </div>
                        </div>
                    </div>
                    <div class="recPrd-List d-flex align-items-center mt-3">
                        <div class="prdctImg">
                            <img src="Assets/images/africafe.png" alt="Product">
                        </div>
                        <div class="prdctDtl rcvPrd-Dtl">
                            <p class="prdName">Africafe 100 G</p>
                            <p class="prdBarCode">988080186962</p>
                            <p class="prdName">Stock</p>
                        </div>
                        <div class="prdctHide">&nbsp;</div>
                        <div class="prdctValue rcvPrdVal">
                            <div class="receiveQty"><input type="text" value="" class="form-control prdForm"
                                    placeholder="1">
                                <p class="recQty-Unit">Bottle</p>
                            </div>
                            <div class="receiveQty">
                                <p class="recQty-Prc">99561</p>
                            </div>
                        </div>
                    </div>
                    <div class="recPrd-List d-flex align-items-center mt-3">
                        <div class="prdctImg">
                            <img src="Assets/images/africafe.png" alt="Product">
                        </div>
                        <div class="prdctDtl rcvPrd-Dtl">
                            <p class="prdName">Africafe 100 G</p>
                            <p class="prdBarCode">988080186962</p>
                            <p class="prdName">Stock</p>
                        </div>
                        <div class="prdctHide">&nbsp;</div>
                        <div class="prdctValue rcvPrdVal">
                            <div class="receiveQty"><input type="text" value="" class="form-control prdForm"
                                    placeholder="1">
                                <p class="recQty-Unit">Bottle</p>
                            </div>
                            <div class="receiveQty">
                                <p class="recQty-Prc">99561</p>
                            </div>
                        </div>
                    </div>
                    <div class="recPrd-List d-flex align-items-center mt-3">
                        <div class="prdctImg">
                            <img src="Assets/images/africafe.png" alt="Product">
                        </div>
                        <div class="prdctDtl rcvPrd-Dtl">
                            <p class="prdName">Africafe 100 G</p>
                            <p class="prdBarCode">988080186962</p>
                            <p class="prdName">Stock</p>
                        </div>
                        <div class="prdctHide">&nbsp;</div>
                        <div class="prdctValue rcvPrdVal">
                            <div class="receiveQty"><input type="text" value="" class="form-control prdForm"
                                    placeholder="1">
                                <p class="recQty-Unit">Bottle</p>
                            </div>
                            <div class="receiveQty">
                                <p class="recQty-Prc">99561</p>
                            </div>
                        </div>
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