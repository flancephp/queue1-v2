<?php 
require_once('common/header.php');
include_once('script/addDesignation.php');
checkUesrLogin();

?>
<style>

/*----- Add/edit designation page css start from here -----*/

.lower-checkbox-main-section{
margin-top: 5%; width: 100%; display: inline-block;
}
.checkbox-list{
border-bottom: 1px solid grey;
margin-bottom: 20px;

}
.checkbox-list label{
font-weight: 600;
}
.supplier-text, .member-text{
margin:10px 10px; font-weight: 600;
}
.supplier-main-listbox, .member-main-listbox{
display: inline-flex;
}
.supplier-inner-listbox, .member-inner-listbox{
margin-left: 10px; width:180px;
}
.payment-main-box, .invoice-main-box, .newStockTake-main-box{
display:flex;
}
.desableRunningtask, .desableHistory, .desableStock, .desableNewStockTake, .desableRevenueCenter, .desableSetup{
margin-left: 25px !important;
}
.runningtask-main-radio-box, .history-main-radio-box, .stock-main-radio-box, .store-access-box, .revenueCenter-main-radio-box, .stock-main-radio-box{
display:flex;
}
.payment-inner-box, .invoice-inner-box, .newStockTake-inner-box{
margin:10px 10px; font-weight: 600;
}
.newStockTake-inner-box{
width:200px;
}
.runningtask-radio-label-box, .history-radio-label-box, .stock-radio-label-box, .revenueCenter-radio-label-box, .stock-radio-label-box{
margin:10px 10px 0px 10px; width: 200px;
}
.runningtask-radio, .history-radio, .stock-radio, .revenueCenter-radio, .stock-radio{
margin:10px 10px 0px 10px;
}
.payment-radio, .invoice-radio, .newStockTake-radio{
margin:10px 10px;
}
#show-supplier-detail, #show-requisition-detail, #show-runningTask-detail, #show-history-detail, #show-stock-detail, #show-revenueCenter-detail, #show-setup-detail, #show-stocktake-detail{
margin-bottom: 20px;
}
/* New CSS End */
</style>


<div class="container-fluid">
<div class="row">
<div class="col-md-2 reverse-nav-section">
<?php require_once('common/nav.php');?>
</div>
<div class="col-md-10 newClm-10">
<div class="panel panel-default">
<div class="panel-heading">
	<h3 class="panel-title">Add Designation</h3>
</div>
<div class="panel-body">

	<?php 
  if( isset($_SESSION['warning']) )
  {
    if (isset($_SESSION['warning']))
    {
      $successMsg = $_SESSION['warning'];
      $color = "alert-warning";
    }
    
      ?>
      <div class="alert <?php echo $color; ?> alert-dismissible"><i class="fa fa-check-circle"></i><strong>Success!</strong> <?php echo $successMsg;?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
      <?php 
      unset($_SESSION['warning']); 
  } 

  ?>
	<div class="content-row">
		<div class="row table-responsive">
			<form name="frm" id="frm" method="post" action="" class="container">

				<table cellpadding="5" cellspacing="5" style="width:30%;">
					<tbody>
						<tr>
							<td>Name:</td>
							<td style="padding: 5px;">
								<input type="text" name="designation_name" class="form-control" required />
							</td>
		
						</tr>
					</tbody>
				</table>


<div class="lower-checkbox-main-section">
<table>
<tbody>
<tr for="New Order">

<td>

	<div class="checkbox-list">
		<input type="checkbox" name="section_check[]" id="new-order-section" value="1">
		<label>New Order</label>
	</div>

	<div style="display:none;" id="show-supplier-detail">

		<div class="supplier-text">Supplier</div>

		<div class="supplier-text">
			<input type="checkbox" class="supplierCheckall">
			<label>Check All</label>
		</div>

		<?php
		
		while ($supplierRow = mysqli_fetch_array($supplierRes)) 
		{  
			?>
			<div class="supplier-main-listbox">
				<div class="supplier-inner-listbox">
					<input type="checkbox" name="new_supplierCheck[]" id="supplierCheckbox" class="supplierCheckbox" value="<?php echo $supplierRow['id'] ?>">
					<label>
						<?php echo $supplierRow['name'] ?>
					</label>
				</div>
			</div>
			<?php
		}

		?>

		<div class="payment-main-box">
			<div class="payment-inner-box">Access Payment:</div>
			<div class="payment-radio">
				<input type="radio" name="access_payment" id="payment-enable" value="1">
				<label>Enable</label>
			</div>
			<div class="payment-radio">
				<input type="radio" name="access_payment" id="payment-disable" value="0">
				<label>Disable</label>
			</div>
		</div>

	</div>

