<?php 
include('inc/dbConfig.php'); //connection details
if ( !isset($_SESSION['adminidusername'])) 
{
	echo "<script>window.location='login.php'</script>";
}




//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

if( isset($_GET['hotelId']) )
{
	$merchantId = $hotelsArr[$_GET['hotelId']];
	$date = isset($_GET['date']) ? date('Y-m-d', strtotime($_GET['date']) ) : '2021-11-10';
	$postData['Date'] = $date;
	$easyData = getPosData($merchantId, $postData);
	$easyData  = json_decode($easyData, true);

	if( $easyData['status'] == 'success' )
	{
		$easyDataArr = $easyData['data'];
	}

	$action = 'easy';
	if( is_array($easyDataArr) && !empty($easyDataArr)  )
	{
		$outLetRow = $easyDataArr[0];

		if( isset($outLetRow['InventoryItems']) && !empty($outLetRow['InventoryItems']) )
		{
			$outLetsRows = getMappedOutLetsByHotelId($_GET['hotelId']);
			
			if( !empty($outLetsRows) )
			{
				foreach($outLetsRows as $outLetId)
				{
					
					//get sale amount for this outlet
					$sale = getSaleAmtEasy($outLetId, $outLetRow['Categories']);
			
					//end
					if(!$sale)
					{
						continue;
					}
					$outLetDataArr = ['outLetId' => $outLetId, 'date' => $date, 'sales' => $sale];
					$parentId = insertUpdateEasyDailyData($outLetDataArr);

					
					foreach($outLetRow['InventoryItems'] as $itemData)
					{
						checkEasyBarCodesEasy($outLetsRows, $itemData);//check for non existing barcode these outlets

						insertUpdateDailyDataItemsEasy($outLetId, $parentId, $date, $itemData);
					}
				}
			}
			
			
		}
	
		echo "<script>window.location='revenueCenterReport.php?&$action=1'</script>";
		
	}
}

?>