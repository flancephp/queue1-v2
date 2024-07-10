<?php
include_once('inc/dbConfig.php'); //connection details

if ( !isset($_SESSION['adminidusername']))
{
  echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);




   
        $sql = "SELECT * FROM tbl_orders  WHERE id = '".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  ";
            
        $qry = mysqli_query($con, $sql);
        $ordDet = mysqli_fetch_array($qry);
                          
        $sql = "SELECT od.*, tp.itemName, tp.barCode, tp.imgName, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
        INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
        LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
        WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  ";

        $proresultSet = mysqli_query($con, $sql);

        $sql = "SELECT cif.itemName, cif.unit, tod.* FROM tbl_order_details tod 
        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
        WHERE tod.ordId = '".$_GET['orderId']."' AND tod.account_id = '".$_SESSION['accountId']."'  and tod.customChargeType=1 ORDER BY cif.itemName ";
        $otherChrgQry=mysqli_query($con, $sql);
        
        $sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_order_details d 
        INNER JOIN tbl_orders o
        ON(o.id = d.ordId) AND o.account_id = d.account_id
        INNER JOIN tbl_suppliers s
        ON(o.supplierId = s.id) AND o.account_id = s.account_id
        WHERE d.ordId IN( ".$_GET['orderId']." ) AND s.account_id = '".$_SESSION['accountId']."'  GROUP BY d.ordId ";
        $resultSet = mysqli_query($con, $sqlSet);
        $supRow = mysqli_fetch_array($resultSet);
        $suppliers = $supRow['suppliers'];
        
        $sqlSet = " SELECT * FROM tbl_user WHERE id = '".$ordDet['orderBy']."'  AND account_id = '".$_SESSION['accountId']."'  ";
        $resultSet = mysqli_query($con, $sqlSet);
        $orderBy = mysqli_fetch_array($resultSet);
        
        

        $sqlSet = " SELECT j.*, u.name, d.designation_name FROM tbl_order_journey j 
        INNER JOIN tbl_user u ON(u.id = j.userBy)
        INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id
        
         WHERE j.orderId = '".$_GET['orderId']."'  AND j.account_id = '".$_SESSION['accountId']."'  ";
        $orderJourneyQry = mysqli_query($con, $sqlSet);


        if($ordDet['ordCurId'] > 0)
        {
            $res = mysqli_query($con, " SELECT * from tbl_currency WHERE id='".$ordDet['ordCurId']."'  AND account_id = '".$_SESSION['accountId']."' ");
            $curDet = mysqli_fetch_array($res);
        } 
 $content = '<!doctype html>';
$content .= '<html lang="en">';
 $content .= '<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Design</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { margin: 10px 10px; }
        th {
            text-align:left;
        }
    </style>
    </head>';

$content .= '<body style="font-family: \'Inter\', Sans- serif;color: #232859; font-weight: 400;font-size: 12px; line-height: 14px;">';
if($_GET['address'] == 1 || $_GET['logo'] == 1 || $_GET['orderDetails'] == 1 || $_GET['currentDate'] == 1)
    {
$content .= '<table style="width: 100%; border-spacing: 0;">
    <tr valign="top">
        <td width="33%">';

  if($_GET['address'] == 1)
                   {
 $content .=   '<table style="width: 100%; border-spacing: 0;">
                <tr>
                    <td style="font-size: 14px; line-height: 16px; font-weight: 600;border:0;">'.$clientDetRow['accountName'].'</td>
                </tr>
                </table>
                <table style="width: 100%; border-spacing: 0; margin-top: 5px;">
                <tr>
                    <td style="font-size: 13px; line-height: 15px; font-weight: 400;border:0;">
                        '.$clientDetRow['address_one'].'<br>
                        '.$clientDetRow['address_two'].'<br>
                        '.$clientDetRow['city'].', '.$clientDetRow['countryName'].'<br>
                        '.$clientDetRow['email'].'<br>
                        '.$clientDetRow['phone'].'
                    </td>
                </tr>
            </table>';
              }
           
 $content .=  '</td>';
  if($_GET['orderDetails'] == 1)
               {
 $content .=  '<td  width="33%" style="font-size: 18px; line-height: 16px; font-weight: 600; margin: 0; padding: 0; text-align: center;border:0;">
            '.showOtherLangText('Order Details').'
              </td>';
                 }
 $content .=  '<td  width="33%" align="right">';
 if($_GET['logo'] == 1)
                    {
 
 if($clientDetRow["logo"] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientDetRow["logo"] ))
                            {  
                                $content .= '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" style="object-fit: scale-down; height: 50px; width: auto;">';
                            }
                            else
                            {
                                $content .= '<img src="'.$siteUrl.'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
                            }
                   }
      if($_GET['currentDate'] == 1)
                    {
  $content .=  '<table style="width: 100%; border-spacing: 0;">
                <tr>
                    <td style="font-size: 14px; line-height: 16px; text-align: right;border:0;">'.date('d/m/Y').'</td>
                </tr>
                </table>';
                    }
   $content .=  '</td>
    </tr>