</td>

</tr>

<tr for="New Requisition">
<td>
	<div class="checkbox-list">

		<input type="checkbox" name="section_check[]" id="new-requisition-section" value="2" >

		<label>New Requisition</label>

	</div>

	<div style="display:none;" id="show-requisition-detail">

		<div class="member-text">Members</div>

		<div class="member-text">
			<input type="checkbox" class="memberCheckall">
			<label>Check All</label>
		</div>

		<?php
		while ($deptUserRow = mysqli_fetch_array($deptUserRes)) 
		{  
			?>
			<div class="member-main-listbox">
				<div class="member-inner-listbox">
					<input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox" value="<?php echo $deptUserRow['id'] ?>">
					<label>
						<?php echo $deptUserRow['name'] ?>
					</label>
				</div>
			</div>
			<?php
		}

		?>

		<div class="invoice-main-box">
			<div class="invoice-inner-box">Access Invoice:</div>
			<div class="invoice-radio">
				<input type="radio" name="access_invoice" id="invoice-enable" value="1">
				<label>Enable</label>
			</div>
			<div class="invoice-radio">
				<input type="radio" name="access_invoice" id="invoice-disable" value="0">
				<label>Disable</label>
			</div>
		</div>

	</div>
</td>
</tr>

<tr for="running Task">
<td>
	<div class="checkbox-list">

		<input type="checkbox" name="section_check[]" id="runningTask-section" value="3" />

		<label>Running Tasks</label>

	</div>

	<div style="display:none;" id="show-runningTask-detail">

		<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box" style="width: 200px;">&nbsp;</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="checkallRunningtask" name="check_all" />
				<label>Check All</label>
			</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="uncheckallRunningtask" name="uncheck_all" />
				<label>Uncheck All</label>
			</div>
		</div>

		<?php
		foreach ($runningTaskArr as $runningTaskSubRow)
		{
			?>
			<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box"><?php echo $runningTaskSubRow['title']; ?>:</div>
			<div class="runningtask-radio enableRunningtask">
				<input type="radio" name="<?php echo $runningTaskSubRow['name']; ?>" class="runningtask-enable" value="1">
				<label>Enable</label>
			</div>
			<div class="runningtask-radio desableRunningtask">
				<input type="radio" name="<?php echo $runningTaskSubRow['name']; ?>" class="runningtask-disable" value="0">
				<label>Disable</label>
			</div>
		</div>
		<?php

		}

		?>
		
	</div>
</td>
</tr>

<tr for="History">
<td>
	<div class="checkbox-list">

		<input type="checkbox" name="section_check[]" id="history-section" value="4" />

		<label>History</label>

	</div>

	<div style="display:none;" id="show-history-detail">

		<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box" style="width: 200px;">&nbsp;</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="checkallHistory" name="check_all" />
				<label>Check All</label>
			</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="uncheckallHistory" name="uncheck_all" />
				<label>Uncheck All</label>
			</div>
		</div>

	<?php

	foreach ($historyArr as $historySubRow)
	{
		?>

		<div class="history-main-radio-box">
			<div class="history-radio-label-box"><?php echo $historySubRow['title']; ?>:</div>
			<div class="history-radio enableHistory">
				<input type="radio" name="<?php echo $historySubRow['name']; ?>" class="history-enable" value="1">
				<label>Enable</label>
			</div>
			<div class="runningtask-radio desableHistory">
				<input type="radio" name="<?php echo $historySubRow['name']; ?>" class="history-disable" value="0">
				<label>Disable</label>
			</div>
		</div>
		<?php
	}
	?>
	</div>
</td>
</tr>

