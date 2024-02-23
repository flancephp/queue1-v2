<?php
include('inc/dbConfig.php'); //connection details

ini_set('max_execution_time', '0'); // for infinite time of execution 

$outLetId = 32;
$account_id = 1;
$sql = "SELECT * FROM tbl_daily_import_items   WHERE outLetId = '".$outLetId."'  AND account_id = '".$account_id."'     order by importedOn limit 100   ";
$qry = mysqli_query($con, $sql);

$i=0;
while( $reportRow = mysqli_fetch_array($qry) )
{			
			
		 $usage = ($reportRow['openStock']+$reportRow['issueIn']+$reportRow['barControl'])-($reportRow['closeStock']);
		
		$usagePerGuest =  0;
		$usageAvgArr = getAvgUsageOldData($account_id, $outLetId, $reportRow['barCode'], $usage, $reportRow['importedOn']);
		$usageAvg = $usageAvgArr['usageAvg'];
		$usageCnt = $usageAvgArr['usageCnt'];
		
		$qryUpdate = " UPDATE tbl_daily_import_items SET  
					
					usagePerDay = '".$usage."',
					usageCnt = '".$usageCnt."',
					usageAvg = '".$usageAvg."',
					usagePerGuest = '".$usagePerGuest."',
					isUsageUpdated=1,
					closeStockDone=1
					
			  WHERE id = '".$reportRow['id']."'  AND account_id = '".$account_id."'     ";
		mysqli_query($con, $qryUpdate);
		
		$i++;
		
		echo '====================================================';
		echo '<br><br><br>';
			
}

die('done');
?>