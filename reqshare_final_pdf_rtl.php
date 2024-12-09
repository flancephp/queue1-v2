<?php
include_once('inc/dbConfig.php'); //connection details

if (!isset($_SESSION['adminidusername'])) {
    echo '<script>window.location = "login.php"</script>';
}

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '" . $_SESSION['accountId'] . "' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// Get column permission of requisition
$getColumnPermission = get_column_permission($_SESSION['designation_id'], $_SESSION['accountId'], 2);


$sql = "SELECT * FROM tbl_orders  WHERE id = '" . $_GET['orderId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'  ";
$qry = mysqli_query($con, $sql);
$ordDet = mysqli_fetch_array($qry);


$sqlCustomItems = "SELECT cif.itemName, cif.unit,cif.amt, tod.* FROM tbl_order_details tod 
        INNER JOIN tbl_custom_items_fee cif ON(tod.customChargeId = cif.id) AND tod.account_id = cif.account_id
        WHERE tod.ordId = '" . $_GET['orderId'] . "'  AND tod.account_id = '" . $_SESSION['accountId'] . "' and tod.customChargeType=1 ORDER BY cif.itemName ";
$otherChrgQry = mysqli_query($con, $sqlCustomItems);

$sql = "SELECT od.*, tp.itemName, tp.barCode, tp.imgName, IF(u.name!='', u.name, tp.unitC) countingUnit FROM tbl_order_details od 
		INNER JOIN tbl_products tp ON(od.pId = tp.id) AND od.account_id = tp.account_id
		LEFT JOIN tbl_units u ON(u.id = tp.unitC) AND u.account_id = tp.account_id
		WHERE od.ordId = '" . $_GET['orderId'] . "' AND od.account_id = '" . $_SESSION['accountId'] . "' and od.qty > 0 ";
$proresultSet = mysqli_query($con, $sql);


$sqlSet = " SELECT GROUP_CONCAT(DISTINCT(d.name)) departments FROM tbl_orders o 
		INNER JOIN tbl_department d
			ON(d.id = o.deptId) AND d.account_id = o.account_id
				WHERE o.id=" . $ordDet['id'] . "  AND d.account_id = '" . $_SESSION['accountId'] . "' ";
$resultSet = mysqli_query($con, $sqlSet);
$departRow = mysqli_fetch_array($resultSet);

$sqlSet = " SELECT * FROM tbl_deptusers WHERE id = '" . $ordDet['recMemberId'] . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
$resultSet = mysqli_query($con, $sqlSet);
$mebRes = mysqli_fetch_array($resultSet);

$sqlSet = " SELECT * FROM tbl_user WHERE id = '" . $ordDet['orderBy'] . "' AND account_id = '" . $_SESSION['accountId'] . "'   ";
$resultSet = mysqli_query($con, $sqlSet);
$orderBy = mysqli_fetch_array($resultSet);

$sqlSet = " SELECT j.*, u.name, d.designation_name FROM tbl_order_journey j 
        INNER JOIN tbl_user u ON(u.id = j.userBy)
        INNER JOIN tbl_designation d ON(u.designation_id = d.id) AND u.account_id = d.account_id
        
         WHERE j.orderId = '" . $_REQUEST['orderId'] . "'  AND j.account_id = '" . $_SESSION['accountId'] . "'  ";
$orderJourneyQry = mysqli_query($con, $sqlSet);




$content = '<!doctype html>';
$content .= '<html>';

$content .= '<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Design</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { margin: 10px 10px; }
        th, td {
            text-align:right;
        }
           
    </style>
</head>';

$content .= '<body style="' . ($getLangType == '1'
    ? 'font-family: firefly, DejaVu Sans, sans-serif, Inter; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;'
    : 'font-family: \'Inter\', sans-serif; color: #232859; font-weight: 400; font-size: 12px; line-height: 14px;') . '">';

// $content .= '<div style="background-color: #fff;max-width: 1140px;margin: 0 auto;padding: 20px; box-sizing: border-box;">';


if ($_GET['address'] == 1 || $_GET['logo'] == 1 || $_GET['reqDetailsTitle'] == 1 || $_GET['currentDate'] == 1 || $_GET['orderDetails'] == 1) {

    $content .= '<table style="width: 100%; border-spacing: 0;">
    <tr valign="top">';

    $content .= '<td  width="33%" align="left" style="text-align:left;">';
    if ($_GET['logo'] == 1) {
        if ($clientDetRow["logo"] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/clientLogo/" . $clientDetRow["logo"])) {
            $content .= '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $clientDetRow['logo'] . '" style="object-fit: scale-down; height: 50px; width: auto;">';
        } else {
            $content .= '<img src="' . $siteUrl . 'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
        }
    }
    if ($_GET['currentDate'] == 1) {
        $content .= '<table style="width: 100%; border-spacing: 0;">
                <tr>
                    <td style="font-size: 14px; line-height: 16px; text-align: left;">' . date('d/m/Y') . '</td>
                </tr>
            </table>';
    }
    $content .= '</td>';


    $content .= '<td  width="33%" style="font-size: 18px; line-height: 16px; font-weight: 600; margin: 0; padding: 0; text-align: center;">';
    if ($_GET['orderDetails'] == 1) {
        $content .= '<b>' . showOtherLangText('Requisition Details') . '</b>';
    }
    $content .= '</td>';


    $content .= '<td width="33%" align="right">';
    if ($_GET['address'] == 1) {
        $content .=  '<table style="width: 100%; border-spacing: 0;">
                    <tr>
                        <td style="font-size: 14px; line-height: 16px; font-weight: 600;text-align:right;"></td>
                    </tr>
                </table>


                <table style="width: 100%; border-spacing: 0; margin-top: 5px;">
                    <tr>
                        <td style="font-size: 13px; line-height: 15px; font-weight: 400;text-align:right;">
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
    $content .= '</td> </tr></table>';
} //end address section

//Department details
$content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px;">';
if (
    $_GET['taskNo']  == 1 ||
    $_GET['department']  == 1 ||
    $_GET['member']  == 1 ||
    $_GET['amount']  == 1
) {


    $content .=   '<tr>';

    if ($_GET['amount']  == 1) {
        $content .= '<td width="40%">
                <table style="width: 100%;">
                    <tr> 
                        <td width="50%" style="font-weight:700;padding: 5px;">' . showOtherLangText('Total') . '</td>
                        <td width="50%" style="padding: 5px;">&nbsp;</td> 
                    </tr>
                
                </table>
            </td>';
    }

    if ($_GET['member']  == 1) {
        $content .= '<td width="20%" style="text-align: center;font-weight:700;padding: 5px;">' . showOtherLangText('Member') . '</td>';
    }

    if ($_GET['department']  == 1) {
        $content .=    '<td width="20%">
                <table style="width: 100%;">
                    <tr>
                        <td width="20%" style="padding: 5px;">&nbsp;</td>
                        <td width="80%" style="font-weight:700;padding: 5px;">' . showOtherLangText('Department') . '</td>
                    </tr>
                </table>        
            </td>';
    }

    if ($_GET['taskNo']  == 1) {
        $content .=   '<td width="20%" style="font-weight:700;padding: 5px;">' . showOtherLangText('Task No.') . '</td>';
    }



    $content .=  '</tr>';



    $content .=  '<tr style="background-color: rgba(122, 137, 255, 0.2);">';

    if ($_GET['amount']  == 1) {
        $content .=  '<td width="40%">
            <table style="width: 100%;">
                <tr> 
                    <td width="50%" style="padding:8px 5px; font-weight: 700;">' . getNumFormtPrice($ordDet['ordAmt'], $getDefCurDet['curCode']) . '</td>
                    <td width="50%" style="padding: 5px;">&nbsp;</td>
                </tr>
            </table></td>';
    }

    if ($_GET['member']  == 1) {
        $content .=  '<td width="20%"d style="text-align: center; padding:8px 5px; font-weight: 700;">' . reverseRTLTextForPdf($mebRes['name']) . '</td>';
    }

    if ($_GET['department']  == 1) {
        $content .=  '<td width="20%">
                    <table style="width: 100%;">
                        <tr>
                            <td width="20%" style="padding: 5px;">&nbsp;</td>
                            <td width="80%" style="padding:8px 5px; font-weight: 700;">' . reverseRTLTextForPdf($departRow['departments']) . '</td>
                        </tr>
                    </table>        
                </td>';
    }

    if ($_GET['taskNo']  == 1) {
        $content .=  '<td width="20%" style="padding:8px 5px;font-weight: 700;;">' . ($ordDet['ordNumber']) . '</td>';
    }



    $content .= '</tr>'; //end sup det
}

if ($_GET['customerInvoice']  == 1  || $_GET['amount']  == 1) {
    // $content .= '<table style="color: #000; vertical-align: top; font-size: 12px; width: 100%;border-collapse: collapse;font-family: Arial, Helvetica, sans-serif;">';
    // $content .= '<thead style="vertical-align: middle;">';
    // $content .='<tr>  ';

    if ($_GET['customerInvoice']  == 1) {
        $content .= '<tr>
            <td width="28%">
                <table style="width: 100%;border-collapse: collapse;">
                    <tr>
                        <td width="50%" style="padding: 5px;">Dummy data</td>
                        <td width="50%" style="padding: 5px;">Sub Total</td> 
                    </tr>
                </table>
            </td>

            
            <td width="25%"></td>
            <td width="22%"></td>

            <td width="25%" valign="top">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px;"># Invoice</td>
                        <td style="padding: 5px;">' . ($ordDet['invNo'] ? setPaymentId($ordDet['invNo']) : '') . '</td>
                    </tr>
                </table>
            </td>
            
        </tr>';
    }
    if ($_GET['amount']  == 1  && $getColumnPermission['item_price'] == 1) {
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
            $content .= '<tr>
                                    <td width="28%">
                                        <table style="width: 100%;border-collapse: collapse;">
                                            <tr> 
                                                <td width="50%" style="padding: 5px;">' . getPriceWithCur($chargePrice, $getDefCurDet['curCode'], 0, 1) . '</td>
                                                <td width="50%" style="padding: 5px;">' . showOtherLangText('Sub Total') . '</td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td width="25%" valign="top">
                                        
                                    </td>
                                    <td width="25%"></td>
                                    <td width="22%"></td>
                                    
                                    </tr>';
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
            $content .= '<tr>

             <td width="28%">
                <table style="width: 100%;border-collapse: collapse;">
                    <tr> 
                        <td width="50%" style="padding: 5px;">' . getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1) . '</td>
                        <td width="50%" style="padding: 5px;">' . reverseRTLTextForPdf($row['feeName']) . '</td>
                    </tr>
                </table>
              </td>
            <td width="25%" valign="top">
                
            </td>
            <td width="25%"></td>
            <td width="22%"></td>
           
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
            $content .= '<tr>
            <td width="28%">
                <table style="width: 100%;border-collapse: collapse;">
                    <tr> 
                        <td width="50%" style="padding: 5px;">' . getPriceWithCur($calDiscount, $getDefCurDet['curCode'], 0, 1) . '</td>
                        <td width="50%" style="padding: 5px;">' . reverseRTLTextForPdf($row['feeName']) . '</td>
                    </tr>
                </table>
              </td>
            <td width="25%" valign="top">
                
            </td>
            <td width="25%"></td>
            <td width="22%"></td>
            
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
            $content .= '<tr>
             <td width="28%">
                <table style="width: 100%;border-collapse: collapse;">
                    <tr> 
                        <td width="50%" style="padding: 5px;">' . getPriceWithCur($calTax, $getDefCurDet['curCode'], 0, 1) . '</td>
                        <td width="50%" style="padding: 5px;">' . reverseRTLTextForPdf($row['feeName']) . '</td>
                    </tr>
                </table>
              </td>
            <td width="25%" valign="top">
                
            </td>
            <td width="25%"></td>
            <td width="22%"></td>
           
             </tr>';
        } //Ends order lelvel tax discount charges


        $totalTax = (($chargePrice + $fixedCharges + $totalCalDiscount) * $taxCharges / 100); //total tax feeType=1
        $totalTaxOther = (($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther) * $taxCharges / 100);


        $netTotalAmt = ($chargePrice + $fixedCharges + $totalCalDiscount + $totalTax);
        $netTotalAmtOther = ($chargePriceOther + $fixedChargesOther + $totalCalDiscountOther + $totalTaxOther);

        if ($showGrandTotal) {

            $content .= '<tr>
            <td width="28%">
                <table style="width: 100%;border-collapse: collapse;">
                    <tr> 
                        <td width="50%" style="padding: 5px;">' . getPriceWithCur($netTotalAmt, $getDefCurDet['curCode'], 0, 1) . '</td>
                        <td width="50%" style="padding: 5px;">' . showOtherLangText('Grand Total') . '</td>
                    </tr>
                </table>
              </td>
            <td width="25%" valign="top">
                
            </td>
            <td width="25%"></td>
            <td width="22%"></td>
            
             </tr>';
        }


        $content .= '</table>';
    }
}






//Order items code goes here


if (
    ($_GET['photo'] == 1) ||  ($_GET['itemName'] == 1) || ($_GET['barcode'] == 1) || ($_GET['price'] == 1)  || ($_GET['unit'] == 1)  ||
    ($_GET['reqQty'] == 1) || ($_GET['total'] == 1) ||  ($_GET['qty'] == 1)

) {

    $content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px; text-align: right;">';
    $content .= '<tr style="background-color: rgba(122, 137, 255, 0.2);">';

    if ($_GET['note'] == 1) {
        $note = showOtherLangText('Note');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . reverseRTLTextForPdf($note) . '</th>';
    }

    if ($_GET['total'] == 1) {
        $total =  '(' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Total');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . $total . '</th>';
    }

    if ($_GET['qty'] == 1) {
        $qty = showOtherLangText('Qty');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . $qty . '</th>';
    }

    if ($_GET['reqQty'] == 1) {
        $reqQty = showOtherLangText('Req Qty');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . $reqQty . '</th>';
    }
    if ($_GET['unit'] == 1) {
        $unit = showOtherLangText('C.Unit');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . reverseRTLTextForPdf($unit) . '</th>';
    }
    if ($_GET['price'] == 1) {
        $Price = '(' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Price');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . $Price . '</th>';
    }

    $itemName = '';

    $barcodeText = '';

    if ($_GET['barcode'] == 1) {
        $barcodeText =  showOtherLangText('BarCode');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . $barcodeText . '</th>';
    }
    if ($_GET['itemName'] == 1) {
        $itemName = showOtherLangText('Item');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . reverseRTLTextForPdf($itemName) . '</th>';
    }

    $photo = '';
    if ($_GET['photo'] == 1) {
        $photo = showOtherLangText('Photo');
        $content .= '<th style="font-weight:700;padding:8px 5px;">' . $photo . '</th>';
    }
    $content .= '<th style="font-weight:700;padding:8px 5px;">#</th>';




    $content .= '</tr>';





    $i = 0;
    while ($row = mysqli_fetch_array($otherChrgQry)) {

        $i++;
        $content .= '<tr>';


        $note = '';
        if ($_GET['note'] == 1) {
            $note = $row['note'];
            $content .= '<td style="padding: 5px;">' . reverseRTLTextForPdf($note) . '</td>';
        }

        $total = '';
        if ($_GET['total'] == 1 && $getColumnPermission['item_price'] == 1) {
            $total = getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode'], 0, 1);
            $content .= '<td style="padding: 5px;">' . $total . '</td>';
        }

        $qty = '';
        if ($_GET['qty'] == 1) {
            $qty = '1';
            $content .= '<td style="padding: 5px;">' . $qty . '</td>';
        }

        $reqQty = '';
        if ($_GET['reqQty'] == 1) {
            $reqQty = '1';
            $content .= '<td style="padding: 5px;">' . $reqQty . '</td>';
        }

        $unit = '';
        if ($_GET['unit'] == 1) {
            $unit = $row['unit'];
            $content .= '<td style="padding: 5px;">' . reverseRTLTextForPdf($unit) . '</td>';
        }

        $content .= '<td style="padding: 5px;"></td>';
        $price = '';
        if ($_GET['price'] == 1 && $getColumnPermission['item_price'] == 1) {
            $price = getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1);
            $content .= '<td style="padding: 5px;">' . $price . '</td>';
        }
        if ($_GET['itemName'] == 1) {
            $itemName = $row['itemName'];
            $itemName = '';
            $content .= '<td style="padding: 5px;">' . reverseRTLTextForPdf($itemName) . '</td>';
        }


        if ($_GET['photo'] == 1) {
            $img = '';
            $content .= '<td style="padding: 5px;">' . $img . '</td>';
        }

        $content .= '<td style="padding: 5px;">' . $i . '</td>';

        $content .= '</tr>';
    }






    while ($row = mysqli_fetch_array($proresultSet)) {
        $i++;
        $content .= '<tr>';

        if ($_GET['note'] == 1) {

            $content .= '<td style="padding: 5px;">' . reverseRTLTextForPdf($row['note']) . '</td>';
        }
        if ($_GET['total'] == 1 && $getColumnPermission['item_price'] == 1) {
            $content .= '<td style="padding: 5px;">
            ' . getPriceWithCur($row['totalAmt'], $getDefCurDet['curCode'], 0, 1) . '</td>';
        }
        if ($_GET['qty'] == 1) {
            $content .= '<td style="padding: 5px;">' . $row['qty'] . '</td>';
        }
        if ($_GET['reqQty'] == 1) {
            $content .= '<td style="padding: 5px;">' . $row['requestedQty'] . '</td>';
        }
        if ($_GET['unit'] == 1) {
            $content .= '<td style="padding: 5px;">' . reverseRTLTextForPdf($row['countingUnit']) . '</td>';
        }
        if ($_GET['price'] == 1 && $getColumnPermission['item_price'] == 1) {
            $content .= '<td style="padding: 5px;">
                ' . getPriceWithCur($row['price'], $getDefCurDet['curCode'], 0, 1) . '</td>';
        }
        if ($_GET['barcode'] == 1) {
            $content .= '<td style="padding: 5px;">' . $row['barCode'] . '</td>';
        }
        if ($_GET['itemName'] == 1) {
            $content .= '<td style="padding: 5px;">' . reverseRTLTextForPdf($row['itemName']) . '</td>';
        }
        if ($_GET['photo'] == 1) {
            $img = '';
            if ($row['imgName'] != '' && file_exists(dirname(__FILE__) . "/uploads/" . $accountImgPath . "/products/" . $row['imgName'])) {
                $img = '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/products/' . $row['imgName'] . '" style="width:30px; height:auto;">';
            }
            $content .= '<td style="padding: 5px;">' . $img . '</td>';
        }
        $content .= '<td style="padding: 5px;">' . $i . '</td>';



        $content .= '</tr>';
    }
    $content .=  '</table>';
}

