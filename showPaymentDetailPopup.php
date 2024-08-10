<?php 
include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


if (!isset($_SESSION['adminidusername'])) 
{

echo "<script>window.location = 'login.php'</script>";

}  



if (isset($_GET['orderId'])) {

$sql = "SELECT tp.*, od.price ordPrice, od.curPrice ordCurPrice,  IF(u.name!='',u.name,tp.unitP) purchaseUnit, od.qty ordQty, od.totalAmt, od.factor ordFactor, od.qtyReceived FROM tbl_order_details od 
INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
WHERE od.ordId = '".$_GET['orderId']."'  AND tp.account_id = '".$_SESSION['accountId']."'  ";
$ordQry = mysqli_query($con, $sql);

}

$cmd="SELECT * FROM tbl_orders WHERE id='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' ";
$ordersQry = mysqli_query($con, $cmd);
$ordersRow = mysqli_fetch_array($ordersQry);

$sqlQry = " SELECT * FROM tbl_currency WHERE id='".$ordersRow['ordCurId']."' ";
$sqlResult = mysqli_query($con, $sqlQry);
$sqlResultRow = mysqli_fetch_array($sqlResult);
$otherCurCode = $sqlResultRow['curCode'];

$qry = "SELECT * FROM tbl_payment WHERE orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' order by id limit 1 "; 
$result = mysqli_query($con, $qry);
$paymentRow = mysqli_fetch_array($result);

$sqlSet = " SELECT * FROM tbl_payment_mode WHERE id='".$paymentRow['paymentType']."'  AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $sqlSet);
$payModeRow = mysqli_fetch_array($resultSet);


$qry = "SELECT * FROM tbl_supplier_payment_info WHERE  orderId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."' ";
$resultSet = mysqli_query($con, $qry);
$checkRow = mysqli_fetch_array($resultSet);

$content = '<!DOCTYPE html>
<html lang="en">
  <head>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
      <title>'.showOtherLangText('Payment PDF').'</title>
  </head>
  <body style="margin: 0; padding: 0; font-family: inter;">
    <div style="margin: auto; max-width: 800px; position: relative; border-radius: 5px;">
      <div  style="position:absolute;left:0;top:0;bottom:0;width:9px;border-radius:0px 10px 10px 0px;z-index:1;background-color:#596BF3;"></div>
      <div style="padding: 1% 4% ;">
        <!-- Header Section -->
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td>';
   
   if (isset($_GET['orderId']) && $paymentRow['paymentStatus']==1 ) 
{ 

    $content .= '<p style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#596BF3 !important; margin-bottom: 0;">
                            '.showOtherLangText('PAID').'</p>';
}elseif($_GET['paymentStatus']==2) { 
   
    $content .= '<p style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#596BF3 !important; margin-bottom: 0;">
                            '.showOtherLangText('REFUNDED').'</p>';
                  

                    }else{ 

     $content .= '<p style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#596BF3 !important; margin-bottom: 0;">
                            '.showOtherLangText('PENDING').'</p>';
                             }
   $content .= '<p style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#666C85;margin-top: 0rem;">'.  showOtherLangText('PAYMENT').'</p>';
    $content .= '</td>
          <td style="text-align: right;">';
          $clientQry = " SELECT * FROM tbl_client WHERE id = '".$_SESSION['accountId']."' ";
$clientResult = mysqli_query($con, $clientQry);
$clientResultRow = mysqli_fetch_array($clientResult);

if($clientResultRow['logo'] !='' && file_exists(dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientResultRow['logo']))
{   

    $content .= '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientResultRow['logo'].'" width="100" style="object-fit: contain;">';

}
     $content .= '</td>
        </tr>
      </table>

<!-- Supplier Invoice and Payment Info -->
<table style="width: 100%;border-collapse: collapse;">
  <tr>
    <td style="width: 50%;">
      <table style="width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">'.showOtherLangText('Supplier Invoice').' #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$checkRow['supplierInvoice'].'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">'.showOtherLangText('Payment').' #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.setPaymentId($paymentRow['id']).'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">'.showOtherLangText('Task').' #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$ordersRow['ordNumber'].'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">'.showOtherLangText('Date').'#</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$paymentRow['paymentDateTime'].'</td>
          </tr>
        </tbody>
      </table>
    </td>';
   $sql = " SELECT * FROM tbl_country WHERE id = '".$clientResultRow['country']."' ";
