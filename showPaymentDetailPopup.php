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
  </head>
  <body style="margin: 0; padding: 0; font-family: inter;">
    <div style="margin: auto; max-width: 800px; position: relative; border-radius: 5px;">
      <div  style="position:absolute;left:0;top:0;bottom:0;width:9px;border-radius:0px 10px 10px 0px;z-index:1;background-color:#E99624;"></div>
      <div style="padding: 1% 4% ;">
        <!-- Header Section -->
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td>
            <p style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#E99624 !important; margin-bottom: 0;"> PENDING</p>
            <p style="font-size:40px;font-weight:700;line-height:48.41px;text-align:left;color:#666C85;margin-top: 0rem;">INVOICE</p>
          </td>
          <td style="text-align: right;">
            <img width="100" height="100" src="https://queue1.net/qa1/uploads/queue1/clientLogo/17152523391706978392ourzanzibar.png" alt="logo">
          </td>
        </tr>
      </table>

<!-- Supplier Invoice and Payment Info -->
<table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
  <tr>
    <td style="width: 50%;">
      <table style="width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Supplier invoice #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">1233</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Payment #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">001538</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Task #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">126515</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;line-height:19.36px;padding-right:1rem;color:#666C85 !important;color:#1C2047 !important;font-weight: bold;">Date #</td>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">2024-06-23 07:23:50</td>
          </tr>
        </tbody>
      </table>
    </td>
    <td>
      <table style=" text-align: right; width: 100%; border-collapse: collapse;">
        <tbody>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">Our Zazibar</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">P.o Box 4146,Jambiani</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">Zanzibar,TANZANIA</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">inventory@our-zanzibar.com</td>
          </tr>
          <tr>
            <td style="padding:0;height:30px;font-size:16px;font-weight:600;line-height:19.36px;color:#666C85 !important;">+255742998277</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>


        <br>
        <div>
          <p style="font-size:16px;font-weight:600;line-height:19.36px;text-align:left; margin-bottom: .5rem;">Payment To:</p>
          <p style="margin: 0; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">Green Grocery</p>
        </div>
        <br>
        <table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
          <thead style="border-radius:10px !important; ">
            <tr style="color:white;height:48px;background:#A9B0C0 !important;background-color: #f5f5f5; ">
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px;  padding:.5rem .5rem .5rem 0;">#</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px;  padding:.5rem .5rem .5rem 0;width: 30%;">Item</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px;  padding:.5rem .5rem .5rem 0;">Unit</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px;  padding:.5rem .5rem .5rem 0;">Quantity</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px;  background:#7A89FF;text-align:center;padding:.5rem .5rem .5rem 0;">Price ($)</th>
              <th style=" background: #A9B0C0 !important; font-weight: 700; font-size: 12px;  font-weight: 700; background:#7A89FF;text-align:center;padding:.5rem .5rem .5rem 0;">Total ($)</th>
            </tr>
          </thead>
          <tbody >
            <tr style="height:48px;">
              <td style="font-size: 12px; padding: .5rem;">1</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center; width: 30%;">Item</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">Kg</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">20</td>
              <td style="font-size: 12px; padding: .5rem;text-align:center;">3.0000 $</td>
              <td style="font-size: 12px; font-weight: 700; padding:.5rem;">2.0000 $</td>
            </tr>
            <tr style="height:48px;">
              <td style=" font-size: 12px; padding: .5rem;">1</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;width: 30%;">Item</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">Kg</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">20</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">3.0000 $</td>
              <td style=" font-size: 12px; font-weight: 700; padding: .5rem;">2.0000 $</td>
            </tr>
            <tr style="height:48px;">
              <td style=" font-size: 12px; padding: .5rem;">1</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center; width: 30%;">Item</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">Kg</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">20</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">3.0000 $</td>
              <td style=" font-size: 12px; font-weight: 700; padding: .5rem;">2.0000 $</td>
            </tr>
            <tr style="height:48px;">
              <td style=" font-size: 12px; padding: .5rem;">1</td>
              <td style=" font-size: 12px; padding: .5rem; text-align:center; width: 30%;">Item</td>
              <td style=" font-size: 12px; padding: .5rem; text-align:center;">Kg</td>
              <td style=" font-size: 12px; padding: .5rem; text-align:center;">20</td>
              <td style=" font-size: 12px; padding: .5rem;text-align:center;">3.0000 $</td>
              <td style=" font-size: 12px; font-weight: 700; padding: .5rem;">2.0000 $</td>
            </tr>
          </tbody>
        </table>

        <div  style="width:100%;height:3px;background:#7A89FF;margin:.5rem 0;margin-top: 20px;"></div>

        <br>
  <table style=" width:100%; border-collapse: collapse;">
  <tr>
    <td style="vertical-align: top; width:50%; ">
      <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left; margin-bottom: 0;">Payment Method:</p>
      <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">Cash</p>
      <p style="margin: 0px; font-size:16px;font-weight:600;line-height:19.36px;text-align:left;color:#666C85; margin-bottom: 0;">Main Safe USD</p>
    </td>
    <td style="vertical-align: top; width:50%;">
      <table style="margin-top:-1rem;min-width:350px;height: 38px; border-collapse: collapse;">
        <tbody>
          <tr style="background:#7A89FF;color:white;font-size:18px;line-height:21.78px;text-align:left;height:38px;border:none !important;font-weight: bold; max-height: 38px;">
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:10px 0 0 10px !important;">Grand Total ($)</th>
            <th style="font-size:18px;font-weight:600;line-height:21.78px;padding:0 12px !important;border-radius:0 10px 10px 0 !important;">238.2235 $</th>
          </tr>
        </tbody>
      </table>
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