<?php 
include('inc/dbConfig.php'); //connection details
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

if ( isset($_POST['TotalAmount']) && $_POST['accountId'] && $_POST['action']=="payment" ) 
{
	
	$total_amount = $_POST['TotalAmount'];
	$total_amount= str_replace(",","",$total_amount);

	$accountQry = " SELECT * FROM tbl_accounts WHERE id='".$_POST['accountId']."' AND account_id='".$_SESSION['accountId']."' ";
	$accountResult = mysqli_query($con, $accountQry);
	$accountRow = mysqli_fetch_array($accountResult);
	$currencyId = $accountRow['currencyId'];
	$curDet = getCurrencyDet($accountRow['currencyId']);
	$accountBalance = number_format($accountRow['balanceAmt'], $curDet['decPlace']);
	$accountNumber = $accountRow['accountNumber'];

	if ($currencyId > 0)
	 {
		
		$curQry = " SELECT amt, currency, curCode from tbl_currency where id='$currencyId' AND account_id='".$_SESSION['accountId']."' ";
		$curQryResult = mysqli_query($con, $curQry);
		$curQryRow = mysqli_fetch_array($curQryResult);
		$otherCurAmt = $curQryRow['amt'];
		$curCode = $curQryRow['curCode'];
		$resHtml = $curQryRow['currency'];

		//convert into selected amount
		$accountBalance = ($accountBalance.' '.$curCode);
	/*echo $currencyId .'=='. $getDefCurDet['id'];
	echo "<br>";
	echo $total_amount.'*'.$otherCurAmt;*/
	
		$payableAmt = $currencyId == $getDefCurDet['id'] ? $total_amount : ($total_amount*$otherCurAmt);
	
		$totalConvUsdAmtOther = showOtherCur($payableAmt, $currencyId);

		$arr = array($resHtml,$totalConvUsdAmtOther,$accountBalance,$accountNumber,$currencyId);
		echo json_encode($arr);
			
	 	}
		//else{

	// 	$resHtml = $getDefCurDet['currency'];
	// 	$accountBalance = ($accountBalance.' '.$getDefCurDet['curCode']);
	// 	$totalConvUsdAmt = getNumFormtPrice($total_amount, $getDefCurDet['curCode']);

	// 	$arr = array($resHtml,$totalConvUsdAmt,$accountBalance,$accountNumber,$currencyId);
	// 	echo json_encode($arr);

	// }

}

?>