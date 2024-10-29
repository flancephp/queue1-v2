<?php 
include('inc/dbConfig.php'); //connection details

if ( !isset($_SESSION['adminidusername']))
{
	echo "<script>window.location='login.php'</script>";
}


//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

//check page permission
$checkPermission = permission_denied_for_section_pages($_SESSION['designation_id'],$_SESSION['accountId']);

if (!in_array('7',$checkPermission))
{
	echo "<script>window.location='index.php'</script>";
}


include_once('script/outlet_report_saleCenter_script.php');

?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title> Sale Center - Queue1</title>
    <link rel="icon" type="image/x-icon" href="Assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="Assets/css/style.css">
    <link rel="stylesheet" href="Assets/css/style1.css">
    <style>
              /* hover effects */
  .reloadBtn a:hover,
  .cstBtn-Sale:hover {
    color: #fff !important;
    background-color: #7a89ff;
    border-color:  #7a89ff ;
  }
  .chkStore a:hover img {scale:1.1;}
  .otltBd-usg, .otltBd-var {width: 100% !important;}
  .mbStock,   .mbStk-Detail {width: 90% !important;}
  .otlt-itm, .otltBd-itm {width: 15% !important;}
  .flipClm-Hide .mbStock { width: 100% !important;}
  .mbStk-Flp {width: 100%;}
  .unitBd-div {width: 27%;display: flex;}
  .otltBd-stkPrc-1 {width: 27% !important;}
  .otltBd-Unit {display: flex;width: 72%;padding: 4px 8px;}
  .otltBd-Avr {width: 15%;}
  .otltBd-Min, .otltBd-Max {width: 13%;}
  .mbStk-Detail {height: auto;min-height: 40px;}
.otlt-Unit {width: 50%;}
.unitBd-div {width: 50%;}
.otltBd-Unit {width: 100%;}
.untFtr {width: 33.33% !important ;text-align: start;}
.untSub {width: 33.33% !important;}
.untCount {width: 33.33% !important;}

.untFtr::after , .untFtr::before {
    display: none;
}

@media screen and (max-width: 992px) {
.mbStock,   .mbStk-Detail {width: 100% !important;}
.unitBd-div {
  width: 100%;
  display: flex;padding-left: 10px;
}
.untFtr {
  width: 100% !important;
  text-align: start;
}
.untSub {
  width: 100% !important;
}
.otltBd-Min, .otltBd-Max {
  width: 100%;
  display: flex;
  justify-content: space-between;padding-left: 10px;padding-right: 12px;
}
.otltBd-Req {
    width: 100%;padding-left: 10px;
  }
  .untCount {
  width: 100% !important;
}
.otltBd-Avr {
  width: 100%;padding-left: 43px;
}
html[dir=rtl] .otltBd-Unit {
  display: flex;
  width: 72%;
  padding: 4px 0px;
}

}
@media screen and (max-width: 1024px) {
    .detailPrc-show { 
        background: #fff;height: 93%;top: auto;bottom: 0;align-items: flex-start;border-radius:var(--radius) var(--radius) 0 0;
    }
}
@media(min-width:992px) {
    /* .otltBd-slBar, .otltBd-Ajst { display:none; } */
    /* .currItm-Info > div { width: 12.5%; } */

    .otlt-itm-typ, .otltBd-itm-typ { width: 7%; }
    .otlt-itm, .otltBd-itm { width: 10% !important; }
    .otltBd-stkPrc, .otlt-stkPrc { width: 7% !important; }
    .flipClm-Otlt, .infoBtn-Hide { width: 66%; }
    .otlt-opnStk, .otlt-isn, .otlt-Ajst, .otlt-Pos, .otlt-slBar, .otlt-clStk, .otlt-usg, .otlt-var, .usg-p-g, .avg-usg, .usg-lvl {
        width: 9.09% !important;
    }
    .otltBd-opnStk, .otltBd-isn, .otltBd-Ajst, .otltBd-Pos, .otltBd-slBar, .otltBd-clStk, .otltBd-usg, .otltBd-var, .usg-p-g, .avg-usg, .usg-lvl {
        width: 9.09% !important;
    }
    /* .otltBd-opnStk, .otltBd-isn, .otltBd-Ajst { padding-left: 8px; } */
    .otltBd-usg, .otltBd-var { padding-left: 0; }
}
/* html[dir="rtl"] .otltBd .form-control[type="date"]::-webkit-calendar-picker-indicator {
    right: auto;
    left: 0;
} */
.form-control[type="date"]::-webkit-calendar-picker-indicator {
  right: var(--calendar-icon-position, auto);
  left: var(--calendar-icon-position, 0);
}
</style>

</head>