<tr for="Stock">
<td>
	<div class="checkbox-list">

		<input type="checkbox" name="section_check[]" id="stock-section" value="5" />

		<label>Stock</label>

	</div>

	<div style="display:none;" id="show-stock-detail">

		<div class="store-access-box">
			<div style="width:200px;">Store Access:</div>
			<div>
			<?php
			
			while($storeRow=mysqli_fetch_array($storeRes)) 
			{
				?>
				<input type="checkbox" name="store_check[]" id="stock-section"
				class="stock-section" value="<?php echo $storeRow['id'] ?>" >

				<label style="margin-right: 25px;"><?php echo $storeRow['name']; ?>
				</label>
				<?php
			}
			?>
			</div>
		</div>

		<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box" style="width: 200px;">&nbsp;</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="checkallStock" name="check_all" />
				<label>Check All</label>
			</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="uncheckallStock" name="uncheck_all" />
				<label>Uncheck All</label>
			</div>
		</div>

		<?php
		
		foreach ($stockArr as $stockSubRow)
		{
			?>
			<div class="stock-main-radio-box">
				<div class="stock-radio-label-box"><?php echo $stockSubRow['title'] ?>:</div>
				<div class="stock-radio enableStock">
					<input type="radio" name="<?php echo $stockSubRow['name'] ?>" class="stock-enable" value="1">
					<label>Enable</label>
				</div>
				<div class="stock-radio desableStock">
					<input type="radio" name="<?php echo $stockSubRow['name'] ?>" class="stock-disable" value="0">
					<label>Disable</label>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</td>
</tr>

<tr for="New Stock Take">

<td>

	<div class="checkbox-list">
		<input type="checkbox" name="section_check[]" id="new-stocktake-section" value="6">
		<label>New Stocktake</label>
	</div>

	<div style="display:none;" id="show-stocktake-detail">

		<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box" style="width: 200px;">&nbsp;</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="checkallNewStockTake" name="check_all" />
				<label>Check All</label>
			</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="uncheckallNewStockTake" name="uncheck_all" />
				<label>Uncheck All</label>
			</div>
		</div>

		<?php
		foreach ($newStockTakeArr as $newStockTakeSubRow)
		{
			?>
			<div class="newStockTake-main-box">
				<div class="newStockTake-inner-box"><?php echo $newStockTakeSubRow['title'] ?>:</div>
				<div class="newStockTake-radio enableNewStockTake">
					<input type="radio" name="<?php echo $newStockTakeSubRow['name'] ?>" class="newStockTake-enable" value="1">
					<label>Enable</label>
				</div>
				<div class="newStockTake-radio desableNewStockTake">
					<input type="radio" name="<?php echo $newStockTakeSubRow['name'] ?>" class="newStockTake-disable" value="0">
					<label>Disable</label>
				</div>
			</div>
			<?php
		}
		?>

	</div>

</td>

</tr>

<tr for="Revenue Center">
<td>
	<div class="checkbox-list">

		<input type="checkbox" name="section_check[]" id="revenueCenter-section" value="7" />

		<label>Revenue Center</label>

	</div>

	<div style="display:none;" id="show-revenueCenter-detail">

		<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box" style="width: 200px;">&nbsp;</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="checkallRevenueCenter" name="check_all" />
				<label>Check All</label>
			</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="uncheckallRevenueCenter" name="uncheck_all" />
				<label>Uncheck All</label>
			</div>
		</div>

	<?php
	
	foreach ($revCenterArr as $RevCenterSubRow)
	{
		?>
		<div class="revenueCenter-main-radio-box">
			<div class="revenueCenter-radio-label-box"><?php echo $RevCenterSubRow['title'] ?>:</div>
			<div class="revenueCenter-radio enableRevenueCenter">
				<input type="radio" name="<?php echo $RevCenterSubRow['name'] ?>" class="revenueCenter-enable" value="1">
				<label>Enable</label>
			</div>
			<div class="revenueCenter-radio desableRevenueCenter">
				<input type="radio" name="<?php echo $RevCenterSubRow['name'] ?>" class="revenueCenter-disable" value="0">
				<label>Disable</label>
			</div>
		</div>
		<?php
	}
	?>
	</div>
</td>
</tr>

<tr for="Setup">
<td>
	<div class="checkbox-list">

		<input type="checkbox" name="section_check[]" id="setup-section" value="8" />

		<label>Setup</label>

	</div>

	<div style="display:none;" id="show-setup-detail">

		<div class="runningtask-main-radio-box">
			<div class="runningtask-radio-label-box" style="width: 200px;">&nbsp;</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="checkallSetup" name="check_all" />
				<label>Check All</label>
			</div>
			<div class="runningtask-radio">
				<input type="checkbox" id="uncheckallSetup" name="uncheck_all" />
				<label>Uncheck All</label>
			</div>
		</div>

	<?php
	foreach ($setupArr as $setupSubRow)
	{
		?>
		<div class="stock-main-radio-box">
			<div class="stock-radio-label-box"><?php echo $setupSubRow['title'] ?>:</div>
			<div class="stock-radio enableSetup">
				<input type="radio" name="<?php echo $setupSubRow['name'] ?>" class="setup-enable" value="1">
				<label>Enable</label>
			</div>
			<div class="stock-radio desableSetup">
				<input type="radio" name="<?php echo $setupSubRow['name'] ?>" class="setup-disable" value="0">
				<label>Disable</label>
			</div>
		</div>
		<?php
	}
	?>

	</div>
</td>
</tr>

</tbody>
</table>
</div>


				<div style="margin-top: 80px;">

					<button class="btn btn-primary" type="submit">Save</button>&nbsp;

					<button class="btn btn-primary" type="button" onClick="window.location.href='listDesignation.php?account_id=<?php echo $_GET['account_id'] ?>'">Back</button>
				</div>

			</form>

		</div>
	</div>
</div><!-- panel body -->
</div>
</div><!-- content -->
</div>
</div>



<script>
$(".supplierCheckall").on('click', function(){

$('.supplierCheckbox:checkbox').not(this).prop('checked', this.checked);
});

$(".memberCheckall").on('click', function(){

$('.requisitionCheckbox:checkbox').not(this).prop('checked', this.checked);
});
</script>
<script>
$('#new-order-section').click(function(){

var current_display = $('#show-supplier-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-supplier-detail').css('display','block');
$('#new-order-section').prop('checked', true);

$('#payment-enable').prop('checked', true);

$('.supplierCheckall').prop('checked', true);

$('.supplierCheckbox').prop('checked', true);


$('.supplierCheckbox').click(function(){

	var totalCount = $('.supplierCheckbox').length;
	
	var totalCheckedCount = $('.supplierCheckbox:checked').length;
	

	if (totalCount == totalCheckedCount) {

		$('.supplierCheckall').prop('checked', true);
	}
	else
	{
		$('.supplierCheckall').prop('checked', false);
	}

});

}
else
{
$('#show-supplier-detail').css('display','none');
$('#new-order-section').prop('checked', false);
}

});
</script>

