<?php 
include('inc/dbConfig.php'); //connection details

include_once('script/revenueCenterReport_script.php');
			
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title> Revenue Centers - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">

</head>

<body>

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php');?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1">Revenue Centers</h1>
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
                                    <h1 class="h1"><?php showOtherLangText('Revenue Centers');?></h1>
                                </div>
                            </div>
                            <div class="user d-flex align-items-center">
                                <img src="Assets/images/user.png" alt="user">
                                <p class="body3 m-0 d-inline-block">User</p>
                            </div>
                            <div class="acc-info">
                                <img src="Assets/icons/Q.svg" alt="Logo" class="q-Logo">
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

                <section class="hisParent-sec revnueParent-sec">
                    <section class="ordDetail hisTory revCenter">

                        <div class="alrtMessage">
                            <div class="container">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <p><strong>Hello User!</strong> Ezee sales data imported successfully.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>

                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <p><strong>Hello User!</strong> error while importing data.</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </div>
                        </div>

                        <div class="container hisData">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                            <!-- Filter Btn Start -->
                                            <div class="his-filtBtn">
                                                <a href="javascript:void(0)" class="head-Filter">
                                                    <img src="Assets/icons/filter.svg" alt="Filter">
                                                </a>
                                            </div>
                                            <!-- Filter Btn End -->
                                        </div>
                                        <!-- Date Box Start -->
                                        <div class="prtDate">
                                            <div class="hstDate">
                                                <input type="text" size="10" class="datepicker" placeholder="15/01/2023"
                                                    name="fromDate" autocomplete="off" value="">
                                                <span>-</span>
                                                <input type="text" size="10" class="datepicker" placeholder="15/02/2023"
                                                    name="fromDate" autocomplete="off" value="">
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i
                                                        class="fa-solid fa-arrows-rotate"></i></a>
                                            </div>
                                            <div class="reloadBtn">
                                                <a href="javascript:void(0)"><i class="fa-solid fa-xmark"></i></a>
                                            </div>
                                        </div>
                                        <!-- Date Box End -->
                                    </div>

                                </div>
                                <div
                                    class="col-md-6 expStrdt revenue-Feature d-flex justify-content-end align-items-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/history-stock.svg" alt="history stock">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/stock-xcl.svg" alt="Stock Xcl">
                                            </a>
                                        </div>
                                        <div class="chkStore">
                                            <a href="javascript:void(0)">
                                                <img src="Assets/icons/stock-pdf.svg" alt="Stock PDF">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Date Box Start -->
                        <div class="container mb-hisDate">
                            <div class="date-flx"></div>
                        </div>
                        <!-- Mobile Date Box End -->

                        <div class="container mb-hisDtl">
                            <div class="row">
                                <div class="col-md-5 is-Incol">
                                    <p class="sale-Name">Sales</p>
                                    <p class="ttlAmount">4,322.05 $</p>
                                </div>
                                <div class="col-md-5 is-Outcol">
                                    <p class="tl-Cst">Total Cost</p>
                                    <p class="ttlAmount-rec">3,998.06 $</p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="container mbguest-Data">

                        </div>

                        <div class="container detailPrice">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl salesDtl">
                                    <div class="d-flex main-Sldiv">
                                        <div class="gstNo">
                                            <div class="guestNum d-flex">
                                                <div class="text-center">
                                                    <p class="gst-Head">No. of Guests</p>
                                                    <p class="gst-Var">25</p>
                                                </div>
                                            </div>
                                            <div class="sl-Num d-flex">
                                                <div class="text-center">
                                                    <p class="sale-Name">Sales</p>
                                                    <p class="sale-Amunt">742.0000 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ttlCost">
                                            <p class="tl-Cst text-center">Total Cost</p>
                                            <div class="d-flex justify-content-end cst-Value">
                                                <div class="col-md-7 d-flex">
                                                    <p class="prcntage-Val">3,998.06 $</p>
                                                    <p class="cst-Prcntage">34%</p>
                                                </div>
                                            </div>
                                            <div class="d-flex cst-Col">
                                                <div class="col-md-5">
                                                    <p class="sl-Cost">Sales Cost</p>
                                                    <p class="sl-Usage">Usage</p>
                                                    <p class="sl-Variance">Variance</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <p class="sale-Amount">3,438 $</p>
                                                    <p class="usage-Amount">450.06 $</p>
                                                    <p class="var-Amount">110 $</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sale-Variance text-center">
                                            <p class="sl-varDtl">Variances</p>
                                            <p class="sl-varDif">-110 $</p>
                                            <p class="sl-varValue">13 $</p>
                                            <p class="sl-varTtl">-123 $</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="gt-Data">
                                    <div class="revFeat position-relative text-center">
                                        <a href="javascript:void(0)" class="dropdown-toggle tabFet" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="edIt"></span>
                                            <p class="btn2 d-flex justify-content-center align-items-center">
                                                <span>Get Data</span> <i class="fa-solid fa-angle-down"></i>
                                            </p>
                                        </a>

                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item ent-Gstno" href="javascript:void(0)">Enter Guest
                                                    No.</a>
                                            </li>
                                            <li><a class="dropdown-item gt-Pos" href="javascript:void(0)">Get POS
                                                    Sales</a>
                                            </li>
                                            <li><a class="dropdown-item imp-Data" href="javascript:void(0)">Import Data
                                                    File</a></li>
                                            <li><a class="dropdown-item dwn-Sample" href="javascript:void(0)"> <i
                                                        class="fa-solid fa-arrow-down"></i> <span>Download sample
                                                        file</span></a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="container position-relative hstTbl-head revTbl-Hd">
                            <!-- Item Table Head Start -->
                            <div class="d-flex align-items-center itmTable">
                                <div class="rev-Cntrgst align-items-center">
                                    <div class="tb-head d-flex align-items-center rev-List">
                                        <div class="dropdown d-flex position-relative">
                                            <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <span>Revenur Center</span> <i class="fa-solid fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        1</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        2</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        3</a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0)">Revenur Center
                                                        4</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tb-head guest-List">
                                        <div class="d-flex align-items-center">
                                            <p>Guests</p>
                                            <span class="dblArrow">
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-up"></i></a>
                                                <a href="javascript:void(0)" class="d-block aglStock"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="otRev-head">
                                    <div class="out-Let align-items-center">
                                        <div class="tb-head d-flex align-items-center out-List">
                                            <div class="dropdown d-flex position-relative">
                                                <a class="dropdown-toggle body3" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <span>Outlet</span> <i class="fa-solid fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            1</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            2</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            3</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)">Outlet
                                                            4</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tb-head out-Sales">
                                            <div class="d-flex align-items-center">
                                                <p>Sales</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head out-Ttlcst">
                                            <div class="d-flex align-items-center">
                                                <p>Total Cost</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sale-Cost align-items-center">
                                        <div class="tb-head sale-Cstlist">
                                            <div class="d-flex align-items-center">
                                                <p>Sales Cost</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head sale-Usage">
                                            <div class="d-flex align-items-center">
                                                <p>Usage</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tb-head sale-Varlist">
                                            <div class="d-flex align-items-center">
                                                <p>Variance</p>
                                                <span class="dblArrow">
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-up"></i></a>
                                                    <a href="javascript:void(0)" class="d-block aglStock"><i
                                                            class="fa-solid fa-angle-down"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tb-head chk-revCnt">
                                        <div class="tab-mbFltr">
                                            <a href="javascript:void(0)" class="tab-lnkFltr"><i
                                                    class="fa-solid fa-arrow-left"></i></a>
                                        </div>
                                        <div class="tab-Filttxt">
                                            <p>Filter</p>
                                        </div>
                                        <a href="javascript:void(0)">
                                            <img src="Assets/icons/chkColumn.svg" alt="Check Column">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Item Table Head End -->
                        </div>
                    </section>

                    <section class="hisTblbody">
                        <div id="boxscroll">
                            <div class="container position-relative hstTbl-bd">
                                <!-- Item Table Body Start -->

                        <?php