<body class="mb-saleBg">

    <div class="container-fluid newOrder">
        <div class="row">
            <div class="nav-col flex-wrap align-items-stretch" id="nav-col">
                <?php require_once('nav.php'); ?>
            </div>
            <div class="cntArea">
                <section class="usr-info">
                    <div class="row">
                        <div class="col-md-4 d-flex align-items-end">
                            <h1 class="h1"><?php echo $outLetDet['name'];?></h1>
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
                                    <h1 class="h1"><?php echo $outLetDet['name'];?></h1>
                                </div>
                            </div>
                             <?php require_once('header.php'); ?>
                        </div>
                    </div>
                </section>

                <section id="landScape">
                    <div class="container">
                        <h1 class="h1 text-center">For better experience, Please use portrait view.</h1>
                    </div>
                </section>

                <section class="hisParent-sec revnueParent-sec">
                    <section class="ordDetail revCenter oltReport oltMs1 ">
                        <div class="container hisData">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="revenueCenterReport.php" class="btn btn-primary mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="hstCal">
                                        <button class="btn btn-primary fab__search__btn d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                                            <i class="fa-regular fa-calendar p-0"></i>
                                        </button>
                                        <div class="his-featBtn d-none d-lg-inline-block">
                                            <div class="cal-Ender">
                                                <a href="javascript:void(0)">
                                                    <i class="fa-regular fa-calendar"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Date Box Start -->
                                        <form action="outlet_report_saleCenter.php" id="frm" name="frm" method="get">

                                        <input type="hidden" name="outLetId" id="outLetId"
                                        value="<?php echo $_GET['outLetId'];?>" />
                                            <div class="prtDate">

                                                        <div class="hstDate">
                                                            <input type="text" size="10" class="datepicker"  
                                                                name="fromDate" autocomplete="off" value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : date('d-m-Y');?>">
                                                            <span>-</span>
                                                            <input type="text" size="10" class="datepicker" 
                                                                name="toDate" autocomplete="off" value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : date('d-m-Y');?>">
                                                        </div>
                                                        <div class="reloadBtn">
                                                            <a href="javascript:void(0)" onClick="return loaderFrm();"><i class="fa-solid fa-arrows-rotate"></i></a>
                                                        </div>
                                                        
                                                
                                            </div>
                                        </form>
                                        <!-- Date Box End -->
                                    </div>

                                </div>
                                <div class="col-md-6 expStrdt d-flex justify-content-end align-items-end">
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

                            <div class="collapse" id="collapseSearch">
                                <div class="mt-4 d-flex gap-2 res__search__box">
                                    <div class="hstDate p-0 border-0">
                                        <input type="text" size="10" class="datepicker"  
                                            name="fromDate" autocomplete="off" value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : date('d-m-Y');?>">
                                        <span>-</span>
                                        <input type="text" size="10" class="datepicker" 
                                            name="toDate" autocomplete="off" value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : date('d-m-Y');?>">
                                    </div>
                                    <div class="reloadBtn m-0">
                                        <a href="javascript:void(0)" onClick="return loaderFrm();"><i class="fa-solid fa-arrows-rotate"></i></a>
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
                                <div class="col-md-5 ttl-Cstcol">
                                    <p class="tl-Cst">Total Cost</p>
                                    <div class="d-flex justify-content-center cst-Value">
                                        <div class="d-flex flex-wrap justify-content-center">
                                            <p class="prcntage-Val">360.44 $</p>
                                            <p class="cst-Prcntage">48.57%</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 var-Cstcol">
                                    <p class="sl-varDtl">Variances</p>
                                    <p class="sl-varDif">-18.3 $</p>
                                </div>
                                <div class="col-md-2 maxBtn">
                                    <a href="javascript:void(0)" class="maxLink">
                                        <img src="Assets/icons/maximize.svg" alt="Maximize">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="container mbguest-Data">

                        </div> -->








                        <?php 

