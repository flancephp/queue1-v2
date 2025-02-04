<?php
require_once('common/header.php');
checkUesrLogin();

$dateCond = '';
if (isset($_GET['fromDate']) && isset($_GET['toDate'])) 
{

    if (isset($_SESSION['fromDate']) && $_SESSION['fromDate']) 
    {
        unset($_SESSION['fromDate']);
        unset($_SESSION['toDate']);
    }
    
    $_SESSION['fromDate'] = $_GET['fromDate'];
    $_SESSION['toDate'] = $_GET['toDate'];
}
else
{
    $_SESSION['fromDate'] = date('d-m-Y', strtotime('-3 days') );
    $_SESSION['toDate'] = date('d-m-Y');
}

$sql = " SELECT id, accountName, accountNumber FROM tbl_client ";

$execute = mysqli_query($con,$sql);
	
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php'); ?>
        </div>
        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-history" aria-hidden="true"></i>
                    <span class="panel-span">Accounts Activity</span>
                </h3>
            </div>
            <div class="panelData-Bdy">
                <div class="full-panel">
                    <div class="date-form">
                        <form action="" id="frm" name="frm" method="get">
                            <div class="dtMain-Div">
                                From Date:&nbsp;<input type="text" size="10" class="datepicker" placeholder="Click here" name="fromDate" autocomplete="off" value="<?php echo isset($_SESSION['fromDate']) ? $_SESSION['fromDate'] : $_GET['fromDate'];?>">
                            &nbsp;&nbsp;
                            
                            To Date:&nbsp;<input size="10" autocomplete="off" type="text" class="datepicker" name="toDate" placeholder="Click here" value="<?php echo isset($_SESSION['toDate']) ? $_SESSION['toDate'] : $_GET['toDate'];?>">
                            &nbsp;&nbsp;

                            <img src="dist/img/refresh.png" width="26" height="26" onClick="document.frm.submit();" style="cursor: pointer;" title="<?php echo showOtherLangText('Search') ?>">
                            &nbsp;&nbsp;

                            <button type="button" id="button-filter" class="btn-info nglb-Btn"
                                onClick="window.location.href='accountsActivity.php';">Clear Search</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
                <div class="table-responsive">

                    <table class="table  table-bordered table-hover table-striped">

                        <tr class="bc-color">
                            <th scope="col">S. no.</th>
                            <th scope="col">Account Name/ Account no.</th>
                            <th scope="col">Total Task No.</th>
                            <th scope="col">Issue In</th>
                            <th scope="col">Issue Out</th>
                            <th scope="col">Stock.Take</th>
                            <th scope="col">Outlet Stock.Take</th>
                            <th scope="col">Items No.</th>
                            <th scope="col">Efficiency</th>
                            <th scope="col">SignedIn Users</th>
                        </tr>
                        <?php 
				  							
					$count = '';
					while ($clientRow = mysqli_fetch_array($execute))
					{ 
					 	$count++;
					 	$accountNameAndNumber = $clientRow['accountName'].' ('.$clientRow['accountNumber'].')';

					 	$totalOrdNumber = getTotalTaskNo($clientRow['id'], $_SESSION['fromDate'], $_SESSION['toDate']);
                        $totalIssueIn = getTotalIssueIn($clientRow['id'], $_SESSION['fromDate'], $_SESSION['toDate']);
                        $totalIssueOut = getTotalIssueOut($clientRow['id'], $_SESSION['fromDate'], $_SESSION['toDate']);
                        $totalStockTake = getTotalStockTake($clientRow['id'], $_SESSION['fromDate'], $_SESSION['toDate']);

					 	$totalOutLetStockTake = getOutLetStockTake($clientRow['id'], $_SESSION['fromDate'], $_SESSION['toDate']);
                        $getTotalProduct = getTotalProductByAccId($clientRow['id'])
					 	?>

					 	<tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $accountNameAndNumber; ?></td>
                            <td><?php echo $totalOrdNumber; ?></td>
                            <td><?php echo $totalIssueIn; ?></td>
                            <td><?php echo $totalIssueOut; ?></td>
                            <td><?php echo $totalStockTake; ?></td>
                            <td><?php echo $totalOutLetStockTake; ?></td>
                            <td><?php echo $getTotalProduct; ?></td>
                            <td>---</td>
                            <td>---</td>
                        	</tr>
				  							
                        	<?php
				  							}
                        	?>
                        
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy'
        });
    });
</script>
<!-- <script type="text/javascript">
$('#button-filter').on('click', function() {
    url = '';

    var filter_date_to = $('input[name=\'filter_date_to\']').val();

    if (filter_date_to) {
        url += '&filter_date_to=' + encodeURIComponent(filter_date_to);
    }

    var filter_date_from = $('input[name=\'filter_date_from\']').val();

    if (filter_date_from) {
        url += '&filter_date_from=' + encodeURIComponent(filter_date_from);
    }
    location = 'accountsActivity.php?' + url;
});
</script> -->
<?php require_once('common/footer.php'); ?>