</table>';
   }



$content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px;">
   
        <tr>
            <td style="font-weight:700;padding: 5px;border:0;">'.showOtherLangText('Task No.').'</td>
            <td style="font-weight:700; text-align: center;padding: 5px;border:0;">'.showOtherLangText('Supplier').'</td>
            <td >
                <table style="width: 100%;">';
         if(  $_GET['defaultCurrencyAmount']  == 1 || $_GET['secondCurrencyAmount']  == 1 )    { 
        $content .=   '<tr>
                        <td width="40%" style="padding: 5px;border:0;">&nbsp;</td>
                        <td width="40%" style="font-weight:700;padding: 5px;border:0;">'.showOtherLangText('Total').'</td>
                        <td width="20%" style="padding: 5px;border:0;">&nbsp;</td>
                    </tr>';
         }
                
         $content .=   '</table>
            </td> 
        </tr>
        <tr style="background-color: rgba(122, 137, 255, 0.2);">
            <td style="padding:8px 5px;font-weight: 700;border:0;">'.$ordDet['ordNumber'].'</td>
            <td style="text-align: center;padding:8px 5px; font-weight: 700;border:0;">'.$suppliers.'</td>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td width="40%" style="padding: 5px;border:0;">&nbsp;</td>
                        <td width="40%" style="padding:8px 5px; font-weight: 700;border:0;">';
                      if(  $_GET['defaultCurrencyAmount']  == 1 )  
                                { 
                        $content .=  getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode']);
                                }
                 $content .= '</td>
                        <td width="20%" style="style="padding:8px 5px; font-weight: 700;"">';
                  if( $_GET['secondCurrency']  == 1 )  
                                {
                 $content .= showOtherCur($ordDet['ordCurAmt'], $ordDet['ordCurId']);
                                }
                 $content .= '</td>
                    </tr>
                </table></td>
        </tr>';
         $content .= '<tr><td width="33%" valign="top">';
         if(  $_GET['supplierInvoice']  == 1)  
                { 
         $content .=   '<table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px;border:0;"># Supplier Invoice</td>
                        <td style="padding: 5px;border:0;">'.$ordDet['invNo'].'</td>
                    </tr>
                </table>';
                }
        $content .=  '</td>';
             if(  $_GET['defaultCurrencyAmount']  == 1 || $_GET['secondCurrency']  == 1 )  
            {
            $content .= '<td width="33%"></td>
            <td width="34%">
                <table style="width: 100%;border-collapse: collapse;">';
             $sqlSet="SELECT SUM(totalAmt) AS sum1, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='".$_GET['orderId']."' AND account_id = '".$_SESSION['accountId']."'  AND (customChargeType='1' OR customChargeType='0')";
                             $resultSet = mysqli_query($con, $sqlSet);
                             $chargeRow = mysqli_fetch_array($resultSet);   
                             $chargePrice=$chargeRow['sum1'];
                             $chargePriceOther=$chargeRow['totalAmtOther'];
                             
     
                             //to find order level charge
                             $ordCount="SELECT * from tbl_order_details where ordId='".$_GET['orderId']."'  AND account_id = '".$_SESSION['accountId']."' AND customChargeType='2' ";
                             $ordCountResult = mysqli_query($con, $ordCount);
                             $ordCountRow = mysqli_num_rows($ordCountResult);
                             $showGrandTotal = false;

                             if ($ordCountRow > 0)
                             { 
                              $showGrandTotal = true;
            $content .=  '<tr>';
            $content .=  '<td style="padding: 5px;border:0;">'.showOtherLangText('Sub Total').'</td>';
            $content .=  '<td style="padding: 5px;border:0;">';
            if(  $_GET['defaultCurrencyAmount']  == 1  )  
                            { 
            $content .= getPriceWithCur($chargePrice, $getDefCurDet['curCode']);
                            }
            $content .= '</td>';
              $content .= '<td style="padding: 5px;">';
            if(  $_GET['secondCurrency']  == 1  )  
                             {
            $content .= showOtherCur($chargePriceOther, $ordDet['ordCurId']);
                             }
            $content .= '</td>
                       </tr>';
                             }
            $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                             INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                             WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";
     
                             $ordQry = mysqli_query($con, $sql);
                     
                 
                     $fixedCharges = 0;
                     $fixedChargesOther = 0;
                     while($row = mysqli_fetch_array($ordQry))//show here order level charges
                     {
                             $fixedCharges += $row['price'];
                             $fixedChargesOther += $row['curAmt'];
             $content .= '<tr style="border-top: 1px solid #ddd;">
                        <td style="padding: 5px;border:0;">'.$row['feeName'].'</td>
                        <td style="padding: 5px;border:0;">';
             if(  $_GET['defaultCurrencyAmount']  == 1  )  
                            { 
                                $content .=getPriceWithCur($row['price'], $getDefCurDet['curCode']);
                            }
              $content .= '</td>
                        <td style="padding: 5px;border:0;">';
                    if(  $_GET['secondCurrency']  == 1  )  
                             { 
                                $content .= showOtherCur($row['curAmt'], $ordDet['ordCurId']);
                             }
                $content .=  '</td>
                    </tr>';
                     }


                $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                             INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                             WHERE od.ordId = '".$_GET['orderId']."' AND od.account_id = '".$_SESSION['accountId']."'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
                             $ordQry = mysqli_query($con, $sql);
                     
                     $perCharges = 0;
                     $perChargesOther = 0;
                     while($row = mysqli_fetch_array($ordQry))//show here order level charges
                     {
                         
                             $perCharges += $row['price'];
                         $perChargesOther = $row['curAmt'];
     
     
                         $calDiscount = ($chargePrice*$row['price']/100);
                         $calDiscountOther = ($chargePriceOther*$row['price']/100);
                         $content .= '<tr style="border-top: 1px solid #ddd;">
                        <td style="padding: 5px;">'.$row['feeName'].'</td>
                        <td style="padding: 5px;">';
             if(  $_GET['defaultCurrencyAmount']  == 1  )  
                            { 
                               $content .=getPriceWithCur($calDiscount, $getDefCurDet['curCode']);
                            }
              $content .= '</td>
                        <td style="padding: 5px;border:0;">';
                    if(  $_GET['secondCurrency']  == 1  )  
                             { 
                                 $content .= showOtherCur($calDiscountOther, $ordDet['ordCurId']);
                             }
                $content .=  '</td>
                    </tr>';
                     }

                 
                 $totalCalDiscount =($chargePrice*$perCharges/100);//total discount feeType=3
                        
                     $totalCalDiscountOther = ($chargePriceOther*$perCharges/100); 
                  $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                             INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                             WHERE od.ordId = '".$_GET['orderId']."'  AND od.account_id = '".$_SESSION['accountId']."' and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
                     $ordQry = mysqli_query($con, $sql);
                     
                     $taxCharges = 0;
                     $totalTaxChargesOther = 0;
                     while($row = mysqli_fetch_array($ordQry))//show here order level charges
                     {
                         $taxCharges += $row['price'];
                         $totalTaxChargesOther += $row['curAmt'];
     
                         $calTax = (($chargePrice+ $fixedCharges+$totalCalDiscount)*$row['price']/100);
                         $calTaxOther = (($chargePriceOther+ $fixedChargesOther+$totalCalDiscountOther)*$row['price']/100);
                          $calDiscount = ($chargePrice*$row['price']/100);
                         $calDiscountOther = ($chargePriceOther*$row['price']/100);
                         $content .= '<tr style="border-top: 1px solid #ddd;">
                        <td style="padding: 5px;border:0;">'.$row['feeName'].'</td>
                        <td style="padding: 5px;border:0;">';
             if(  $_GET['defaultCurrencyAmount']  == 1  )  
                            { 
                                $content .= getPriceWithCur($calTax, $getDefCurDet['curCode']);
                            }
              $content .= '</td>
                        <td style="padding: 5px;border:0;">';
                    if(  $_GET['secondCurrency']  == 1  )  
                             { 
                                $content .= showOtherCur($calTaxOther, $ordDet['ordCurId']);
                             }
                $content .=  '</td>
                    </tr>';
                     }
                 
                 $totalTax =(($chargePrice+$fixedCharges+$totalCalDiscount)*$taxCharges/100);//total tax feeType=1
                          $totalTaxOther =(($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther)*$taxCharges/100);
     
                         $netTotalAmt= ($chargePrice+$fixedCharges+$totalCalDiscount+$totalTax);
                         $netTotalAmtOther= ($chargePriceOther+$fixedChargesOther+$totalCalDiscountOther+$totalTaxOther);
     
                         if($showGrandTotal)
                         {
               $content .= '<tr style="border-top: 1px solid #ddd;">
                        <td style="padding: 5px;border:0;">'.showOtherLangText('Grand Total').'</td>
                        <td style="padding: 5px;border:0;">';
               if(  $_GET['defaultCurrencyAmount']  == 1  )  
                            { 
                                $content .= getPriceWithCur($netTotalAmt, $getDefCurDet['curCode']);
                            }
                $content .= '</td>
                        <td style="padding: 5px;border:0;">';
                if(  $_GET['secondCurrency']  == 1  )  
                            { 
                $content .= showOtherCur($netTotalAmtOther, $ordDet['ordCurId']);
                            }
                $content .=  '</td>
                    </tr>';
                      }
                  }

           $content .= '</table></td></tr></table>';

