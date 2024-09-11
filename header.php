<?php
//unset session variable $_SESSION['errorQtyOrderId'] && $_SESSION['errorQty']  
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
if($curPageName  != 'runningOrders.php')
{
	unset($_SESSION['errorQty']);
	unset($_SESSION['errorQtyOrderId']);
}

//Get login account name by there accountId
//$accountName = ($_SESSION['accountId'] == '1') ? 'זנזיבר שלנו' : ($_SESSION['accountId'] == '2' ? 'בְּנִיָה' : '
//חוף מנאראני');

//$accountName = ($_SESSION['accountId'] == '1') ? 'Our Zazibar' : ($_SESSION['accountId'] == '2' ? 'Construction' : 'Mnarani Beach');

$sql = " SELECT * FROM tbl_client WHERE id = '".$_SESSION['accountId']."' ";
$sqlQry = mysqli_query($con, $sql);
$qryRow = mysqli_fetch_array($sqlQry);
$accountName = $qryRow['accountName'];


?>


<div class="user-info d-inline-flex gap-2 align-items-center">
      <div class="col text-capitalize"> 
            <span class="user-name"><?php echo $_SESSION['name'] ?></span><span class="d-none d-sm-inline-block">|</span>
            <span class="account-name d-block d-sm-inline-block"><?php echo $accountName ?></span>
      </div> 
      <?php
					if( $qryRow['logo'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath.'/clientLogo/'.$qryRow['logo'] ) )
					{

						echo '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$qryRow['logo'].'" width="45" class="headLogo-Img">';

					} 
					?>
</div>