<script>
$('#new-requisition-section').click(function(){

var current_display = $('#show-requisition-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-requisition-detail').css('display','block');
$('#new-requisition-section').prop('checked', true);

$('#invoice-enable').prop('checked', true);

$('.memberCheckall').prop('checked', true);

$('.requisitionCheckbox').prop('checked', true);

$('.requisitionCheckbox').click(function(){

	var totalCount = $('.requisitionCheckbox').length;
	
	var totalCheckedCount = $('.requisitionCheckbox:checked').length;
	

	if (totalCount == totalCheckedCount) {

		$('.memberCheckall').prop('checked', true);
	}
	else
	{
		$('.memberCheckall').prop('checked', false);
	}

});
}
else
{
$('#show-requisition-detail').css('display','none');
$('#new-requisition-section').prop('checked', false);
}

});
</script>

<script>
$('#runningTask-section').click(function()
{
var current_display = $('#show-runningTask-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-runningTask-detail').css('display','block');
$('#runningTask-section').prop('checked', true);

$('#checkallRunningtask').prop('checked', true);

$('.runningtask-enable').prop('checked', true);


$('.runningtask-enable, .runningtask-disable').click(function(){

	var totalCount = $('.runningtask-enable:radio').length;
	//alert('totalCount='+totalCount);

	var totalEnableCount = $('.runningtask-enable:checked').length;
	//alert('totalEnableCount='+totalEnableCount);

	var totalDisableCount = $('.runningtask-disable:checked').length;
	//alert('totalDisableCount='+totalDisableCount);


	if (totalCount == totalEnableCount) {

		$('#checkallRunningtask').prop('checked', true);
	}
	else
	{
		$('#checkallRunningtask').prop('checked', false);
	}

	if (totalCount == totalDisableCount) {

		$('#uncheckallRunningtask').prop('checked', true);
	}
	else
	{
		$('#uncheckallRunningtask').prop('checked', false);
	}

});

}
else
{
$('#show-runningTask-detail').css('display','none');
$('#runningTask-section').prop('checked', false);
}
});
</script>

<script>
$('#history-section').click(function(){

var current_display = $('#show-history-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-history-detail').css('display','block');
$('#history-section').prop('checked', true);

$('#checkallHistory').prop('checked', true);

$('.history-enable').prop('checked', true);


$('.history-enable, .history-disable').click(function(){

	var totalCount = $('.history-enable:radio').length;
	//alert('totalCount='+totalCount);

	var totalEnableCount = $('.history-enable:checked').length;
	//alert('totalEnableCount='+totalEnableCount);

	var totalDisableCount = $('.history-disable:checked').length;
	//alert('totalDisableCount='+totalDisableCount);


	if (totalCount == totalEnableCount) {

		$('#checkallHistory').prop('checked', true);
	}
	else
	{
		$('#checkallHistory').prop('checked', false);
	}

	if (totalCount == totalDisableCount) {

		$('#uncheckallHistory').prop('checked', true);
	}
	else
	{
		$('#uncheckallHistory').prop('checked', false);
	}

});
}
else
{
$('#show-history-detail').css('display','none');
$('#history-section').prop('checked', false);
}

});
</script>

