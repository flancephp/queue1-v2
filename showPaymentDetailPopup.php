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

$content = '
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="./PDF Design_files/css2" rel="stylesheet">
  </head>
  <body style="margin: 0; padding: 0; font-family: inter;">
    <div style="margin: auto; max-width: 800px; position: relative; border: 1px solid #ddd; border-radius: 5px;">
      <div  style="position:absolute;left:0;top:0;bottom:0;width:9px;border-radius:0px 16px 16px 0px;z-index:1;background-color:#596BF3;"></div>
      <div style="padding: 1% 4% ;">
        <div  style="display: flex; justify-content: space-between; align-items: center;">
          <div>
            <p  style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#596BF3 !important; margin-bottom: 0; color: #000;">PAID</p>
            <p  style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#666C85;margin-top: 0rem;">PAYMENT</p>
          </div>
          <div>
            <img width="100" height="100" src="https://queue1.net/qa1/uploads/queue1/clientLogo/17152523391706978392ourzanzibar.png" alt="logo">
          </div>
        </div>
        <div style="margin-top: 16px; display: flex; justify-content: space-between;">
          <table  style="width: 50%; border-collapse: collapse;">
            <tbody>
              <tr>
                <td  style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Supplier invoice #</td>
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">1233</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Payment #</td>
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">001538</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Task #</td>
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">126515</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Date #</td>
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">2024-06-23 07:23:50</td>
              </tr>
            </tbody>
          </table>
          <table  style="text-align: right; border-collapse: collapse;">
            <tbody>
              <tr>
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">Our Zazibar</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">P.o Box 4146,Jambiani</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">Zanzibar,TANZANIA</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">inventory@our-zanzibar.com</td>
              </tr>
              <tr style="border-top:none !important;">
                <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">+255742998277</td>
              </tr>
            </tbody>
          </table>
        </div>
        <br>
        <div>
          <p style="font-size:16px;font-weight:600;line-height:19.36px;text-align:left; margin-bottom: .5rem;">Payment To:</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">Green Grocery</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">07711122</p>
        </div>
        <br>
        <table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
          <thead style="background: #A9B0C0 !important;">
            <tr style="color:white;height:48px;background:#A9B0C0 !important;background-color: #f5f5f5;">
              <th style="font-weight: 700; font-size: 12px; padding-left:.5rem;border-radius:10px 0 0 10px !important;">#</th>
              <th style="font-weight: 700; font-size: 12px; padding-left:.5rem;width: 30%;">Item</th>
              <th style="font-weight: 700; font-size: 12px; padding-left:.5rem;">Unit</th>
              <th style="font-weight: 700; font-size: 12px; padding-left:.5rem;">Ordered <br>Quantity</th>
              <th style="font-weight: 700; font-size: 12px; padding-left:.5rem;">Receive <br>Quantity</th>
              <th style="font-weight: 700; font-size: 12px; background:#7A89FF;text-align:center;padding-left:.5rem;">Price ($)</th>
              <th style="font-weight: 700; font-size: 12px; font-weight: 700; background:#7A89FF;text-align:center;padding-left:.5rem;border-radius:0 10px 10px 0 !important;">Total ($)</th>
            </tr>
          </thead>
          <tbody >
            <tr style="height:48px;">
              <td style="font-size: 12px; padding:0 .5rem;">1</td>
              <td style="font-size: 12px; padding:0 .5rem;text-align:center; width: 30%;">Item</td>
              <td style="font-size: 12px; padding:0 .5rem;text-align:center;">Kg</td>
              <td style="font-size: 12px; padding:0 .5rem;text-align:center;">20</td>
              <td style="font-size: 12px; padding:0 .5rem;text-align:center;">20</td>
              <td style="font-size: 12px; padding:0 .5rem;text-align:center;">3.0000 $</td>
              <td style="font-size: 12px; font-weight: 700; padding:0 .5rem;">2.0000 $</td>
            </tr>
            <tr style="height:48px;">
              <td style=" font-size: 12px; padding:0 .5rem;">1</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;width: 30%;">Item</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">Kg</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">20</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">20</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">3.0000 $</td>
              <td style=" font-size: 12px; font-weight: 700; padding:0 .5rem;">2.0000 $</td>
            </tr>
            <tr style="height:48px;">
              <td style=" font-size: 12px; padding:0 .5rem;">1</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center; width: 30%;">Item</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">Kg</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">20</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">20</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">3.0000 $</td>
              <td style=" font-size: 12px; font-weight: 700; padding:0 .5rem;">2.0000 $</td>
            </tr>
            <tr style="height:48px;">
              <td style=" font-size: 12px; padding:0 .5rem;">1</td>
              <td style=" font-size: 12px; padding:0 .5rem; text-align:center; width: 30%;">Item</td>
              <td style=" font-size: 12px; padding:0 .5rem; text-align:center;">Kg</td>
              <td style=" font-size: 12px; padding:0 .5rem; text-align:center;">20</td>
              <td style=" font-size: 12px; padding:0 .5rem; text-align:center;">20</td>
              <td style=" font-size: 12px; padding:0 .5rem;text-align:center;">3.0000 $</td>
              <td style=" font-size: 12px; font-weight: 700; padding:0 .5rem;">2.0000 $</td>
            </tr>
          </tbody>
        </table>
        <div  style="width:100%;height:3px;background:#7A89FF;margin:.5rem 0;margin-top: 20px;"></div>
        <br>
        <div  style="display:flex;justify-content:space-between;">
          <div>
            <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left; margin-bottom: 0;">Payment Method:</p>
            <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">Cash</p>
            <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">Main Safe USD</p>
          </div>
          <table style="margin-top:-1rem;min-width:289px;height: 38px; border-collapse: collapse;">
            <tbody>
              <tr  style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                <th style="font-size:16px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">Grand Total ($)</th>
                <th style="font-size:16px;font-weight:600; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">238.2235 $</th>
              </tr>
            </tbody>
            <tbody>
              <tr style="color:#666C85; font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                <th style="font-size:16px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">Grand Total ($)</th>
                <th style="font-size:16px;font-weight:600; color: #232859;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">238.2235 $</th>
              </tr>
            </tbody>
            <tbody>
              <tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
                <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">Grand Total ($)</th>
                <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">238.2235 $</th>
              </tr>
            </tbody>
          </table>
        </div>
        <br>
        <br>
        <br>
      </div>
    </div>
  </body>
</html>';


//echo $content;