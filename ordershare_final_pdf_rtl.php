<?php
include_once('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location = "login.php"</script>';
}

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);


// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);





$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";

$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);

$sql = "SELECT od.*, tp.itemName, tp.barCode, tp.imgName, IF(u.name!='',u.name,tp.unitP) purchaseUnit FROM tbl_order_details od 
        INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
        LEFT JOIN tbl_units u ON(u.id = tp.unitP) AND u.account_id = tp.account_id
        WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "' order by tp.catId  ";

$proresultSet = mysqli_query($con, $sql);

$sql = "SELECT cif.itemName, cif.unit, tod.* FROM tbl_order_details tod 
        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
        WHERE tod.ordId = '" . $_GET['orderId'] . "' AND tod.account_id = '" . $_SESSION['accountId'] . "'  and tod.customChargeType=1 ORDER BY cif.itemName ";
$otherChrgQry = mysqli_query($con, $sql);

$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(s.name)) suppliers FROM tbl_order_details d 
        INNER JOIN tbl_orders o
        ON(o.id = d.ordId) AND o.account_id = d.account_id
        INNER JOIN tbl_suppliers s
        ON(o.supplierId = s.id) AND o.account_id = s.account_id
        WHERE d.ordId IN( " . $_GET['orderId'] . " ) AND s.account_id = '" . $_SESSION['accountId'] . "'  GROUP BY d.ordId ";
$resultSet = mysqli_query($con, $sqlSet);
$supRow = mysqli_fetch_array($resultSet);
$suppliers = $supRow['suppliers'];

$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "'  AND account_id = '" . $_SESSION['accountId'] . "'  ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);



$sqlSet = " SELECT j.*, u.name, d.designation_name FROM tbl_order_journey j 
        INNER JOIN tbl_user u ON(u.id = j.userBy)
        INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id
        
         WHERE j.orderId = '" . $_GET['orderId'] . "'  AND j.account_id = '" . $_SESSION['accountId'] . "'  ";
$orderJourneyQry = mysqli_query($con, $sqlSet);