$x= 0;
$tr = '';
$datesArr = [];
$guestsTotal = 0;
$useVarianceCount = 0;
$allVarianceAmt = 0;
$usageVarianceValueArr = [];
$usageTotalAmount = 0;
$outLetVariance = 0;
$costPerTot = 0;

$revIdsArr = [];
while($row = mysqli_fetch_array($getRevenueReport))
{


    $color = ($x%2 == 0)? 'white': '#FFFFCC';
    $x++;
    
    $ourLetsTotsArr = getRevenueTotals($row['outLetId'], $_GET['fromDate'], $_GET['toDate']);

    $sales = $ourLetsTotsArr['salesTotal'];
    $guests = $ourLetsTotsArr['guestsTotal'];
    $varience = $ourLetsTotsArr['varience'];
    $usage = $ourLetsTotsArr['usageTotal'];
    $usagePer = $ourLetsTotsArr['usagePer'];
    $usageLevel = $ourLetsTotsArr['usageLevel'];				
    
    
    $usageTotalAmount += $usage;
    $guestsTotal += $guests;
    $allVarianceAmt += $varience;
    $salesTotal += $sales;
    
    $usagePerGuests = ($usage && $guests) ? ($usage/$guests) : '';
    
    $usagePerValNew = ($sales != 0 && $usage ? '('.get2DecimalVal( ($usage/$sales) *100).'%)' : '');

   
                        ?>
                               

                                    <?php 
                                     if( !isset($revIdsArr[$row['id']]) )
                                     {
                                         $revIdsArr[$row['id']] = $row['id'];
                                     
                                       
                                        $revHeadPart = ' <div class="revCntr-Task align-items-center">
                                        <div class="mbshw-Revcnt">
                                            <div class="revCenter-Name d-flex align-items-center itmBody">
                                                <div class="center-List">
                                                    <p>'.$row['name'].'</p>
                                                </div>
                                                <div class="num-List">
                                                    <p><span class="mbguest-Head">Guests</span> '. $guests .'</p>
                                                </div>
                                            </div>
                                            <div class="revCenter-Dtl">';
                                    
                                    
                                        $revFooter = '</div>
                                            </div>

                                            <div class="align-items-center mbTask">
                                                <a href="javascript:void(0)" class="statusLink mb-hisLink"><i
                                                        class="fa-solid fa-angle-down"></i></a>
                                            </div>
                                        </div>';    
                                        


                                          $outLetParts .= '  <div class="revtable-Detail d-flex align-items-center itmBody">
                                                <div class="outsale-Dtl align-items-center">
                                                    <div class="outlet-Name">
                                                        <a href="outlet_report_saleCenter.php">'. $row['outletName'] .'</a>
                                                        <div class="chkbx-revCntr">

                                                        </div>
                                                    </div>
                                                    <div class="outlet-Salecst">
                                                        
                                                        <p class="bl-Sale">742.0000 $</p>
                                                    </div>
                                                    <div class="outlet-Totalcst d-flex item-items-center">
                                                        <div class="ttlCost-Amt">
                                                            
                                                            <p class="pr-Tcost">250.9259 $</p>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="outusage-Dtl">
                                                    <div class="slpart-Detail d-flex align-items-center itmBody">
                                                        <div class="saleCost-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Usage-prt">
                                                            <p>230.25 $</p>
                                                        </div>
                                                        <div class="Variance-prt">
                                                            <div class="Variance-prtPos">
                                                                <p>7 $</p>
                                                            </div>
                                                            <div class="Variance-prtNeg">
                                                                <p>-76 $</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="outchk-Dtl"></div>
                                                </div>
                                                <div class="sqChk-Box align-items-center">
                                                    <a href="javascript:void(0)"
                                                        class="grey-Box rev-issueIn fst-Bx"><span></span></a>
                                                    <a href="javascript:void(0)"
                                                        class="green-Box rev-closeStock"><span></span></a>
                                                </div>
                                            </div>';



                                  

                                        



                                ?>
                                
                    <?php } ?>
                                <!-- Item Table Body End -->
                            </div>
                    </section>

                </section>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>
</body>

</html>