$content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px; text-align: left;">
    
    <tr style="background-color: rgba(122, 137, 255, 0.2);">
        <th style="font-weight:700;padding:8px 5px;text-align: left;">#</th>';
 if( $_GET['photo'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Photo</th>';
            } 

    if( $_GET['itemName'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Item</th>';
            }
if( $_GET['barcode'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Barcode</th>';
            }
 if( $_GET['price'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Price('.$getDefCurDet['curCode'].')</th>';
            }
            if( $_GET['secondCurrency'] == 1 && $ordDet['ordCurId'] > 0)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Price('.$curDet['curCode'].')</th>';
            }
        if( $_GET['unit'] == 1)
            {                                 
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Unit</th>';
            } 
            if( $_GET['qty'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Qty.</th>';
             } 
             if( $_GET['receivedQty'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Rec Qty.</th>';
            } 
            if( $_GET['total'] == 1)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Total('.$getDefCurDet['curCode'].')</th>';
            } 
             if( $_GET['secondCurrency'] == 1 && $ordDet['ordCurId'] > 0)
            {
 $content .=  '<th style="font-weight:700;padding:8px 5px;">Total('.$curDet['curCode'].')</th>';               
            } 
         if( $_GET['note'] == 1)
            {
 $content .= '<th style="font-weight:700;padding:8px 5px;">Note</th>';
            } 
 $content .=    '</tr>';
   $i = 0;
        while($row = mysqli_fetch_array($otherChrgQry) )
        {
            $i++;
  $content .=   '<tr>';
  $content .=   '<td style="padding: 5px;">'.$i.'</td>';
  if( $_GET['photo'] == 1)
  {
  $content .= '<td style="padding: 5px;"></td>';
  }
   if( $_GET['itemName'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['itemName'].'</td>';
  }
   if( $_GET['barcode'] == 1)
            {
  $content .=  '<td style="padding: 5px;"></td>';
      } 
   if( $_GET['price'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.getPriceWithCur($row['price'], $getDefCurDet['curCode']).'</td>';
   }

  if( $_GET['secondCurrencyPrice'] == 1 && $ordDet['ordCurId'] > 0)
            {
  $content .=  '<td style="padding: 5px;">
                '.getPriceWithCur(($row['curAmt']), $curDet['curCode']).'</td>';
   } 
  
    if( $_GET['unit'] == 1)
            {
  $content .=  '<td style="padding: 5px;">box</td>';
            }
             if( $_GET['qty'] == 1)
            {
  $content .=  '<td style="padding: 5px;">1</td>';
            } 
         if( $_GET['receivedQty'] == 1)
         {
  $content .=  '<td style="padding: 5px;">1</td>';
         }
         if( $_GET['total'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.getPriceWithCur($row['price'], $getDefCurDet['curCode']).'</td>';
            }
             if($_GET['secondCurrencyTotal'] == 1 && $ordDet['ordCurId'] > 0)
            {
  $content .=  '<td style="padding: 5px;">'.getPriceWithCur($row['curAmt'], $curDet['curCode']).'</td>';
            }
        if( $_GET['Note'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['note'].'</td>';
            }
  $content .=  '</tr>'; 
        }

          while($row = mysqli_fetch_array($proresultSet) )
        {
            $i++;
  $content .=   '<tr>';
  $content .=   '<td style="padding: 5px;">'.$i.'</td>';
   if( $_GET['photo'] == 1)
            {

        $img = '';
                if( $row['imgName'] != '' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
                {  
                    $img = '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
                }
       $content .= '<td style="padding: 5px;">'.$img.'</td>';
           } 

           if( $_GET['itemName'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['itemName'].'</td>';
            }
             if( $_GET['barcode'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['barCode'].'</td>';
            } 
             if( $_GET['price'] == 1)
            {
  $content .=  '<td style="padding: 5px;">
                '.getPriceWithCur($row['price']*$row['factor'], $getDefCurDet['curCode']).'</td>';
            } 
             if(  $_GET['secondCurrency'] == 1 && $ordDet['ordCurId'] > 0)
            {
  $content .=  '<td style="padding: 5px;">
                '.getPriceWithCur($row['price']*$row['factor'], $getDefCurDet['curCode']).'</td>';
            } 
   if( $_GET['unit'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['purchaseUnit'].'</td>';
            } 
             if( $_GET['qty'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['qty'].'</td>';
            } 
             if( $_GET['receivedQty'] == 1)
            {
  $content .=  '<td style="padding: 5px;">
                '.$row['qtyReceived'].'</td>';
            } 
             if( $_GET['total'] == 1)
            {
  $content .=  '<td style="padding: 5px;">
                '.getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode']).'</td>';
            } 
            if( $_GET['secondCurrency'] == 1 && $ordDet['ordCurId'] > 0)
            {
  $content .=  '<td style="padding: 5px;">'.getPriceWithCur($row['curAmt'], $curDet['curCode']).'</td>';
            } 
            if( $_GET['note'] == 1)
            {
  $content .=  '<td style="padding: 5px;">'.$row['note'].'</td>';
            } 
  $content .=  '</tr>'; 
        }

    

  $content .=  '</table>';

if( $_GET['taskRecord'] == 1)
{
$content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px; text-align: left;">
    
        <tr style="background-color: rgba(122, 137, 255, 0.2);">
            <th style="font-weight:700;padding:8px 5px;">Status</th>
            <th style="font-weight:700;padding:8px 5px;">Date</th>
            <th style="font-weight:700;padding:8px 5px;">User</th>';
 $content .= '<th style="font-weight:700;padding:8px 5px;">Price('.$getDefCurDet['curCode'].')</th>';
 if($_GET['secondCurrency'] == 1 &&  $curDet['curCode'] != '')
            {
 $content .= '<th style="font-weight:700;padding:8px 5px;">Price('.$curDet['curCode'].')</th>';       
            }              
 $content .= '<th style="font-weight:700;padding:8px 5px;">Note</th></tr>';
   while($orderJourney = mysqli_fetch_array($orderJourneyQry) )
        {  
 $content .= '<tr>
            <td style="padding: 5px;">'.ucfirst($orderJourney['action']).'</td>                          
            <td style="padding: 5px;">'.date('d/m/Y
                h:iA', strtotime($orderJourney['ordDateTime']) ).'</td>
            <td style="padding: 5px;">
                '.ucfirst($orderJourney['name']).'('.ucfirst($orderJourney['designation_name']).')</td>
            <td style="padding: 5px;">'.
                getPriceWithCur($orderJourney['amount'], $getDefCurDet['curCode']).'</td>';
    if($_GET['secondCurrency'] == 1 && $curDet['curCode'] != '')
                {
   $content .=  '<td style="padding: 5px;">'.
                    getPriceWithCur($orderJourney['otherCur'], $curDet['curCode']).'</td>'; 
   }                          
 $content .=  '<td style="padding: 5px;">'.ucfirst($orderJourney['notes']).'</td>';
 $content .=   '</tr>';
        }  
    
 $content .= '</table>';
}




$content .= '</body>
             </html>';
?>