<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Edit Additional Fee - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

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
                                    <a class="nav-link" aria-current="page" href="index.php"><img
                                            src="Assets/icons/Bag.svg" alt="Bag" class="navIcon me-2 align-middle">
                                        <span class="align-middle">New Order</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="newRequisition.php"> <img
                                            src="Assets/icons/Chat_alt_add.svg" alt="chat"
                                            class="navIcon me-2 align-middle"> <span class="align-middle">New
                                            Requisition</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="runningTask.php"><img src="Assets/icons/Desk_alt.svg"
                                            alt="Desk" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">Running
                                            Tasks</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="history.php"><img src="Assets/icons/Book_open.svg"
                                            alt="History" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">History</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="stockView.php"><img src="Assets/icons/Shop.svg"
                                            alt="Stock" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">Stock</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="revenueCenter.php"><img src="Assets/icons/Database.svg"
                                            alt="Revenue" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">Revenue
                                            Centers</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="setup.php"><img src="Assets/icons/Setting_line.svg"
                                            alt="Setup" class="navIcon me-2 align-middle"> <span
                                            class="align-middle">Setup</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-bar lgOut">
                        <ul class="nav flex-column h2">
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0)"><img src="Assets/icons/Sign_out_squre.svg"
                                        alt="Logout" class="navIcon me-2 align-middle"> <span class="align-middle">Log
                                        out</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="cntArea setupArea">
                <section class="usr-info stockUser">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo showOtherLangText('Edit Additional Fee'); ?></h1>
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
                                    <h1 class="h1"><?php echo showOtherLangText('Edit Additional Fee'); ?></h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <h1>Q</h1>
                                <div class="dropdown d-flex">
                                    <a class="dropdown-toggle body3" data-bs-toggle="dropdown">
                                        <span> Account</span> <i class="fa-solid fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 1</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 2</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 3</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)">Account 4</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="ordDetail userDetail">
                    <div class="container">
                        <div class="usrBtns d-flex align-items-center justify-content-between">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="manageAdditionalFee.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Back'); ?></span></a>
                                </div>
                            </div>
                            <div class="usrAd-Btn">
                                <div class="btnBg">
                                    <button type="submit" class="btn sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-regular fa-floppy-disk"></i></span> <span
                                            class="dsktp-Btn"><?php echo showOtherLangText('Save'); ?></span></button>
                                </div>
                            </div>
                        </div>

                        <div class="edtSup-Div">
                            <form class="addUser-Form acntSetup-Form">

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feeName" class="form-label">Fee Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="feeName"
                                            placeholder="Ace Transport charge">
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feeType" class="form-label">Fee Type</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-select" aria-label="Default select example"
                                            id="feeType">
                                            <option selected="">Fixed Discount</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row align-items-center acntStp-Row">
                                    <div class="col-md-3">
                                        <label for="feePercentage" class="form-label">Fee Percentage % (+/-)</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="feePercentage" placeholder="10">
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>