<script>
$('#stock-section').click(function(){

var current_display = $('#show-stock-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-stock-detail').css('display','block');
$('#stock-section').prop('checked', true);

$('#checkallStock').prop('checked', true);

$('.stock-enable').prop('checked', true);

$('.stock-section').prop('checked', true);


$('.stock-enable, .stock-disable').click(function(){

	var totalCount = $('.stock-enable:radio').length;
	//alert('totalCount='+totalCount);

	var totalEnableCount = $('.stock-enable:checked').length;
	//alert('totalEnableCount='+totalEnableCount);

	var totalDisableCount = $('.stock-disable:checked').length;
	//alert('totalDisableCount='+totalDisableCount);


	if (totalCount == totalEnableCount) {

		$('#checkallStock').prop('checked', true);
	}
	else
	{
		$('#checkallStock').prop('checked', false);
	}

	if (totalCount == totalDisableCount) {

		$('#uncheckallStock').prop('checked', true);
	}
	else
	{
		$('#uncheckallStock').prop('checked', false);
	}

});
}
else
{
$('#show-stock-detail').css('display','none');
$('#stock-section').prop('checked', false);
}

});
</script>

<script>
$('#new-stocktake-section').click(function(){

var current_display = $('#show-stocktake-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-stocktake-detail').css('display','block');
$('#new-stocktake-section').prop('checked', true); 

$('#checkallNewStockTake').prop('checked', true);

$('.newStockTake-enable').prop('checked', true);


$('.newStockTake-enable, .newStockTake-disable').click(function(){

	var totalCount = $('.newStockTake-enable:radio').length;
	//alert('totalCount='+totalCount);

	var totalEnableCount = $('.newStockTake-enable:checked').length;
	//alert('totalEnableCount='+totalEnableCount);

	var totalDisableCount = $('.newStockTake-disable:checked').length;
	//alert('totalDisableCount='+totalDisableCount);


	if (totalCount == totalEnableCount) {

		$('#checkallNewStockTake').prop('checked', true);
	}
	else
	{
		$('#checkallNewStockTake').prop('checked', false);
	}

	if (totalCount == totalDisableCount) {

		$('#uncheckallNewStockTake').prop('checked', true);
	}
	else
	{
		$('#uncheckallNewStockTake').prop('checked', false);
	}

});
}
else
{
$('#show-stocktake-detail').css('display','none');
$('#new-stocktake-section').prop('checked', false);
}

});
</script>

<script>
$('#revenueCenter-section').click(function(){

var current_display = $('#show-revenueCenter-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-revenueCenter-detail').css('display','block');
$('#revenueCenter-section').prop('checked', true);

$('#checkallRevenueCenter').prop('checked', true);

$('.revenueCenter-enable').prop('checked', true);


$('.revenueCenter-enable, .revenueCenter-disable').click(function(){

	var totalCount = $('.revenueCenter-enable:radio').length;
	//alert('totalCount='+totalCount);

	var totalEnableCount = $('.revenueCenter-enable:checked').length;
	//alert('totalEnableCount='+totalEnableCount);

	var totalDisableCount = $('.revenueCenter-disable:checked').length;
	//alert('totalDisableCount='+totalDisableCount);


	if (totalCount == totalEnableCount) {

		$('#checkallRevenueCenter').prop('checked', true);
	}
	else
	{
		$('#checkallRevenueCenter').prop('checked', false);
	}

	if (totalCount == totalDisableCount) {

		$('#uncheckallRevenueCenter').prop('checked', true);
	}
	else
	{
		$('#uncheckallRevenueCenter').prop('checked', false);
	}

});
}
else
{
$('#show-revenueCenter-detail').css('display','none');
$('#revenueCenter-section').prop('checked', false);
}

});
</script>