if ($ordDet['ordCurId'] > 0) {
    $res = mysqli_query($con, " SELECT * from tbl_currency WHERE id='" . $ordDet['ordCurId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' ");
    $curDet = mysqli_fetch_array($res);
}
$content = '<!doctype html>';
$content .= '<html>';
$content .= '<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Design</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Hebrew:wght@300;400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { margin: 10px 10px; }
        th {
            text-align:left;
        }
    </style>
    </head>';

$content .= '<body style="' . ($getLangType == '1'
    ? 'font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;box-sizing:border-box;'
    : 'font-family: \'Inter\', sans-serif; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;') . '">';
if ($_GET['address'] == 1 || $_GET['logo'] == 1 || $_GET['orderDetails'] == 1 || $_GET['currentDate'] == 1) {
    $content .= '<table style="width: 100%; border-spacing: 0;">
            <tr align="top">
                <td width="33%" style="text-align:left;vertical-align:top;">';
    //logo and date will go here

    if ($_GET['logo'] == 1) {
        if ($clientDetRow["logo"] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientDetRow["logo"])) {
            $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 50px; width: auto;">';
        } else {
            $content .= '<img src="' . $siteUrl . 'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
        }
    }
    if ($_GET['currentDate'] == 1) {
        $content .=  '<table style="width: 100%; border-spacing: 0;">
            <tr>
            <td style="font-size: 14px; line-height: 16px; text-align: left;border:0;">' . date('d/m/Y') . '</td>
            </tr>
            </table>';
    }
    //end logo and date here

    $content .=  '</td><td  width="33%" style="font-size: 18px; line-height: 16px; font-weight: 600; margin: 0; padding: 0; text-align: center !important;border:0;vertical-align:top;">';
    if ($_GET['orderDetails'] == 1) {
        $content .=  '
        <b style="text-align: center; padding:5px; ">' . showOtherLangText('Order Details') . '</b>
             ';
    }

    //address will go here
    $content .=  ' </td><td  width="33%">';

    if ($_GET['address'] == 1) {
        $content .=   ' 
    <table style="width: 100%; border-spacing: 0; margin-top: 5px;text-align:right;">
        <tr>
            <td style="font-size: 13px; line-height: 15px; font-weight: 400;border:0;">
                <b>' . reverseRTLTextForPdf($clientDetRow['accountName']) . '</b><br>
                ' . reverseRTLTextForPdf($clientDetRow['address_one']) . '<br>
                ' . reverseRTLTextForPdf($clientDetRow['address_two']) . '<br>
                ' . reverseRTLTextForPdf($clientDetRow['city']) . ', ' . reverseRTLTextForPdf($clientDetRow['countryName']) . '<br>
                ' . reverseRTLTextForPdf($clientDetRow['email']) . '<br>
                ' . reverseRTLTextForPdf($clientDetRow['phone']) . '
            </td>
        </tr>
    </table>';
    }




    //end address
    $content .=  '</td>
    </tr>
</table>';
} //end header parts

//start summary part
if (
    isset($_GET['checkAllSummary']) || isset($_GET['defaultCurrencyAmount']) || isset($_GET['secondCurrency'])
    || isset($_GET['supplierInvoice']) || isset($_GET['payment'])

) {

    $content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px;text-align:right;">
   
        <tr>
            <td width="50%" style="font-weight:700;padding: 5px;border:0;">';

    if ($_GET['defaultCurrencyAmount']  == 1 || $_GET['secondCurrency']  == 1) {


        $content .= ' <table width="100%" border="0">
                    <tbody>
                        <tr>
                            <td style="width:30%;text-align: right;"></td>
                            <td style="width:30%;text-align: right;">' . showOtherLangText('Total') . '</td>
                            <td style="width:40%;text-align: right;"></td>
                        </tr>
                    </tbody>
                </table> ';
    }

    $content .= ' </td>
            <td width="25%" style="font-weight:700; text-align: center;padding: 5px;border:0;text-align: right;">' . showOtherLangText('Supplier') . '</td>
            <td width="25%">
                ';

    $content .=   '<table style="width: 100%;"><tr>
                        <td width="40%" style="padding: 5px;border:0;text-align: right;">&nbsp;</td>
                        <td width="60%" style="font-weight:700;padding: 5px;border:0;text-align: right;">' . showOtherLangText('Task No.') . '</td> 
                    </tr></table>';


    $content .=   '
            </td> 
        </tr>
        <tr style="background-color: rgba(122, 137, 255, 0.2);text-align: right;">
            <td style="padding:8px 5px;font-weight: 700;border:0;text-align: right;">
             
                <table style="width: 100%;text-align: right;">
                    <tr> 
                        <td width="30%" style="padding:8px 5px; font-weight: 700;text-align: right;">';
    if ($_GET['secondCurrency']  == 1) {
        $content .= showOtherCur($ordDet['ordCurAmt'], $ordDet['ordCurId'],  1);
    }
    $content .= '</td>

                        <td width="30%" style="padding:8px 5px; font-weight: 700;border:0;text-align: right;">';
    if ($_GET['defaultCurrencyAmount']  == 1) {
        $content .=  getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode'], 0, 1);
    }

    $content .= '</td> <td width="40%" style="padding: 5px;border:0;text-align: right;">&nbsp;</td>

                 

                    </tr>
                    
                </table>
            
            
            </td>
            <td style="text-align: right;padding:8px 5px; font-weight: 700;border:0;">' . reverseRTLTextForPdf($suppliers) . '</td>
            <td style="padding-right:10px;text-align: right;font-weight: 700;">

                ' . $ordDet['ordNumber'] . '
            </td>
        </tr>';





    //starts totak breakups charges------------------------------------------------------------------------------------------------------------------

    $content .= '<tr>
            <td width="50%" style="text-align: right;">';

    if ($_GET['defaultCurrencyAmount']  == 1 || $_GET['secondCurrency']  == 1) {
        $content .= '
                <table border="0" cellpadding="5" cellspacing="0" width="100%" style="width: 100%;border-collapse: collapse;text-align: right;border:1px solid #DFE0E8;border-top:0;">';
        $sqlSet = "SELECT SUM(totalAmt) AS sum1, SUM(curAmt) AS totalAmtOther FROM tbl_order_details WHERE ordId='" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  AND (customChargeType='1' OR customChargeType='0')";
        $resultSet = mysqli_query($con, $sqlSet);
        $chargeRow = mysqli_fetch_array($resultSet);
        $chargePrice = $chargeRow['sum1'];
        $chargePriceOther = $chargeRow['totalAmtOther'];


        //to find order level charge
        $ordCount = "SELECT * from tbl_order_details where ordId='" . $_GET['orderId'] . "'  AND account_id = '" . $_SESSION['accountId'] . "' AND customChargeType='2' ";
        $ordCountResult = mysqli_query($con, $ordCount);
        $ordCountRow = mysqli_num_rows($ordCountResult);
        $showGrandTotal = false;

        if ($ordCountRow > 0) {
            $showGrandTotal = true;
            $content .=  '<tr>';


            $content .= '<td style="padding: 5px;width:30%;text-align: right;">';
            if ($_GET['secondCurrency']  == 1) {
                $content .= showOtherCur($chargePriceOther, $ordDet['ordCurId'], 1);
            }
            $content .= '</td>';

            $content .=  '<td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['defaultCurrencyAmount']  == 1) {
                $content .= getPriceWithCur($chargePrice, $getDefCurDet['curCode'], 0, 1);
            }
            $content .= '</td>';

            $content .=  '<td style="padding: 5px;border:0;width:40%;text-align: right;">' . showOtherLangText('Sub Total') . '</td>';

            $content .=  '</tr>';
        }
        $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                          INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                          WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 2 ORDER BY tp.feeName ";

        $ordQry = mysqli_query($con, $sql);


        $fixedCharges = 0;
        $fixedChargesOther = 0;
        while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
        {
            $fixedCharges += $row['price'];
            $fixedChargesOther += $row['curAmt'];
            $content .= '<tr style="border-top: 1px solid #ddd;text-align: right;">
                       
                       
                        <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['secondCurrency']  == 1) {
                $content .= showOtherCur($row['curAmt'], $ordDet['ordCurId'], 1);
            }
            $content .=  '</td>

                         <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['defaultCurrencyAmount']  == 1) {
                $content .= getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1);
            }

            $content .= '</td>
                         <td style="padding: 5px;border:0;width:40%;text-align: right;">' . reverseRTLTextForPdf($row['feeName']) . '</td>
                        </tr>';
        }


        $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                          INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                          WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "'  and od.customChargeType=2 AND tp.feeType = 3 ORDER BY tp.feeName ";
        $ordQry = mysqli_query($con, $sql);

        $perCharges = 0;
        $perChargesOther = 0;
        while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
        {

            $perCharges += $row['price'];
            $perChargesOther = $row['curAmt'];


            $calDiscount = ($chargePrice * $row['price'] / 100);
            $calDiscountOther = ($chargePriceOther * $row['price'] / 100);
            $content .= '<tr style="border-top: 1px solid #ddd;text-align: right;">
                     
                     
                     <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['secondCurrency']  == 1) {
                $content .= showOtherCur($calDiscountOther, $ordDet['ordCurId'], 1);
            }
            $content .=  '</td>

                            <td style="padding: 5px;width:30%;text-align: right;">';
            if ($_GET['defaultCurrencyAmount']  == 1) {
                $content .= getPriceWithCur($calDiscount, $getDefCurDet['curCode'], 0, 1);
            }
            $content .= '</td>

                          <td style="padding: 5px;width:40%;text-align: right;">' . reverseRTLTextForPdf($row['feeName']) . '</td>


                 </tr>';
        }


        $totalCalDiscount = ($chargePrice * $perCharges / 100); //total discount feeType=3

        $totalCalDiscountOther = ($chargePriceOther * $perCharges / 100);
        $sql = "SELECT od.*, tp.feeName FROM tbl_order_details od 
                          INNER JOIN tbl_order_fee tp ON(od.customChargeId = tp.id) AND od.account_id = tp.account_id
                          WHERE od.ordId = '" . $_GET['orderId'] . "'  AND od.account_id = '" . $_SESSION['accountId'] . "' and od.customChargeType=2 AND tp.feeType = 1 ORDER BY tp.feeName ";
        $ordQry = mysqli_query($con, $sql);

        $taxCharges = 0;
        $totalTaxChargesOther = 0;
        while ($row = mysqli_fetch_array($ordQry)) //show here order level charges
        {
            $taxCharges += $row['price'];
            $totalTaxChargesOther += $row['curAmt'];

            $calTax = (($chargePrice + $fixedCharges + $totalCalDiscount) * $row['price'] / 100);
            $calTaxOther = (($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther) * $row['price'] / 100);
            $calDiscount = ($chargePrice * $row['price'] / 100);
            $calDiscountOther = ($chargePriceOther * $row['price'] / 100);
            $content .= '<tr style="border-top: 1px solid #ddd;text-align: right;">
                     
                     
                     <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['secondCurrency']  == 1) {
                $content .= showOtherCur($calTaxOther, $ordDet['ordCurId'], 1);
            }
            $content .=  '</td>

             <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['defaultCurrencyAmount']  == 1) {
                $content .= getPriceWithCur($calTax, $getDefCurDet['curCode'], 0, 1);
            }
            $content .= '</td>

             <td style="padding: 5px;border:0;width:40%;text-align: right;">' . reverseRTLTextForPdf($row['feeName']) . '</td>
                 </tr>';
        }

        $totalTax = (($chargePrice + $fixedCharges + $totalCalDiscount) * $taxCharges / 100); //total tax feeType=1
        $totalTaxOther = (($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther) * $taxCharges / 100);

        $netTotalAmt = ($chargePrice + $fixedCharges + $totalCalDiscount + $totalTax);
        $netTotalAmtOther = ($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther + $totalTaxOther);

        if ($showGrandTotal) {
            $content .= '<tr style="border-top: 1px solid #ddd;text-align: right;font-weight:bold;background-color: #E5E7FF;color:#000;">
                     
                     
                     <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['secondCurrency']  == 1) {
                $content .= showOtherCur($netTotalAmtOther, $ordDet['ordCurId'], 1);
            }
            $content .=  '</td>
                         <td style="padding: 5px;border:0;width:30%;text-align: right;">';
            if ($_GET['defaultCurrencyAmount']  == 1) {
                $content .= getPriceWithCur($netTotalAmt, $getDefCurDet['curCode'], 0, 1);
            }
            $content .= '</td>

                         <td style="padding: 5px;border:0;width:40%;text-align: right;">' . showOtherLangText('Grand Total') . '</td>
                 </tr>';
        }

        $content .= '</table>';
    }

    $content .= '
          <td width="50%" align="right" colspan="2" style="vertical-align:top;text-align: right;">';
    if ($_GET['supplierInvoice']  == 1) {
        $content .=   '<table style="width: 100%;text-align: right;">
                   <tr>
                       
                       <td style="padding: 5px;border:0;text-align: right;width:50%;">' . $ordDet['invNo'] . '</td>
                       <td style="padding: 5px;border:0;text-align: right;width:50%;"> ' . showOtherLangText('Supplier Invoice') . ' #</td>
                   </tr>
               </table>';
    }

    if ($_GET['payment']  == 1 && $ordDet['paymentId'] > 0) {
        $content .=   '<table style="width: 100%;">
                       <tr>
                           
                           <td style="padding: 5px;border:0;text-align: right;width:50%;">' . $ordDet['paymentId'] . '</td>
                           <td style="padding: 5px;border:0;text-align: right;width:50%;"> ' . showOtherLangText('Payment') . ' #</td>
                       </tr>
                   </table>';
    }


    $content .= '</td></tr></table>';




    //end totak breakups charges---------------------------------------------------------------------------------------------------------------------


} //end 