$tr = '';
		$datesArr = [];
		$topCosts = 0;
		$costSalesAmtTot = 0;
		$x = 0;
        $varPosAmt = 0;
        $varNegAmt = 0;
		//$stockPriceForOpenStock = 0;
        $usageItemTotalAmt = 0;
        $itemsLists = '';
		$slno = 0;
		while( $row = mysqli_fetch_array($outLetItemsQry) )
		{
				$x++;
                $slno++;
				
				$dailyImportedData = $itemFirstReportRowArr[$row['saleBarcode']];//$row;
				
				
				$stockPrice = $dailyImportedData['stockPrice'];
				$stockPrice = $stockPrice > 0 ? $stockPrice : $dailyImportedData['itemLastPrice'];
				
				
				//all columns data is here
				$usageAvg = $dailyImportedData['usageAvg'];
				$usagePerGuest = $dailyImportedData['usagePerGuest'];
					
				$issueIn = $dailyImportedData['issueIn'];
				$adjustment = $dailyImportedData['adjment'];
				$sales = $dailyImportedData['easySales'];
				$barControl = $dailyImportedData['barControl'];
				$closeStockDone = $dailyImportedData['closeStockDone'];
				
				if(  ($_GET['fromDate'] != $_GET['toDate'])  &&  isset($dailyImportedData['saleBarcode']) )
				{
					
					$itemFirstRowDetailsArr = $itemFirstReportRowArr[$dailyImportedData['saleBarcode']];
					$openStock = $itemFirstRowDetailsArr['openStock'];
					//$stockPrice = $itemFirstRowDetailsArr['stockPrice'];
					
					$itemLastRowDetailsArr = $itemLastReportRowArr[$dailyImportedData['saleBarcode']];
					
					$stockPrice = $itemLastRowDetailsArr['stockPrice'] > 0 ? $itemLastRowDetailsArr['stockPrice'] : $itemLastRowDetailsArr['itemLastPrice'];					
					
					$closeStock = $itemLastRowDetailsArr['closeStock'];
					
					
					$itemsTotalCntArr = $itemsTotalsArr[$dailyImportedData['saleBarcode']];
					
					$usageAvg = ($itemsTotalCntArr['usageNoOfDays'] && $itemsTotalCntArr['usagePerDayTot']) ? ($itemsTotalCntArr['usagePerDayTot']/$itemsTotalCntArr['usageNoOfDays']) : '';
					$usagePerGuest = $itemsTotalCntArr['usagePerGuestTot'];
					
					$issueIn = $itemsTotalCntArr['issueInTot'];
					$adjustment = $itemsTotalCntArr['adjmentTot'];
					$sales = $itemsTotalCntArr['easySalesTot'];
					$barControl = $itemsTotalCntArr['barControlTot'];
					
					$adjForEnptyBottle = isset($itemsTotalCntArr['adjForEnptyBottle']) ? $itemsTotalCntArr['adjForEnptyBottle'] : 0;
														
				}
				else
				{
					$openStock = $dailyImportedData['openStock'];
					//$stockPriceForOpenStock = $dailyImportedData['stockPrice'];
					
					$closeStock = $dailyImportedData['closeStock'];
					$adjForEnptyBottle = $dailyImportedData['adjForEnptyBottle'];
					
				}
				
				$stockPrice = $stockPrice > 0 ? $stockPrice : $dailyImportedData['itemLastPrice'];//if still its zero then assign from product
				
				
				
				$usageCloseStock = $adjForEnptyBottle == 1 ? ($closeStock+$adjustment) : $closeStock;//in case of empty bottle adjustment
				
				$usage = ($closeStockDone) ? (($openStock+$issueIn+$barControl) - ($usageCloseStock) ) : '';//(Open Stock+Issue In) - (Close Stock)
				
				$usageLevel = $usageAvg ?  ( get2DecimalVal(($usage/$usageAvg)-1).'%' ) : '';

				if($row['outletItemType'] == 3)//usage item type=3, bar=1, sales=2
				{
					$varience = '';//(Sales+Adjustment) - (Usage )
					$varienceAmt = '';
				}
				else
				{
					$adjustmentForVarinc = $adjForEnptyBottle == 1 ? 0 : $adjustment;
					
					$varience = $closeStockDone > 0 ? ( ($sales+$adjustmentForVarinc) - $usage ) : '';//(Sales+Adjustment) - (Usage )
					$varienceAmt = $varience*$stockPrice;
					$varienceTotalAmt += $varienceAmt;
				}
								
				
				$usageItemAmt = $usage*$stockPrice;
				$usageItemTotalAmt += $usageItemAmt;
				

				//requisition qty
				$requisition = '';
				if ( ($_GET['fromDate'] == $_GET['toDate']) && ($closeStockDone) && ($closeStock < $row['outletMinQty']) && $dailyImportedData['requisition'] == 0 ) {
					
					$requisition = ($row['outletMaxQty'] - $closeStock);
					$requisition = ($requisition > 0) && $row['factor'] ? ceil($requisition/$row['factor']) : '';
				}
				if ( ($_GET['fromDate'] == $_GET['toDate']) && $dailyImportedData['requisition'] > 0 ) {
					
					$requisition = ( ($row['outletMaxQty'] - $closeStock) - ($dailyImportedData['requisition']*$row['factor']) );
					$requisition = ($requisition > 0) && $row['factor'] ? ceil($requisition/$row['factor']) : '';
				}
				
				
				//Hide (0) Variances filter---------------------------------------------------------------------------											
				if( isset($_GET['hideVar']) && $_GET['hideVar'] && ($varience == 0  || $row['outletItemType'] == 3) )
				{
					continue;
				}
				
				
				//-------------------------------------------------------------------------------
			
				$img = '';
				if( $row['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
				{	
					$img = $siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'];
				}
                else
                {
                    $img = 'Assets/images/jack-daniel.png';
                }
		
			
				
			
			
			$note = getNoteDet($row['outLetId'], $row['pId'], $_GET['fromDate']);
			$requisition = $requisition > 0 ? '<span style="color:grey; font-weight: bold;">'.$requisition. '</span> <span style="color:grey;font-size: 12px;">'.$row['countingUnit'].'</span>' : $requisition;
			
			
			//Get prices
			$issueInAmt = ($issueIn*$stockPrice);
			$adjustmentAmt = ($adjustment*$stockPrice);
			$openStockAmt =  ($openStock*$stockPrice);
			$closeStockAmt = ($closeStock*$stockPrice);
			$usageAmt =  ($usage*$stockPrice);
			$barControlAmt =  ($barControl*$stockPrice);
			$salesAmt = ($sales*$stockPrice);
			//end get prices
			
			//get total -----------------------------------------------------------------------------------------------------------------				

			$issueInAmtTot += $issueInAmt;
			$adjustmentAmtTot += $adjustmentAmt;
			$salesAmtTot += $salesAmt;
			$openStockAmtTot += $openStockAmt;
			$closeStockAmtTot += $closeStockAmt;
			$usageAmtTot += $usageAmt;
			$barControlAmtTot += $barControlAmt;
			
			
			$decimalPlace = 2;
			
			$issueInAmt = getNumFormtPrice( ($issueInAmt),$getDefCurDet['curCode'], $decimalPlace);
			$adjustmentAmt = getNumFormtPrice( ($adjustmentAmt),$getDefCurDet['curCode'], $decimalPlace);
			$openStockAmt = getNumFormtPrice( ($openStockAmt),$getDefCurDet['curCode'], $decimalPlace);
			$closeStockAmt = $closeStockAmt ? getNumFormtPrice( ($closeStockAmt),$getDefCurDet['curCode'], $decimalPlace) : '';
			$usageAmt = getNumFormtPrice( ($usageAmt),$getDefCurDet['curCode'], $decimalPlace);
			$barControlAmt = getNumFormtPrice( ($barControlAmt),$getDefCurDet['curCode'], $decimalPlace);
			$varienceAmt = getNumFormtPrice( ($varienceAmt),$getDefCurDet['curCode'], $decimalPlace);
			$salesAmt = getNumFormtPrice( ($salesAmt),$getDefCurDet['curCode'], $decimalPlace);
			//end total ------------------------------------------------------------------------------------------------------------------	
			
            if($varienceAmt > 0)
            {
			    $varPosAmt += $varienceAmt;
            }

            if($varienceAmt != '' && $varienceAmt < 0)
            {
			    $varNegAmt += $varienceAmt;
            }

			$variancesVal =  $varience !== 0 ? ( (get2DecimalVal($varience)) ) : 0;
		
			$varianceShowHideClass = $variancesVal == 0 ? 'hideVariance' : 'showVariance';

			//if close stock is done then only show zero/other values
			if($closeStockDone != 1 && ($_GET['fromDate'] == $_GET['toDate']) )
			{
				$requisition = '';
				$openStock = '';
				$closeStock = '';
				$usage = '';
				$variancesText = '';
				$usagePerGuest = '';
				$usageAvg = '';
				$usageLevel = '';
				
				$adjustmentAmt = '';
				$openStockAmt = '';
				$closeStockAmt = '';
				$usageAmt = '';
				$salesAmt = '';
				
			}
			
			$issueIn = $issueIn ? $issueIn : '';
			$barControl = $barControl ? $barControl : '';
			$barControlAmt = $barControlAmt ? $barControlAmt : '';
			
			$salesPriceText =  '<span class="showhideprice"><br>'. $salesAmt. '<br></span>';
            
                               $itemsLists .= ' <!-- Item Table Body Start -->
                                <div class="container outletBody-Task '.$varianceShowHideClass.'" >
                                
                                    <div class="otltTbl-bdy border-bottom">

                                        <div class="otltBd-slno">
                                            '.$slno.'
                                        </div>

                                        <div class="otltBd-hdImg">
                                            <img src="'.$img.'" alt="Item">
                                        </div>
                                        
                                        <div class="otltBd-itm">
                                            <p>'. $row['itemName'].'</p>
                                        </div>
                                        <div class="otltBd-itm-typ">
                                            '. getItemType($row['outletItemType']).'
                                        </div>

                                        <div class="otltBd-stkPrc" title="Stock Price hideInMobile">
                                            '. getNumFormtPrice($stockPrice,$getDefCurDet['curCode'],$decimalPlace).'
                                        </div>

                                        <div class="infoBtn-Hide">
                                            <div class="outletInfo">
                                                <div class="mbStk-Detail">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">

                                                            <div class="otltBd-opnStk d-lg-none">
                                                                <p class="mbSale-Head">Stock Price</p>
                                                                <p class="mblStock-Sale" title="Stock Price">'.getNumFormtPrice($stockPrice,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>

                                                            <div class="otltBd-opnStk">
                                                                <p class="mbSale-Head">Open Stock</p>
                                                                <p class="mblStock-Sale" title="Open Stock">'.showValue($openStock).'</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p class="mbSale-Head">Issues In</p>
                                                                <p class="mblStock-Sale fw-bold" title="Issues In">'. showValue($issueIn).'</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p class="mbSale-Head">Adjust</p>
                                                                <p class="mblStock-Sale" title="adjustment">'. showValue($adjustment).'</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p class="mbSale-Head">Sales POS</p>
                                                                <p class="mblStock-Sale fw-bold salesQty" title="Sales POS">'. $sales.'</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p class="mbSale-Head">Sales Bar.C</p>
                                                                <p class="mblStock-Sale fw-bold" title="Sales Bar">'. $barControl.'</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p class="mbSale-Head">Close Stock</p>
                                                                <p class="mblStock-Sale" title=""Close Stock">'. $closeStock.'</p>
                                                            </div>
                                                              <div class="otltBd-usg">
                                                            <p class="mbSale-Head">Usage</p>
                                                            <p class="mblStock-Sale fw-bold" title="Usage">'. $usage.'</p>
                                                        </div>


                                                        <div class="otltBd-var">
                                                            <p class="mbSale-Head">Variance</p>
                                                            <p class="mblStock-Sale fw-bold" title="Variance">'. $variancesVal.'</p>
                                                        </div>
                                                            <div class="usg-p-g">
                                                                <p class="mbSale-Head">Usage/Guest</p>
                                                                <p class="mblStock-Sale" title="Usage/Guest">'.get2DecimalVal($usagePerGuest).'</p>
                                                            </div>
                                                            <div class="avg-usg">
                                                                <p class="mbSale-Head">Usage Avg</p>
                                                                <p class="mblStock-Sale" title="Usage Avg">'.get2DecimalVal($usageAvg).'</p>
                                                            </div>
                                                            <div class="usg-lvl">
                                                                <p class="mbSale-Head">Usage Level</p>
                                                                <p class="mblStock-Sale" title="Usage Level">'.$usageLevel.'</p>

                                                            </div>

                                                        </div>




                                                        <div class="itmInfo-Otlt currItm-Info">
                                                            <div class="otltBd-opnStk">
                                                                <p title="Open Stock Amount">'. getNumFormtPrice($openStockAmt,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p title="Issue In Amount">'. getNumFormtPrice($issueInAmt,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                            <p title="Adjustment Amount">'. getNumFormtPrice($adjustmentAmt,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p title="Sales Amount">'. getNumFormtPrice($salesAmt,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p>'. getNumFormtPrice($barControlAmt,$getDefCurDet['curCode'],$decimalPlace) .'</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p title="Close Stock Amount">'.getNumFormtPrice($closeStockAmt,$getDefCurDet['curCode'],$decimalPlace) .'</p>
                                                            </div>
                                                            <div class="otltBd-usg">
                                                                <p title="Usage Amount">'. getNumFormtPrice($usageAmt,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>
                                                            <div class="otltBd-var">
                                                                <p title="Variance Amount">'. getNumFormtPrice($varienceAmt,$getDefCurDet['curCode'],$decimalPlace) .'</p>
                                                            </div>
                                                            <div class="usg-p-g"> 
                                                            </div>
                                                            <div class="avg-usg"> 
                                                            </div>
                                                            <div class="usg-lvl"> 
                                                            </div>


                                                        
                                                              
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="otltBd-hdNote">
                                                    <p class="mbSale-Head mbHide-Note">Note</p>
                                                    <input type="text" class="form-control note-itm outletNote"  id="note'.$row['pId'].'" onchange="updateNote(this.value,  '.$row['pId'].')" value="'. $note.'"
                                                        placeholder="Note">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flipInfo-Clm">
                                            <div class="outletInfo">
                                            
                                           <div class="mbFlip-Dtl d-none">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-stkPrc">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal"></p>
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="mbStk-Flp">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="unitBd-div">
                                                        <div class="otltBd-stkPrc otltBd-stkPrc-1">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal"></p>
                                                        </div>
                                                                <div class="otltBd-Unithd">
                                                                    <p class="untCount mbSale-Head">Count Unit</p>
                                                                    <p class="untFtr mbSale-Head">Factor </p>
                                                                    <p class="untSub mbSale-Head">Sub Unit</p>
                                                                </div>
                                                                <div class="otltBd-Unit">
                                                                    <p class="untCount">'. $row['countingUnit'].'</p>
                                                                    <p class="untFtr">'. $row['factor'].' </p>
                                                                    <p class="untSub">'. $row['subUnit'].'</p>
                                                                </div>
                                                            </div>

                                                            <div class="otltBd-Avr">
                                                                <p class="mbSale-Head">Avr Usage</p>
                                                                <p class="mblAvg-Dtl" title="Usage Avg">'. getNumFormtPrice($usageAvg,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                                <p class="mblAvr-Usg" title="Usage Avg">'. getNumFormtPrice($usageAvg,$getDefCurDet['curCode'],$decimalPlace).'</p>
                                                            </div>
                                                            <div class="otltBd-Min">
                                                                <p class="mbSale-Head">Min</p>
                                                                <p class="mblMinQty" title="Min Qty">'. $row['outletMinQty'].'</p>
                                                            </div>
                                                            <div class="otltBd-Max">
                                                                <p class="mbSale-Head">Max</p>
                                                                <p class="mblMaxQty" title="Max Qty">'. $row['outletMaxQty'].'</p>
                                                            </div>
                                                            <div class="otltBd-Req">
                                                                <p class="mbSale-Head">Requisition</p>
                                                                <p class="mblReq fw-bold" title="Requisition">'. $requisition.'</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                              
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-bdyOlt">
                                        <a href="javascript:void(0)" class="slCst-Lnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>';
                              
  }//end while ?>                              










                        <div class="container detailPrice">
                            <div class="row">
                                <div class="tab-mbDtl">
                                    <a href="javascript:void(0)" class="tab-revLnk"><i
                                            class="fa-solid fa-arrow-left"></i></a>
                                </div>
                                <div class="issueDtl saleCnt-Rpt">
                                    <div class="d-flex main-Sldiv">
                                        <div class="gstNo saleGust">
                                            <div class="guestNum d-flex">
                                                <div class="text-center">
                                                    <p class="gst-Head">No. of Guests</p>
                                                    <p class="gst-Var"><?php echo $guestsTotal;?></p>
                                                </div>
                                            </div>
                                            <div class="sl-Num d-flex">
                                                <div class="text-center">
                                                    <p class="sale-Name">Sales</p>
                                                    <p class="sale-Amunt"><?php  showPrice($easySalesAmt,$getDefCurDet['curCode'], '');?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ttlCost saleTtl-Cost text-center">
                                            <p class="tl-Cst">Total Cost</p>
                                            <div class="d-flex justify-content-center cst-Value">
                                                <div class="d-flex">
                                                    <p class="prcntage-Val"><?php  showPrice($usageItemTotalAmt,$getDefCurDet['curCode'], '');?></p>
                                                    <p class="cst-Prcntage"><?php echo  $easySalesAmt > 0 ? ('('.get2DecimalVal( ($usageItemTotalAmt/$easySalesAmt)*100 ).'%)') : '';
                                                ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sale-Variance text-center saleVar-Inr">
                                            <p class="sl-varDtl">Variances</p>
                                            <p class="sl-varDif"><?php echo getNumFormtPrice($varienceTotalAmt,$getDefCurDet['curCode'], 0, '');?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container saleSrch-Cst px-3">
                            <div class="row sp-pad1">
                                <div class="col-md-6 slCst-Srch gap-3">
                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" onKeyUp="myFunction()" placeholder="Search" name="search" id="search"
                                            aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Filter Btn Start -->
                                    <div class="dropdown slCst-fltBtn">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="Assets/icons/filter.svg" alt="Filter">
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="outlet_report_saleCenter.php?outLetId=<?php echo $_GET['outLetId'];?>&fromDate=<?php echo $_GET['fromDate'];?>&toDate=<?php echo $_GET['toDate'];?>">All</a></li>

                                        <li><a class="dropdown-item" href="outlet_report_saleCenter.php?outLetId=<?php echo $_GET['outLetId'];?>&fromDate=<?php echo $_GET['fromDate'];?>&toDate=<?php echo $_GET['toDate'];?>&outLetType=2">Sales</a></li>
                                            <li><a class="dropdown-item" href="outlet_report_saleCenter.php?outLetId=<?php echo $_GET['outLetId'];?>&fromDate=<?php echo $_GET['fromDate'];?>&toDate=<?php echo $_GET['toDate'];?>&outLetType=1">Bar Control</a></li>
                                            <li><a class="dropdown-item" href="outlet_report_saleCenter.php?outLetId=<?php echo $_GET['outLetId'];?>&fromDate=<?php echo $_GET['fromDate'];?>&toDate=<?php echo $_GET['toDate'];?>&outLetType=3">Usage</a></li>
                                        </ul>
                                    </div>
                                    <!-- Filter Btn End -->

                                </div>
                                <div class="col-md-6 shrtBtn-Clm">
                                    <div class="shrtBtns">
                                        <a href="javascript:void(0)" class="cstBtn-Sale" data-bs-toggle="modal"
                                            data-bs-target="#adjust">
                                            <img src="Assets/icons/Setting_line.svg" alt="Adjustment"
                                                class="cstBtn-Img"> <span class="cstMb">Adjust</span>
                                        </a>
                                        <a id="ShowHideZeroVar" href="javascript:void(0)" class="cstBtn-Sale ShowHideZeroVar">
                                            <img src="Assets/icons/zero.svg" alt="zero" class="cstBtn-Img">
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Sale hideBtn-Info">
                                            <img src="Assets/icons/info.svg" alt="Information" class="cstBtn-Img">
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Sale hideBtn-Prc">
                                            <span class="txtSale-Btn">$</span>
                                        </a>
                                        <a href="javascript:void(0)" class="cstBtn-Qty actvSale-Cst">
                                            <span class="txtSale-Btn">Qyt</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="outletBdy">
                        <div id="boxscroll">
                            <div class="container position-relative outletTbl">
                                <!-- Item Table Head Start -->
                                <div class="container outletTask d-none d-lg-flex">
                                    <div class="otltTbl-head">
                                    <div class="otlt-slno"><?php echo mysqli_num_rows($outLetItemsQry) > 0 ? mysqli_num_rows($outLetItemsQry) : ''; ?></div>
                                        <div class="otlt-hdImg"></div>
                                        <div class="otlt-itm">
                                            <p>Item</p>
                                            <p>Totals</p>
                                        </div>

                                        <div class="otlt-itm-typ hideInMobile">
                                            <p>Item Type</p>
                                        </div>

                                        <div class="otlt-stkPrc hideInMobile">
                                                    <p>Stock price</p>
                                                </div>

                                        <div class="flipClm-Otlt">
                                            <div class="mbStock">
                                                <div class="otlt-opnStk">
                                                    <p>Open Stock</p>
                                                    <p class="stkVal-Otlt" title="Open Stock Amount"><?php echo getNumFormtPrice($openStockAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-isn">
                                                    <p>Issues In</p>
                                                    <p class="stkVal-Otlt" title="Issue In Amount"><?php echo getNumFormtPrice($issueInAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-Ajst">
                                                    <p>Adjust</p>
                                                </div>
                                                <div class="otlt-Pos">
                                                    <p>Sales POS</p>
                                                    <p class="posValue" title="Sales POS Amount"><?php echo getNumFormtPrice($salesAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-slBar">
                                                    <p>Sales Bar</p>
                                                    <p class="posValue" title="Sales Bar Amount"><?php echo getNumFormtPrice($barControlAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-clStk">
                                                    <p>Close Stock</p>
                                                    <p class="stkVal-Otlt" title="Close Stock Amount"><?php echo getNumFormtPrice($closeStockAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                               
                                            
                                                <div class="otlt-usg">
                                                    <p>Usage</p>
                                                    <p class="cstVal-Otlt" title="Usage Amount"><?php echo getNumFormtPrice($usageAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                
                                                <div class="otlt-var">
                                                    <p>Variance</p>
                                                    <p class="varnVal-Otlt" title="Variance Amount"><?php echo getNumFormtPrice($varienceTotalAmt,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
 
                                                <div class="usg-p-g">
                                                    <p>Usage/Guest</p>
                                                    <p class="stkVal-Otlt"></p>
                                                </div>
                                                <div class="avg-usg">
                                                    <p>Avg Usage</p>
                                                    <p class="stkVal-Otlt"></p>
                                                </div>
                                                <div class="usg-lvl">
                                                    <p>Usage Level</p>
                                                    <p class="stkVal-Otlt"></p>
                                                </div>
                                            </div>

                                            <div class="otlt-hdNote"></div>
                                        </div>
                                        <div class="flipClm-Hide">
                                            <div class="mbStock">
                                                
                                                <div class="otlt-Unit">
                                                    <p class="untCount">Count Unit</p>
                                                    <p class="untFtr">Factor </p>
                                                    <p class="untSub">Sub Unit</p>
                                                </div>
                                                <div class="otlt-avgUsg">
                                                    <p>Average Usage</p>
                                                </div>
                                                <div class="otlt-Min">
                                                    <p>Min</p>
                                                </div>
                                                <div class="otlt-Max">
                                                    <p>Max</p>
                                                </div>
                                                <div class="otlt-Req">
                                                    <p>Requisition</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mbLnk-Otlt">
                                        <a href="javascript:void(0)" class="oltLnk">
                                            <i class="fa-solid fa-angle-down"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Item Table Head End -->

                                <?php echo $itemsLists; ?>
                                <!-- Item Table Body End -->


                            </div>
                    </section>

                </section>

            </div>
        </div>
    </div>

    <!-- Add Storage Popup Start -->
    <form action="" method="post" id="adjustmentFrm" name="adjustmentFrm" class="addUser-Form row">
    <div class="modal" tabindex="-1" id="adjust" aria-labelledby="add-PhyStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">Adjustment</h1>
                </div>
                <div class="modal-body">

                
                    

                <input type="hidden" name="outLetId" value="<?php echo $_GET['outLetId'];?>" />

                <input type="hidden" size="10" name="fromDate"
                    value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : date('d-m-Y');?>">

                <input type="hidden" size="10" name="toDate"
                    value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : date('d-m-Y');?>">


                        <input type="text" class="form-control" name="item" id="item" onChange="checkItemtype(this.value);" placeholder="Item">
                        <input type="date" class="form-control" name="adjDate" id="adjDate">
                        <input type="number" class="form-control" name="qty" id="ajstNum" placeholder="1">

                        <div style="display:none;" id="barItemAdj" class="my-3">
                            <!-- <div style="width: 35%;"></div> -->
                            <div class="d-flex align-items-center"><input type="checkbox" name="adjForEnptyBottle" value="1" class="form-check-input" />&nbsp;&nbsp;Tick it to adjust
                                empty bottle(s)</div>
                        </div>

     
                </div>
                <div class="modal-footer justify-content-start">
                    <div class="btnBg">
                        <button type="submit" name="saveBtn" class="btn btn-primary">Save</button>
                    </div>
                    <div class="btnBg">
                        <button type="submit" class="btn btn-primary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Add Storage Popup End -->

    <script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="Assets/js/custom.js"></script>

     <!-- Links for datePicker and dialog popup -->
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script type="text/javascript" src="cdn_js/jquery-ui-1.12.1.js"></script>
<script>
$(function() {
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy'
        });
});


function loaderFrm() {

    document.getElementById('frm').submit();
    return true;
}


function checkItemtype(itemName) {

$.ajax({
        method: "POST",
        url: "ajax.php",

        data: {
            itemNameVal: itemName,
            outLetId: '<?php echo $_GET['outLetId'];?>'
        }
    })
    .done(function(itemType) {

        if (itemType == 1) {
            $('#barItemAdj').show();
        } else {
            $('#barItemAdj').hide();
        }


    });
}

function updateNote(notes,  pId) {

    $.ajax({
            method: "POST",
            url: "ajax.php",

            data: {
                actionType: 'updateProductNote',
                outLetId: '<?php echo $_GET['outLetId'];?>',
                pId: pId,
                fromDate: '<?php echo $_GET['fromDate'];?>',
                notes: notes
            }
        })
        .done(function(note) {

        });
}




$(document).ready(function() {

    var availableTags = [
        <?php 

    foreach($proRows as $pId=>$pName){
        $itemName = $pName.'('.$pId.')';
        echo "'$itemName'".',';
    }
    ?>
    ];

    $("#item").autocomplete({
        source: availableTags
    });
    $("#itemNotes").autocomplete({
        source: availableTags
    });
});

function myFunction() {
    var parentDivs = document.getElementsByClassName("outletBody-Task");
    var input = document.getElementById("search");
    var filter = input.value.toLowerCase();

   for (var i = 0; i < parentDivs.length; i++) {
        var parentDiv = parentDivs[i];
        var childNodes = parentDiv.getElementsByClassName("otltBd-itm");
        var foundInChild = false;


        for (var j = 0; j < childNodes.length; j++) {
            var childNode = childNodes[j];
            var text = childNode.textContent || childNode.innerText;


            if (text.toLowerCase().includes(filter)) {
                if (!foundInChild) {
                    parentDiv.style.display = "flex";  
                }
                childNode.style.display = "flex";  
                foundInChild = true;
            } else {
                childNode.style.display = "none";  // Hide non-matching child
            }
        }

        if (!foundInChild) {
            parentDiv.style.display = "none";
        }
    }
}

function debounce(fn, delay) {
    let timeout;
    return function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, arguments), delay);
    };
}


document.getElementById("search").addEventListener("input", debounce(myFunction, 300));

		$("#search").on("search", function(evt){
		if($(this).val().length == 0){
		resetData();
		}
		});

		function resetData() {

		$('#search').val('');
		myFunction();
		}


$(document).ready(function () {

  $(".cstBtn-Sale").on("click", function () {

       
        if( $("#ShowHideZeroVar.actvSale-Cst").length )
        {
            $('.hideVariance').hide();
        }
        else
        {
            $('.hideVariance').show();
        }


        if ($(".hideBtn-Info").hasClass("actvSale-Cst")) {
            $(".otlt-stkPrc").hide();
            $(".otltBd-stkPrc").hide();

            $(".otltBd-Avr").hide();
            $(".otlt-avgUsg").hide();
            
        } else {
            $(".otltBd-Avr").show();
            $(".otlt-avgUsg").show();
        }

  });
});

$(document).ready(function () {

    $(".cstBtn-Qty").on("click", function () {

        
        

            $('.mblStock-Sale').toggle();

            

            $(".cstBtn-Qty").toggleClass('actvSale-Cst');
            
        

    });

    
});
document.addEventListener('DOMContentLoaded', () => {
  const dateInputs = document.querySelectorAll('html[dir="rtl"] .otltBd .form-control[type="date"]');

  dateInputs.forEach(input => {
    input.style.setProperty('--calendar-icon-position', 'left');
  });
});
</script>
</body>

</html>