$resSet = mysqli_query($con, $sql);
$resultRow = mysqli_fetch_array($resSet);
   $content .= '<td>
      <table style=" text-align: right; width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$clientResultRow['accountName'].'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$clientResultRow['address_one'].','.$clientResultRow['address_two'].'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$clientResultRow['city'].','.$resultRow['name'].'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$clientResultRow['email'].'</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">'.$clientResultRow['phone'].'</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>';


   $content .= '<br>
        <div>
          <p style="font-size:16px;font-weight:600;line-height:19.36px;text-align:left; margin-bottom: .5rem;">'.showOtherLangText('Payment To:').'</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">'. $checkRow['supplierName'].'</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">'. $checkRow['supplierAddress'].'</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">'. $checkRow['supplierEmail'].'</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">'. $checkRow['supplierPhone'].'</p>
        </div>';
    $content .= '<br>
        <table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
          <thead style="border-radius:10px !important;">
            <tr style="color:white;height:48px;background:#A9B0C0 !important;background-color: #f5f5f5;">
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;">#</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;width: 30%;">'. showOtherLangText('Item').'</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;">'. showOtherLangText('Unit').'</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;">'. showOtherLangText('Order Quantity').'</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; padding-left:.5rem;">'. showOtherLangText('Receive Quantity').'</th>';
          
          if ($ordersRow['ordCurId'] > 0) {
                  

                           $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; background:#7A89FF;text-align:center;padding-left:.5rem;">
                                '. showOtherLangText('Price').'('. $sqlResultRow['curCode'].')</th>';

                            }else{
                                $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; background:#7A89FF;text-align:center;padding-left:.5rem;">
                                '. showOtherLangText('Price').'('. $getDefCurDet['curCode'].')</th>';
                            }

          if ($ordersRow['ordCurId'] > 0) {

                               
                             $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; font-weight: 700; background:#7A89FF;text-align:center;padding-left:.5rem;">'. showOtherLangText('Total').'('. $sqlResultRow['curCode'].')</th>';
                            }else{

                               
                                $content .= '<th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px; font-weight: 700; background:#7A89FF;text-align:center;padding-left:.5rem;">'. showOtherLangText('Total').'('. $getDefCurDet['curCode'].')</th>';

                            }
         
          $content .= '</tr>
          </thead><tbody>';
        $sql = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
            INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
            WHERE tod.ordId = '".$_GET['orderId']."'  AND tod.account_id = '".$_SESSION['accountId']."'  and tod.customChargeType=1 ORDER BY cif.itemName ";
            $resultSet=mysqli_query($con, $sql);   
            $x=0;
            while ($showCif= mysqli_fetch_array($resultSet))
            {
              $x++;
        $content .= '<tr style="height:48px;">
              <td style="font-size: 12px; padding: .5rem;">'.$x.'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center; width: 30%;">'. $showCif['itemName'].'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">'. $showCif['unit'].'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">1</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">1</td>';
            if ($ordersRow['ordCurId'] > 0) {

                             
                                $content .=  '<td style="font-size: 12px; padding: .5rem;text-align:center;">'. showOtherCur($showCif['amt']*$sqlResultRow['amt'], $ordersRow['ordCurId']).'</td>';

                            }else{
                                  $content .=  '<td style="font-size: 12px; padding: .5rem;text-align:center;">'.getPriceWithCur($showCif['amt'],$getDefCurDet['curCode']).'</td>';
                            }

            if ($ordersRow['ordCurId'] > 0) {


                              
                                 $content .=  '<td style="font-size: 12px; font-weight: 700; padding:.5rem;">'. showOtherCur($showCif['amt']*$sqlResultRow['amt'], $ordersRow['ordCurId']).'</td>';
                            }else{
                                 $content .=  '<td style="font-size: 12px; font-weight: 700; padding:.5rem;">'.getPriceWithCur($showCif['amt'],$getDefCurDet['curCode']).'</td>';

                            }
            
            $content .=  '</tr>';
            }
            $sum=0;

            while($row = mysqli_fetch_array($ordQry))
              {
              $x++;
              $content .= '<tr style="height:48px;">
              <td style="font-size: 12px; padding: .5rem;">'.$x.'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center; width: 30%;">'. $row['itemName'].'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">'. $row['purchaseUnit'].'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">'. $row['ordQty'].'</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">'. $row['qtyReceived'].'</td>';
              if ($ordersRow['ordCurId'] > 0) {

                            $content .=  '<td style="font-size: 12px; padding: .5rem;text-align:center;">'. showOtherCur($row['ordCurPrice']*$row['factor'],($ordersRow['ordCurId'])).'</td>';

                            }else{

                             $content .=  '<td style="font-size: 12px; padding: .5rem;text-align:center;">'.getPriceWithCur($row['ordPrice']*$row['factor'],$getDefCurDet['curCode']).'</td>';

                            }

                 if ($ordersRow['ordCurId'] > 0) {


                              
                            $content .=  '<td style="font-size: 12px; font-weight: 700; padding:.5rem;">'. showOtherCur($row['ordQty']*$row['ordCurPrice']*$row['factor'], ($ordersRow['ordCurId'])).'</td>';

                            }else{

                              
                            $content .=  '<td style="font-size: 12px; font-weight: 700; padding:.5rem;">'.getPriceWithCur($row['ordPrice']*$row['ordQty']*$row['factor'],$getDefCurDet['curCode']).'</td>';

                            }
              }
         $content .=  '</tbody>
        </table>

        <div  style="width:100%;height:3px;background:#7A89FF;margin:.5rem 0;margin-top: 20px;"></div>

        <br>
  <table style=" width:100%; border-collapse: collapse;">
  <tr>
    <td style="vertical-align: top; width:50%; ">
      <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left; margin-bottom: 0;">'. showOtherLangText('Payment Method').':</p>';
      $sqlSet= " SELECT * FROM tbl_payment WHERE orderId='".$paymentRow['orderId']."'  AND account_id = '".$_SESSION['accountId']."' order by id limit 1 ";

                $result= mysqli_query($con, $sqlSet);
                $resultQryRow= mysqli_fetch_array($result);
                
                  if( isset($resultQryRow['bankAccountId']) )
                  { 
                    $sqlSet = " SELECT * FROM tbl_payment_mode where id='".$resultQryRow['paymentType']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                    $resultSet = mysqli_query($con, $sqlSet);
                    $payModeRow = mysqli_fetch_array($resultSet);

                    $sqlSet = " SELECT * FROM  tbl_accounts WHERE id='".$resultQryRow['bankAccountId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
                    $resultSet = mysqli_query($con, $sqlSet);
                    $accDet = mysqli_fetch_array($resultSet);

                                        $curQry = " SELECT * FROM tbl_currency WHERE id='".$resultQryRow['currencyId']."' AND account_id='".$_SESSION['accountId']."' "; 
                                        $curresult = mysqli_query($con, $curQry);
                                        $curRow = mysqli_fetch_array($curresult);

                                        if ($resultQryRow['currencyId'] > 0) 
                                        {

                                            $curCode = $curRow['curCode'];
                                            $curName = $curRow['currency'];

                                        }else{

                                            $curCode = "$";
                                            $curName = "USD";
                                        }
      $content .= '<p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">'.$payModeRow['modeName'].'</p>';
      $content .= '<p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">'.$accDet['accountName'].'</p>';
                        }
    $content .= '</td>
    <td style="vertical-align: top; width:50%;">
      <table style="margin-top:-1rem;min-width:350px;height: 38px; border-collapse: collapse;">
        <tbody>';
    $curAmtVal = 0;
              if($ordRow['ordCurId'] > 0)
              {
                $curDet = getCurrencyDet($ordRow['ordCurId']);
                $curAmtVal = $curDet['amt'];
              }
              

                    

  //get the sum of all product and item level charges  
              $sqlSet="SELECT SUM(totalAmt) as sum1, SUM(curAmt) AS sum2 from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
              $resultSet = mysqli_query($con, $sqlSet);
              $chargeRow = mysqli_fetch_array($resultSet);  
              $chargePrice=$chargeRow['sum1'];
              $totalPriceOther=($chargeRow['sum2']);          


                            $ordCount="SELECT * from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                            $ordCountResult = mysqli_query($con, $ordCount);
                            $ordCountRow = mysqli_num_rows($ordCountResult);

                            if ($ordCountRow > 0)
                            {
                if ($ordersRow['ordCurId'] > 0)
                                {

            $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:16px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. showOtherLangText('Sub Total').'</th>
            <th style="font-size:16px;font-weight:600; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'.showOtherCur($totalPriceOther, $ordersRow['ordCurId']).'</th>
            </tr>';
                 }else{
             $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:16px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. showOtherLangText('Sub Total').'</th>
            <th style="font-size:16px;font-weight:600; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'.getPriceWithCur($chargePrice,$getDefCurDet['curCode']).'</th>
             </tr>';

         }
                            }


  //Starts order level fixed discount charges
  $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
  INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
  WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

  $ordQry = mysqli_query($con, $sql);

  $fixedCharges = 0;
  $fixedChargesOther = 0;

  $sql = " SELECT SUM(od.totalAmt) AS totalFixedCharges, tp.feeName FROM tbl_order_details od 
  INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
  WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName   ";
  $sumQry = mysqli_query($con, $sql);
  $totalSum= mysqli_fetch_array($sumQry);

  $totalFixedCharges=$totalSum['totalFixedCharges'];


  while($row = mysqli_fetch_array($ordQry))//show here order level charges
  {
    $fixedCharges =$row['price'];
    $fixedChargesOther = $row['curAmt'];
    $totalFixedChargesOther = $fixedChargesOther;

        if ($ordersRow['ordCurId'] > 0)
                {
                $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:16px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. $row['feeName'].'</th>
            <th style="font-size:16px;font-weight:600; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'. showOtherCur($fixedChargesOther, $ordersRow['ordCurId']).'</th>
             </tr>';
               } else {
               $content .= '<tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:16px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. $row['feeName'].'</th>
            <th style="font-size:16px;font-weight:600; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'.getPriceWithCur($fixedCharges,$getDefCurDet['curCode']).'</th>
             </tr>'; 

               }
  } //Ends order lelvel fixed discount charges
        


//Start order level discoutns

      $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
      WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
      $ordQry = mysqli_query($con, $sql);

      $perCharges = 0;
      $itemIds = [];
      $totalCharges = 0;
      while($row = mysqli_fetch_array($ordQry))//show here order level charges
      {
        $itemIds[] = $row['customChargeId'];              
        $totalCharges = $row['totalAmt'];
        $perCharges += $row['totalAmt'];

        $discountPercent=$chargePrice*$totalCharges/100;
                $discountPercentOther=$totalPriceOther*$totalCharges/100;
        $totalDiscountPercent=$chargePrice*$perCharges/100;
        $totalDiscountPercentOther=$totalPriceOther*$perCharges/100;
        if($row)
        {
          if ($ordersRow['ordCurId'] > 0)
                    {

                    }
      } }//End of order level discount

//Starts order level tax discount charges
      $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
      INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
      WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
      $ordQry = mysqli_query($con, $sql);

      $taxCharges = 0;
  while($row = mysqli_fetch_array($ordQry))//show here order level charges
  {
    $tax = $row['price'];

    $taxCharges=(($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
        $taxChargesOther=(($totalPriceOther+$totalFixedChargesOther+$totalDiscountPercentOther)*$tax/100);
    $totalTaxCharges += (($chargePrice+$totalFixedCharges+$totalDiscountPercent)*$tax/100);
    $totalTaxChargesOther += (($totalPriceOther+$totalFixedChargesOther+$totalDiscountPercentOther)*$tax/100);

    if ($ordersRow['ordCurId'] > 0)
        {

          }
        } //end order level tax discount charges



        $sqlSet = "SELECT SuM(totalAmt) AS totalFixedDiscount, SUM(curAmt) AS totalFixedDiscountOther FROM tbl_order_details od 
        INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
        WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
        $resultSet = mysqli_query($con, $sqlSet);
        $fixedDiscountRow = mysqli_fetch_array($resultSet); 
        $totalFixedDiscount= $fixedDiscountRow['totalFixedDiscount'];
        $totalFixedDiscountOther= $fixedDiscountRow['totalFixedDiscountOther'];

        $netTotalAmt= ($chargePrice+ $totalTaxCharges+$totalDiscountPercent+$totalFixedDiscount);
        $netTotalAmtOther= ($totalPriceOther+ $totalTaxChargesOther+$totalDiscountPercentOther+$totalFixedDiscountOther);

      $content .= '</tbody>';
      if ($ordersRow['ordCurId'] > 0)
        {


    
        
      $content .= '<tbody>
          <tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. showOtherLangText('Grand Total').'('. $otherCurCode.')</th>
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'. showOtherCur($netTotalAmtOther, $ordersRow['ordCurId']).'</th>
          </tr>
        </tbody>
        <tbody>
          <tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. showOtherLangText('Grand Total').' ('. $getDefCurDet['curCode'].')</th>
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'.getPriceWithCur($netTotalAmt,$getDefCurDet['curCode']).'</th>
          </tr>
        </tbody>';
         }else{
          $content .= '<tbody><tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">'. showOtherLangText('Grand Total').' ('. $getDefCurDet['curCode'].')</th>
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">'.getPriceWithCur($netTotalAmt,$getDefCurDet['curCode']).'</th>
          </tr>
        </tbody>';
         }
      $content .= '</table>
    </td>
  </tr>
</table>

        <br>
        <br>
        <br>
      </div>
    </div>
  </body>
</html>';


//echo $content;