//order journey starts here
if ($_GET['taskRecord'] == 1) {
    $content .= '<table width="100%" style="font-size: 12px; line-height: 14px; border-spacing: 0; margin-top: 20px; text-align: right;">
    ';


    $content .= '<tr style=" background-color: rgba(122, 137, 255, 0.2);">
             <th style="font-weight:700;padding:8px 5px;">' . showOtherLangText('Note') . '</th>
              <th style="font-weight:700;padding:8px 5px;">(' . $getDefCurDet['curCode'] . ')' . showOtherLangText('Price') . '</th>    
            <th style="font-weight:700;padding:8px 5px;">' . showOtherLangText('User') . '</th>
            <th style="font-weight:700;padding:8px 5px;">' . showOtherLangText('Date') . '</th>
            <th style="font-weight:700;padding:8px 5px;">' . showOtherLangText('Status') . '</th>           
           </tr>';


    while ($orderJourney = mysqli_fetch_array($orderJourneyQry)) {
        $content .= '<tr>
        <td style="padding: 5px;">' . ucfirst(reverseRTLTextForPdf($orderJourney['notes'])) . '</td>
        <td style="padding: 5px;">' .
            getPriceWithCur($orderJourney['amount'], $getDefCurDet['curCode'], 0, 1) . '</td> 
        <td style="padding: 5px;">' . '(' . ucfirst(reverseRTLTextForPdf($orderJourney['designation_name'])) . ')' . ucfirst(reverseRTLTextForPdf($orderJourney['name'])) . '</td>
        <td style="padding: 5px;">' . date('h:iA d/m/Y
                ', strtotime($orderJourney['ordDateTime'])) . '</td>

            <td style="padding: 5px;">' . showOtherLangText(ucfirst($orderJourney['action'])) . '</td> 
        </tr> ';
    }

    $content .= '</table>';
}
//end order journey









$content .= '</body></html>';
