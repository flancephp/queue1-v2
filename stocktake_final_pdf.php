<?php
include_once('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

// Get full address and their logo of client
$sql = " SELECT c.name AS countryName, cl.* FROM tbl_client cl
LEFT JOIN tbl_country c ON(cl.country=c.id)
WHERE cl.id = '".$_SESSION['accountId']."' ";
$result = mysqli_query($con, $sql);
$clientDetRow = mysqli_fetch_array($result);

if(isset($_SESSION['newStockTakeVal']))
{
    
    if( is_array($_SESSION['newStockTakeVal']) && !empty($_SESSION['newStockTakeVal']) )
    {
        foreach($_SESSION['newStockTakeVal'] as $row)
        {
            $fileDataRows[$row['BarCode']] = $row['StockTake'];

            $sqlSet = " SELECT * FROM tbl_products WHERE barCode = '".$row['BarCode']."' AND storageDeptId = '".$_SESSION['storeId']."'   AND account_id = '".$_SESSION['accountId']."'  ";

            $resultSet = mysqli_query($con, $sqlSet);

            $productRes = mysqli_fetch_array($resultSet);

            if(!$productRes)
            {
                $notFoundProducts[] = $row['BarCode'];
            }
            else
            {
                $barCodesArr[] = $row['BarCode'];
            }
        }   

            $sql = "SELECT p.*, s.qty stockQty  FROM tbl_stocks s 

            INNER JOIN tbl_products p ON(s.pId = p.id) AND s.account_id = p.account_id WHERE p.barCode IN( ".implode(',', $barCodesArr)." )   AND p.account_id = '".$_SESSION['accountId']."' ";

            $importQry = mysqli_query($con, $sql);

    }

}
///////////////////////////////////////////////
// Mobile stock take part goes here
if( isset($_SESSION['processId']) && isset($_SESSION['storeId']) )
{   
    $sql=" SELECT t.*, p.barCode BarCode FROM  tbl_mobile_items_temp t 
    INNER JOIN tbl_products p ON(p.id = t.pId) AND (p.account_id = t.account_id)

    WHERE t.processId = '".$_SESSION['processId']."'  AND t.account_id = '".$_SESSION['accountId']."'  AND t.`stockTakeType` = 1 AND t.status=1 ";
    $result = mysqli_query($con, $sql);

    $pIdArr = [];
    while($stockTakeRes = mysqli_fetch_array($result) )
    {
        $fileDataRows[$stockTakeRes['BarCode']] = $stockTakeRes['qty'];
        $pIdArr[] = $stockTakeRes['pId'];
    }

    $sql = "SELECT p.*, s.qty stockQty, IF(u.name!='',u.name,p.unitC) unitC  FROM tbl_stocks s 
    INNER JOIN tbl_products p ON(s.pId = p.id) AND (s.account_id = p.account_id) 
    LEFT JOIN tbl_units u ON(u.id=p.unitC) AND (u.account_id = p.account_id)
    WHERE p.id IN( ".implode(',', $pIdArr)." )  AND p.account_id = '".$_SESSION['accountId']."' ";
    $importQry = mysqli_query($con, $sql);
}

$sqlSetAccount = " SELECT * FROM tbl_stores WHERE id = '".$_SESSION['storeId']."'  AND account_id = '".$_SESSION['accountId']."'  ";
$storeQryAcc = mysqli_query($con, $sqlSetAccount);
$storeRowAcc = mysqli_fetch_array($storeQryAcc);




$content = '<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>'.showOtherLangText('Order Details PDF').'</title>
      <link rel="preconnect" href="https://fonts.googleapis.com/">
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
      <link href="./PDF Design_files/css2" rel="stylesheet">
     <style>
        @page { margin: 10px 10px; }
        th {
            text-align:left;
        }
    </style>
   </head>';
   $content .= '<body style="font-family: \'Inter\', sans-serif;color: #232859; font-weight: 400;font-size: 12px; line-height: 14px;">
      <table style="width: 100%; border-spacing: 0; padding-bottom: 16px;">
         <tbody>
            <tr valign="top">
               <td width="50%">
                  <table style="width: 100%; border-spacing: 0;">
                     <tbody>
                        <tr>
                           <td style="font-size: 14px; line-height: 16px; font-weight: 600;">'.$clientDetRow['accountName'].'</td>
                        </tr>
                     </tbody>
                  </table>
                  <table style="width: 100%; border-spacing: 0; margin-top: 5px;">
                     <tbody>
                        <tr>
                           <td style="font-size: 13px; line-height: 15px; font-weight: 400;">
                              '.$clientDetRow['address_one'].'<br>
                              '.$clientDetRow['address_two'].'<br>
                              '.$clientDetRow['city'].', '.$clientDetRow['countryName'].'<br>
                              '.$clientDetRow['email'].'<br>
                              '.$clientDetRow['phone'].'
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td width="50%" align="right">';
               if($clientDetRow["logo"] !='' && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/clientLogo/".$clientDetRow["logo"] ))
                            {  
                                $content .= '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/clientLogo/'.$clientDetRow['logo'].'" style="object-fit: scale-down; height: 50px; width: auto;">';
                            }
                            else
                            {
                                $content .= '<img src="'.$siteUrl.'uploads/pdf-logo-sample.png" alt="Logo" style="object-fit: scale-down; height: 50px; width: auto;">';
                            }  
            $content .= '</td>
            </tr>
         </tbody>
      </table>';
      $content .= '<table style="width: 100%; border-collapse: collapse; margin-block-end: 32px;">
        <tr>
            <td style="width: 50%; border-block: 1px solid #d2d2d2; padding-block: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="flex: 1;">
                        <h6 style="font-weight: 600; font-size: 16px; margin: 0;">'.showOtherLangText('Stock View').'</h6>
                    </div>
                    <div style="flex: 1;">
                        <h6 style="font-weight: 600; font-size: 16px; margin: 0; text-align: center;">'.$storeRowAcc['name'].'</h6>
                    </div>
                    <div style="flex: 1; text-align: right; font-size: 15px;">'.date('d/m/Y').'</div>
                </div>
            </td>
        </tr>
    </table>';
     $varianceQry = $importQry;
     while($variancerow = mysqli_fetch_array($varianceQry))
     {
      $variance = ($fileDataRows[$variancerow['barCode']] - $variancerow['stockQty']);
     if($variance>0)
      {
        $plusvariance  += $variance;
      }
      if($variance<0)
      {
        $minusvariance  += $variance;
      }
      $stockview_pdf[] = $variancerow;
     }
     
    $content .= '<table style="font-size:12px;" width="100%">
        <tr style="vertical-align: baseline;">
            <td style="width: 50%;">
                <table style="width:100%; margin-right:1px; font-size:12px; border-collapse: collapse;">
                    <tr style="font-weight:bold;"" align="center">
                        <td style="padding: 8px 5px;">&nbsp;</td>
                        <td style="padding: 8px 5px;">+Variance</td>
                    </tr>
                    
                    <tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;"" align="center">
                        <td style="padding: 8px 5px;">Total</td>
                        <td style="padding: 8px 5px;">'.$plusvariance.'</td>
                    </tr>
                    
                </table>
            </td>
            <td style="width: 50%;">
                <table style="width:100%; margin-right:1px; font-size:12px; border-collapse: collapse;">
                    <tr style="font-weight:bold;" align="center">
                        <td style="padding: 8px 5px;">-Variance</td>
                    </tr>
                    <tr style="background-color: rgba(122, 137, 255, 0.2); font-weight:bold;" align="center">
                        <td style="padding: 8px 5px; color:#dc3545;">'.($minusvariance!=''?$minusvariance:0).'</td>
                    </tr>
                </table>
            </td>
            
        </tr>
    </table>';
    
    $content .= '<table border="0" cellpadding="5" cellspacing="0" width="100%" style="width:100%; font-size:12px; margin-block-start: 24px; border-collapse: collapse;">
        <tr style="font-weight:bold; background-color: rgba(122, 137, 255, 0.2);">
        <td style="padding: 8px 5px;">#</td>
        <td style="padding: 8px 5px;">'.showOtherLangText('Photo').'</td>
        <td style="padding: 8px 5px;">'.showOtherLangText('Item').'</td>
        <td style="padding: 8px 5px;">'.showOtherLangText('Barcode').'</td>
        <td style="padding: 8px 5px;">'.showOtherLangText('Stock Qty').'</td>
        <td style="padding: 8px 5px;">'.showOtherLangText('Stock take Qty').'</td>
        <td style="padding: 8px 5px;">'.showOtherLangText('Variances').'</td>
        </tr>';
     $i = 0;
     foreach($stockview_pdf as $row)
     {   
        $i++;
        $bgColor = ($i % 2 != 0) ? 'white' : '#F9F9FB';
    $content .=  '<tr style="background-color: ' . $bgColor . ';">
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">'.$i.'</td>
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">'; 
                     if( $row['imgName'] != ''  && file_exists( dirname(__FILE__)."/uploads/".$accountImgPath."/products/".$row['imgName'] )  )
                     {  
                       $content .=  '<img src="'.$siteUrl.'uploads/'.$accountImgPath.'/products/'.$row['imgName'].'" width="60" height="60">';
                        
                     }
           $content .=  '</td>
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;font-weight:700;">'.$row['itemName'].'</td>
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">'.$row['barCode'].'</td>
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8;">'.$row['stockQty'].'</td>
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8; font-weight: 600;">'.$fileDataRows[$row['barCode']].'</td>
            <td style="padding: 8px 5px;border-bottom:1px solid #DFE0E8; color:#dc3545;">'.($fileDataRows[$row['barCode']] - $row['stockQty']).'</td>
        </tr>';
      }
        
        $content .= '</table>
        </body>
        </html>';

