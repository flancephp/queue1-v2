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
                            <h1 class="h1">Casa Bar</h1>
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
                                    <h1 class="h1">Casa Bar</h1>
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
                    <section class="ordDetail revCenter oltReport">
                        <div class="container hisData">
                            <div class="usrBk-Btn">
                                <div class="btnBg">
                                    <a href="revenueCenterReport.php" class="sub-btn std-btn mb-usrBkbtn"><span
                                            class="mb-UsrBtn"><i class="fa-solid fa-arrow-left"></i></span> <span
                                            class="dsktp-Btn">Back</span></a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="hstCal">
                                        <div class="his-featBtn">
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
		
		while( $row = mysqli_fetch_array($outLetItemsQry) )
		{
				$x++;
				
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
                                        <div class="otltBd-hdImg">
                                            <img src="'.$img.'" alt="Item">
                                        </div>
                                        <div class="otltBd-itm">
                                            <p>'. $row['itemName'].'</p>
                                        </div>
                                        <div class="infoBtn-Hide">
                                            <div class="outletInfo">
                                                <div class="mbStk-Detail">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="otltBd-opnStk">
                                                                <p class="mbSale-Head">Open Stock</p>
                                                                <p class="mblStock-Sale">'.showValue($openStock).'</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p class="mbSale-Head">Issues In</p>
                                                                <p class="mblStock-Sale fw-bold">'. showValue($issueIn).'</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                                <p class="mbSale-Head">Adjust</p>
                                                                <p class="mblStock-Sale">'. showValue($adjustment).'</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p class="mbSale-Head">Sales POS</p>
                                                                <p class="mblStock-Sale fw-bold">'. $sales.'</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p class="mbSale-Head">Sales Bar.C</p>
                                                                <p class="mblStock-Sale fw-bold">'. $barControl.'</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p class="mbSale-Head">Close Stock</p>
                                                                <p class="mblStock-Sale">'. $closeStock.'</p>
                                                            </div>
                                                        </div>
                                                        <div class="itmInfo-Otlt currItm-Info">
                                                            <div class="otltBd-opnStk">
                                                                <p>'. $openStockAmt.'</p>
                                                            </div>
                                                            <div class="otltBd-isn">
                                                                <p>'. $issueInAmt.'</p>
                                                            </div>
                                                            <div class="otltBd-Ajst">
                                                            <p>'. $adjustmentAmt.'</p>
                                                            </div>
                                                            <div class="otltBd-Pos">
                                                                <p>'. $salesAmt.'</p>
                                                            </div>
                                                            <div class="otltBd-slBar">
                                                                <p>'. $barControlAmt.'</p>
                                                            </div>
                                                            <div class="otltBd-clStk">
                                                                <p>'. $closeStockAmt.'</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbShow-Otlt">
                                                    <div class="itmInfo-Otlt">
                                                        <div class="otltBd-usg">
                                                            <p class="mbSale-Head">Cost/Usage</p>
                                                            <p class="mblStock-Sale fw-bold">'. $usage.'</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p class="mbSale-Head">Variance</p>
                                                            <p class="mblStock-Sale fw-bold">'. $variancesVal.'</p>
                                                        </div>
                                                    </div>
                                                    <div class="itmInfo-Otlt currItm-Info">
                                                        <div class="otltBd-usg">
                                                            <p>'. $usageAmt.'</p>
                                                        </div>
                                                        <div class="otltBd-var">
                                                            <p>'. $varienceAmt.'</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="otltBd-hdNote">
                                                    <p class="mbSale-Head mbHide-Note">Note</p>
                                                    <input type="text" class="form-control note-itm outletNote" value="'. $note.'"
                                                        placeholder="Note">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flipInfo-Clm">
                                            <div class="outletInfo">
                                                <div class="mbFlip-Dtl">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-stkPrc">
                                                            <p class="mbSale-Head">Open Stock</p>
                                                            <p class="opnStk-BdVal">'. $openStock.'</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbStk-Flp">
                                                    <div class="mbHide-Otlt">
                                                        <div class="itmInfo-Otlt">
                                                            <div class="unitBd-div">
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
                                                                <p class="mblAvg-Dtl">'. $usageAvg.'</p>
                                                                <p class="mblAvr-Usg">24.76 $</p>
                                                            </div>
                                                            <div class="otltBd-Min">
                                                                <p class="mbSale-Head">Min</p>
                                                                <p class="mblAvg-Dtl">'. $row['outletMinQty'].'</p>
                                                            </div>
                                                            <div class="otltBd-Max">
                                                                <p class="mbSale-Head">Max</p>
                                                                <p class="mblAvg-Dtl">'. $row['outletMaxQty'].'</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mbFlip-DtlReq">
                                                    <div class="mbFlp-Shw">
                                                        <div class="otltBd-Req">
                                                            <p class="mbSale-Head">Requisition</p>
                                                            <p class="mblAvg-Dtl fw-bold">'. $requisition.'</p>
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

                        <div class="container saleSrch-Cst">
                            <div class="row">
                                <div class="col-md-6 slCst-Srch">
                                    <div class="input-group srchBx">
                                        <input type="search" class="form-control" placeholder="Search" id="srch"
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
                                        <a href="javascript:void(0)" class="cstBtn-Sale ShowHideZeroVar">
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
                                <div class="container outletTask">
                                    <div class="otltTbl-head">
                                        <div class="otlt-hdImg"></div>
                                        <div class="otlt-itm">
                                            <p>Item</p>
                                            <p>Totals</p>
                                        </div>
                                        <div class="flipClm-Otlt">
                                            <div class="mbStock">
                                                <div class="otlt-opnStk">
                                                    <p>Open Stock</p>
                                                    <p class="stkVal-Otlt"><?php echo getNumFormtPrice($openStockAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-isn">
                                                    <p>Issues In</p>
                                                    <p class="stkVal-Otlt"><?php echo getNumFormtPrice($issueInAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-Ajst">
                                                    <p>Adjust</p>
                                                </div>
                                                <div class="otlt-Pos">
                                                    <p>Sales POS</p>
                                                    <p class="posValue"><?php echo getNumFormtPrice($salesAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-slBar">
                                                    <p>Sales Bar.Ctrl</p>
                                                    <p class="posValue"><?php echo getNumFormtPrice($barControlAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-clStk">
                                                    <p>Close Stock</p>
                                                    <p class="stkVal-Otlt"><?php echo getNumFormtPrice($closeStockAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-usg">
                                                    <p>Usage/ Cost</p>
                                                    <p class="cstVal-Otlt"><?php echo getNumFormtPrice($usageAmtTot,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                                <div class="otlt-var">
                                                    <p>Variance</p>
                                                    <p class="varnVal-Otlt"><?php echo getNumFormtPrice($varienceTotalAmt,$getDefCurDet['curCode'], 2);?></p>
                                                </div>
                                            </div>
                                            <div class="otlt-hdNote"></div>
                                        </div>
                                        <div class="flipClm-Hide">
                                            <div class="mbStock">
                                                <div class="otlt-stkPrc">
                                                    <p>Stock price</p>
                                                </div>
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
    <div class="modal" tabindex="-1" id="adjust" aria-labelledby="add-PhyStorageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h1 class="modal-title h1">Adjustment</h1>
                </div>
                <div class="modal-body">
                    <form class="addUser-Form row">
                        <input type="text" class="form-control" id="ajstName" placeholder="Item">
                        <input type="date" class="form-control" id="ajstDate">
                        <input type="number" class="form-control" id="ajstNum" placeholder="1">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">Save</button>
                    </div>
                    <div class="btnBg">
                        <button type="submit" class="btn sub-btn std-btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</script>
</body>

</html>