$content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px; text-align: right;">
    
    <tr style="background-color: rgba(122, 137, 255, 0.2);">';

if ($_GET['note'] == 1) {
    $content .= '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Note') . '</th>';
}

if ($_GET['secondCurrencyTotal'] == 1 && $ordDet['ordCurId'] > 0) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . '(' . $curDet['curCode'] . ')' . showOtherLangText('Total') . '</th>';
}

if ($_GET['total'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . '(' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Total') . '</th>';
}

if ($_GET['receivedQty'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Rec qty.') . '</th>';
}

if ($_GET['qty'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Qty') . '</th>';
}
if ($_GET['unit'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Unit') . '</th>';
}

if ($_GET['secondCurrencyPrice'] == 1 && $ordDet['ordCurId'] > 0) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . '(' . $curDet['curCode'] . ')' . showOtherLangText('Price') . '</th>';
}
if ($_GET['price'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . '(' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Price') . '</th>';
}
if ($_GET['barcode'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Barcode') . '</th>';
}
if ($_GET['itemName'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Item') . '</th>';
}
if ($_GET['photo'] == 1) {
    $content .=  '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Photo') . '</th>';
}




$content .=    ' <th style="font-weight:700;padding:8px 5px;text-align: right;">#</th></tr>';


$i = 0;
while ($row = mysqli_fetch_array($otherChrgQry)) {
    $i++;
    $bgColor = ($i % 2 != 0) ? 'white' : '#F9F9FB';
    $content .=   '<tr style="background-color: ' . $bgColor . ';">';


    if ($_GET['note'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . $row['note'] . '</td>';
    }

    if ($_GET['secondCurrencyTotal'] == 1 && $ordDet['ordCurId'] > 0) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . getPriceWithCur($row['curAmt'], $curDet['curCode'], $curDet['decPlace'], 1) . '</td>';
    }

    if ($_GET['total'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1) . '</td>';
    }

    if ($_GET['receivedQty'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;font-weight:700;">1</td>';
    }



    if ($_GET['qty'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">1</td>';
    }

    if ($_GET['unit'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . reverseRTLTextForPdf($row['unit']) . '</td>';
    }

    if ($_GET['secondCurrencyPrice'] == 1 && $ordDet['ordCurId'] > 0) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">
                ' . getPriceWithCur(($row['curAmt']), $curDet['curCode'], $curDet['decPlace'], 1) . '</td>';
    }

    if ($_GET['price'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1) . '</td>';
    }

    if ($_GET['barcode'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;"></td>';
    }

    if ($_GET['itemName'] == 1) {
        $content .=  '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;font-weight:700;">' . reverseRTLTextForPdf($row['itemName']) . '</td>';
    }

    if ($_GET['photo'] == 1) {
        $content .= '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;"></td>';
    }

    $content .=   '<td style="padding: 8px 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . $i . '</td>';

    $content .=  '</tr>';
}

while ($row = mysqli_fetch_array($proresultSet)) {
    $i++;
    $bgColor2 = ($i % 2 != 0) ? 'white' : '#F9F9FB';
    $content .=   '<tr style="background-color: ' . $bgColor2 . ';">';


    if ($_GET['note'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . reverseRTLTextForPdf($row['note']) . '</td>';
    }

    if ($_GET['secondCurrencyTotal'] == 1 && $ordDet['ordCurId'] > 0) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . getPriceWithCur($row['curAmt'], $curDet['curCode'], $curDet['decPlace'], 1) . '</td>';
    }

    if ($_GET['total'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">
                ' . getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode'], 0, 1) . '</td>';
    }

    if ($_GET['receivedQty'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;font-weight:700;">
                ' . $row['qtyReceived'] . '</td>';
    }

    if ($_GET['qty'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . $row['qty'] . '</td>';
    }

    if ($_GET['unit'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . reverseRTLTextForPdf($row['purchaseUnit']) . '</td>';
    }

    if ($_GET['secondCurrencyPrice'] == 1 && $ordDet['ordCurId'] > 0) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">
                ' . getPriceWithCur($row['curPrice'] * $row['factor'], $curDet['curCode'], $curDet['decPlace'], 1) . '</td>';
    }

    if ($_GET['price'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">
                ' . getPriceWithCur($row['price'] * $row['factor'], $getDefCurDet['curCode'], 0, 1) . '</td>';
    }

    if ($_GET['barcode'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . $row['barCode'] . '</td>';
    }

    if ($_GET['itemName'] == 1) {
        $content .=  '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;font-weight:700;">' . reverseRTLTextForPdf($row['itemName']) . '</td>';
    }

    if ($_GET['photo'] == 1) {

        $img = '';
        if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
            $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" width="60" height="60">';
        }
        $content .= '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . $img . '</td>';
    }

    $content .=   '<td style="padding: 5px;text-align:right;border-bottom:1px solid #DFE0E8;">' . $i . '</td>';

    $content .=  '</tr>';
}



$content .=  '</table>';

if ($_GET['taskRecord'] == 1) {
    $content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px; text-align: right;">
    
        <tr style="background-color: rgba(122, 137, 255, 0.2);">';
    $content .= '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Note') . '</th>';

    if ($_GET['secondCurrency'] == 1 &&  $curDet['curCode'] != '') {
        $content .= '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . '(' . $curDet['curCode'] . ')' . showOtherLangText('Price') . '</th>';
    }
    $content .= '<th style="font-weight:700;padding:8px 5px;text-align: right;">' . '(' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Price') . '</th>';


    $content .= ' <th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('User') . '</th>
        <th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Date') . '</th>
        <th style="font-weight:700;padding:8px 5px;text-align: right;">' . showOtherLangText('Status') . '</th></tr>
            
           ';


    $j=0;
    while ($orderJourney = mysqli_fetch_array($orderJourneyQry)) {
        $j++;
        $bgColor3 = ($j % 2 != 0) ? 'white' : '#F9F9FB';
        $content .= '<tr style="background-color: ' . $bgColor3 . ';">';
        $content .=  '<td style="padding: 5px;text-align: right;border-bottom:1px solid #DFE0E8;">' . ucfirst($orderJourney['notes']) . '</td>';

        if ($_GET['secondCurrency'] == 1 && $curDet['curCode'] != '') {
            $content .=  '<td style="padding: 5px;text-align: right;border-bottom:1px solid #DFE0E8;">' .
                getPriceWithCur($orderJourney['otherCur'], $curDet['curCode'], $curDet['decPlace'], 1) . '</td>';
        }

        $content .= '<td style="padding: 5px;text-align: right;border-bottom:1px solid #DFE0E8;">' . getPriceWithCur($orderJourney['amount'], $getDefCurDet['curCode'], 0, 1) . '</td>
        
        <td style="padding: 5px;text-align: right;border-bottom:1px solid #DFE0E8;">' . '(' . ucfirst(reverseRTLTextForPdf($orderJourney['designation_name'])) . ')' . ucfirst(reverseRTLTextForPdf($orderJourney['name'])) . '</td>
        
        <td style="padding: 5px;text-align: right;border-bottom:1px solid #DFE0E8;">' . date('h:iA d/m/Y', strtotime($orderJourney['ordDateTime'])) . '</td>

        <td style="padding: 5px;text-align: right;border-bottom:1px solid #DFE0E8;">' . showOtherLangText(ucfirst($orderJourney['action'])) . '</td>';

        $content .=   '</tr>';
    }

    $content .= '</table>';
}


$content .= '</body>
             </html>';
 
// echo $content;
// unset($content);
// $content = '<html>
// <head>
//     <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
// </head>
// <body>
//     <div><p style="font-family: firefly, DejaVu Sans, sans-serif;">'.showOtherLangText('Note').'</p></div>
// </body>
// </html>';