<script>
$('#setup-section').click(function(){

var current_display = $('#show-setup-detail').css('display');

if (current_display == 'none' || '') 
{
$('#show-setup-detail').css('display','block');
$('#setup-section').prop('checked', true);

$('#checkallSetup').prop('checked', true);

$('.setup-enable').prop('checked', true);


$('.setup-enable, .setup-disable').click(function(){

	var totalCount = $('.setup-enable:radio').length;
	//alert('totalCount='+totalCount);

	var totalEnableCount = $('.setup-enable:checked').length;
	//alert('totalEnableCount='+totalEnableCount);

	var totalDisableCount = $('.setup-disable:checked').length;
	//alert('totalDisableCount='+totalDisableCount);


	if (totalCount == totalEnableCount) {

		$('#checkallSetup').prop('checked', true);
	}
	else
	{
		$('#checkallSetup').prop('checked', false);
	}

	if (totalCount == totalDisableCount) {

		$('#uncheckallSetup').prop('checked', true);
	}
	else
	{
		$('#uncheckallSetup').prop('checked', false);
	}

});
}
else
{
$('#show-setup-detail').css('display','none');
$('#setup-section').prop('checked', false);
}

});

// RunningTask check all/ uncheck all
$("#checkallRunningtask").on('click', function(){
//alert('clicked');
if($("#uncheckallRunningtask").is(':checked'))
{
$('#uncheckallRunningtask').prop('checked', false);
}

$('.enableRunningtask input:radio').not(this).prop('checked', this.checked);
});

$("#uncheckallRunningtask").on('click', function(){
//alert('clicked');
if($("#checkallRunningtask").is(':checked'))
{
$('#checkallRunningtask').prop('checked', false);
}

$('.desableRunningtask input:radio').not(this).prop('checked', this.checked);
});

//History check all/uncheck all
$("#checkallHistory").on('click', function(){
//alert('clicked');
if($("#uncheckallHistory").is(':checked'))
{
$('#uncheckallHistory').prop('checked', false);
}

$('.enableHistory input:radio').not(this).prop('checked', this.checked);
});

$("#uncheckallHistory").on('click', function(){
//alert('clicked');
if($("#checkallHistory").is(':checked'))
{
$('#checkallHistory').prop('checked', false);
}

$('.desableHistory input:radio').not(this).prop('checked', this.checked);
});

//Stock check all/uncheck all
$("#checkallStock").on('click', function(){
//alert('clicked');
if($("#uncheckallStock").is(':checked'))
{
$('#uncheckallStock').prop('checked', false);
}

$('.enableStock input:radio').not(this).prop('checked', this.checked);
});

$("#uncheckallStock").on('click', function(){
//alert('clicked');
if($("#checkallStock").is(':checked'))
{
$('#checkallStock').prop('checked', false);
}

$('.desableStock input:radio').not(this).prop('checked', this.checked);
});

//NewStockTake check all/uncheck all
$("#checkallNewStockTake").on('click', function(){
//alert('clicked');
if($("#uncheckallNewStockTake").is(':checked'))
{
$('#uncheckallNewStockTake').prop('checked', false);
}

$('.enableNewStockTake input:radio').not(this).prop('checked', this.checked);
});

$("#uncheckallNewStockTake").on('click', function(){
//alert('clicked');
if($("#checkallNewStockTake").is(':checked'))
{
$('#checkallNewStockTake').prop('checked', false);
}

$('.desableNewStockTake input:radio').not(this).prop('checked', this.checked);
});

//RevenueCenter check all/uncheck all
$("#checkallRevenueCenter").on('click', function(){
//alert('clicked');
if($("#uncheckallRevenueCenter").is(':checked'))
{
$('#uncheckallRevenueCenter').prop('checked', false);
}

$('.enableRevenueCenter input:radio').not(this).prop('checked', this.checked);
});

$("#uncheckallRevenueCenter").on('click', function(){
//alert('clicked');
if($("#checkallRevenueCenter").is(':checked'))
{
$('#checkallRevenueCenter').prop('checked', false);
}

$('.desableRevenueCenter input:radio').not(this).prop('checked', this.checked);
});

//Setup check all/uncheck all
$("#checkallSetup").on('click', function(){
//alert('clicked');
if($("#uncheckallSetup").is(':checked'))
{
$('#uncheckallSetup').prop('checked', false);
}

$('.enableSetup input:radio').not(this).prop('checked', this.checked);
});

$("#uncheckallSetup").on('click', function(){
//alert('clicked');
if($("#checkallSetup").is(':checked'))
{
$('#checkallSetup').prop('checked', false);
}

$('.desableSetup input:radio').not(this).prop('checked', this.checked);
});
</script>

<?php require_once('common/footer.php');?>