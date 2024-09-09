<?php 
    include('../inc/dbConfig.php'); //connection details
    $pgnm = 'Bar Control';
    $barControlStep = '1';

    //Get language Type 
    $getLangType = getLangType($_SESSION['language_id']);

    $checkCurPage = checkCurPage();
    if ($checkCurPage == 5) {
        echo "<script>window.location.href='".$siteUrl."'</script>";
        exit;
    }

    if( !isset($_SESSION['id']) ||  $_SESSION['id'] < 1){
        echo "<script>window.location.href='".$siteUrl."';</script>";
        exit;
    }

    $sql = "SELECT  rc.id, rc.name, du.account_id, du.name as outletName, rcd.id as outLetId, rcd.outLetType FROM tbl_revenue_center_departments rcd 
    INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId)  AND du.account_id = rcd.account_id 
    INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId)  AND rc.account_id = rcd.account_id 
    INNER JOIN tbl_outlet_items o ON(o.outLetId = rcd.id) AND o.account_id = rcd.account_id AND o.status=1 
    WHERE du.account_id = '".$_SESSION['accountId']."' 
    GROUP BY rc.id order by rc.id desc ";
            
    $revQry = mysqli_query($con, $sql);

    $totalItems = 0;
    if( isset($_GET['outLetId'])){
        $totalItems = getOutLetItemsCount($_GET['outLetId'], 1);
        $outLetDet = getRevenueOutLetDetailsById($_GET['outLetId']);
        $_GET['revenueId'] =  $outLetDet['revCenterId'];
    }

    if(isset($_GET['revenueId'])){
        $sql = "SELECT du.name, rcd.id, rcd.account_id  FROM tbl_revenue_center_departments rcd
            INNER JOIN tbl_deptusers du ON(du.id = rcd.deptId) AND du.account_id = rcd.account_id
            INNER JOIN tbl_revenue_center rc ON(rc.id = rcd.revCenterId) AND rc.account_id = rcd.account_id AND rc.id = '".$_GET['revenueId']."'
            WHERE  rcd.account_id = '".$_SESSION['accountId']."' 
            GROUP BY rcd.id order by du.name  ";
            
        $outLetQry = mysqli_query($con, $sql);
    }
?>
<!DOCTYPE html>
<html dir="<?php echo $getLangType == '1' ?'rtl' : ''; ?>" lang="<?php echo $getLangType == '1' ? 'he' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title><?php echo showOtherLangText('Start') ?> - Queue1 Mobile</title>
    <?php include('layout/mCss.php'); ?>
</head>
<body>
    <section class="headSec">
        <div class="container">
            <?php include('layout/mHeader.php'); ?>
            <div class="text-center">
                <a href="<?php echo $mobileSiteUrl;?>barControl2.php?revenueId=<?php echo $_GET['revenueId'];?>&stockTakeId=<?php echo $_GET['outLetId'];?>&start=1" class="stockTake-Btn"><?php echo showOtherLangText('Start StockTaking') ?></a>
            </div>
        </div>
    </section>
    <section class="storeSection finishSection">
        <div class="container">
            <div class="storeDtl brControl overflow-hidden">
                <!-- <h3 class="mblFnt2">Storage Stocktaking</h3> -->
                <div class="inner__content bg-white">
                    <form method="get" id="frm" name="frm">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="stkData">
                                <p class="strHead">
                                    <?php echo showOtherLangText('Date'); ?>
                                </p>
                                <p class="barStr-Date"><?php echo date('d/m/Y');?></p>
                            </div>
                            <div class="stkData">
                                <p class="strHead">
                                    <?php echo showOtherLangText('Revenue Center'); ?>
                                </p>
                                <select name="revenueId" class="form-select revCntr-Bar" aria-label="Default select example" onChange="showOutLets(this.value);">
                                    <option value=""><?php echo showOtherLangText('Select') ?></option>
                                    <?php 
                                        while($revRow = mysqli_fetch_array($revQry)){
                                            $sel = $revRow['id'] == $_GET['revenueId'] ? 'selected="selected"' : '';
                                            echo '<option value="'.$revRow['id'].'" '.$sel.'>'.$revRow['name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="stkData">
                                <p class="strHead"><?php echo showOtherLangText('OutLet') ?></p>
                                <select name="outLetId" id="outLetId" class="form-select outlet-Bar" aria-label="Default select example" onChange="this.form.submit();">
                                    <option value=""><?php echo showOtherLangText('Select') ?></option>
                                    <?php 
                                        while($outLet = mysqli_fetch_array($outLetQry)){
                                            $sel = $outLet['id'] == $_GET['outLetId'] ? 'selected="selected"' : '';
                                            echo '<option value="'.$outLet['id'].'" '.$sel.'>'.$outLet['name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="mt-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="brClm-6 brClmhead">
                                <p><?php echo showOtherLangText('All Items') ?></p>
                            </div>
                            <div class="brClm-6 brClm-Dtl">
                                <p><?php echo $totalItems;?></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                            <div class="brClm-6 brClmhead">
                                <p><?php echo showOtherLangText('Counted') ?></p>
                            </div>
                            <div class="brClm-6 brClm-Dtl">
                                <p>0</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                            <div class="brClm-6 brClmhead">
                                <p><?php echo showOtherLangText('Not Counted') ?></p>
                            </div>
                            <div class="brClm-6 brClm-Dtl">
                                <p>0</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <?php include('layout/mJs.php'); ?>
    <script>
	    function showOutLets(revId){
			$.ajax({
			    method: "POST",
			    url: "ajax.php",
			    data: { revId:revId }
			})
		    .done(function( outLetData ) {
		  	    //$('#outLetId').html('');
			    $('#outLetId').html(outLetData);
		    });
	    }
	</script>
</body>
</html>