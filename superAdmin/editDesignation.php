<?php 
require_once('common/header.php');
include_once('script/editDesignation.php');
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
      <div class="col-md-2">
        <?php require_once('common/nav.php');?>
      </div>

      <div class="col-md-10 newClm-10">
        <div class="panel panel-default">
          <div class="panel-heading">
		  		<div class="pull-right" style="margin:-7px 0px 0px 0px;">
					<button  type="submit" form="frm"  class="btn btn-primary"><i class="fa fa-save"></i></button>&nbsp;
					<button class="btn btn-primary" type="button" onClick="window.location.href='listDesignation.php?account_id=<?php echo $_GET['account_id'] ?>'">Back</button>
				</div>
				<h3 class="panel-title">Edit Designation</h3>
				
				</div>
				<div class="panel-body">
					<div class="content-row">
						<div class="row table-responsive">


							<!-- Alert Message part start here	 -->

							<?php $color = (isset($_GET['error']) && $_GET['error'] > 0)  ? 'color: #FF0000' : 'color: #038B00'; ?>

								<h6 style="<?php echo $color?>">
									<?php 
									echo isset($_GET['error']) ? ''.showOtherLangText('You can not create the same designation name as this name already created').'' : '';
									?>
									
								</h6>

							<form name="frm" id="frm" method="post" action="editDesignation.php?id=<?php echo $_GET['id'];?>&account_id=<?php echo $_GET['account_id'];?>" class="container">

								<table cellpadding="5" cellspacing="5" style="width:30%;">
									<tbody>
										<tr>
											<td>Name:</td>
											<td style="padding: 5px;"><input type="text" name="designation_name" class="form-control" value="<?php echo $designationName; ?>" required /></td>
						
										</tr>
									</tbody>
								</table>


<div class="lower-checkbox-main-section">
	
	<table>
		<tbody>
			<tr for="New Order">

				<td>

					<div class="checkbox-list">
						<input type="checkbox" name="section_check[]" id="new-order-section" value="1" <?php echo in_array('1', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />
						<label>New Order</label>
					</div>

					<div style="display:none;" id="show-supplier-detail">

						<div class="supplier-text">Supplier</div>

						<div class="supplier-text">
							<input type="checkbox" class="supplierCheckall" id="supplierCheckall">
							<label>Check All</label>
						</div>

						<?php
						
						while ($supplierRow = mysqli_fetch_array($supplierRes)) 
						{  

							?>

							<div class="supplier-main-listbox">
								<div class="supplier-inner-listbox">
									<input type="checkbox" name="new_supplierCheck[]"  id="supplierCheckbox" class="supplierCheckbox" value="<?php echo $supplierRow['id'] ?>" <?php echo in_array($supplierRow['id'], $supIdsArr) ? 'checked="checked"' : ''?> />
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
								<input type="radio" name="access_payment" <?php echo $accessPaymentRow['type_id'] == 1 ? 'checked="checked"' : '';?> class="payment-enable" value="1" />
								<label>Enable</label>
							</div>
							<div class="payment-radio">
								<input type="radio" name="access_payment" <?php echo $accessPaymentRow['type_id'] == 0 ? 'checked="checked"' : '';?> class="payment-disable" value="0" />
								<label>Disable</label>
							</div>
						</div>

					</div>

				</td>

			</tr>

			<tr for="New Requisition">
				<td>
					<div class="checkbox-list">

						<input type="checkbox" name="section_check[]" id="new-requisition-section" value="2" <?php echo in_array('2', $selectedSectionArr) ? 'checked="checked"' : ''; ?> / >

						<label>New Requisition</label>

					</div>

					<div style="display:none;" id="show-requisition-detail">

						<div class="member-text">Members</div>

						<div class="member-text">
							<input type="checkbox" class="memberCheckall" id="memberCheckall">
							<label>Check All</label>
						</div>

						<?php
						while ($deptUserRow = mysqli_fetch_array($deptUserRes)) 
						{  

							?>

							<div class="member-main-listbox">
								<div class="member-inner-listbox">
									<input type="checkbox" name="new_memberCheck[]" id="requisitionCheckbox" class="requisitionCheckbox"  value="<?php echo $deptUserRow['id'] ?>" <?php echo in_array($deptUserRow['id'], $memIdsArr) ? 'checked="checked"' : ''?> />
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
								<input type="radio" name="access_invoice" <?php echo $AccessInvoiceRow['type_id'] == 1 ? 'checked="checked"' : '';?> class="invoice-enable" value="1" />
								<label>Enable</label>
							</div>
							<div class="invoice-radio">
								<input type="radio" name="access_invoice" <?php echo $AccessInvoiceRow['type_id'] == 0 ? 'checked="checked"' : '';?> class="invoice-disable" value="0" />
								<label>Disable</label>
							</div>
						</div>

					</div>
				</td>

			</tr>

			<tr for="running Task">
				<td>
					<div class="checkbox-list">

						<input type="checkbox" name="section_check[]" id="runningTask-section" value="3" <?php echo in_array('3', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />

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
							$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '".$runningTaskSubRow['name']."' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
							$accessRunningtaskRes = mysqli_query($con, $sql);
							$AccessRunningtaskRow = mysqli_fetch_array($accessRunningtaskRes);

							?>
							<div class="runningtask-main-radio-box">
							<div class="runningtask-radio-label-box"><?php echo $runningTaskSubRow['title']; ?>:</div>
							<div class="runningtask-radio enableRunningtask">
								<input type="radio" name="<?php echo $runningTaskSubRow['name']; ?>" <?php echo $AccessRunningtaskRow['type_id'] == 1 ? 'checked="checked"' : '';?> class="runningtask-enable" value="1">
								<label>Enable</label>
							</div>
							<div class="runningtask-radio desableRunningtask">
								<input type="radio" name="<?php echo $runningTaskSubRow['name']; ?>" <?php echo $AccessRunningtaskRow['type_id'] == 0 ? 'checked="checked"' : '';?> class="runningtask-disable" value="0">
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

						<input type="checkbox" name="section_check[]" id="history-section" value="4" <?php echo in_array('4', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />

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
						$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '". $historySubRow['name']."' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
						$accesshistoryRes = mysqli_query($con, $sql);
						$accesshistoryRow = mysqli_fetch_array($accesshistoryRes);
						?>

						<div class="history-main-radio-box">
							<div class="history-radio-label-box"><?php echo $historySubRow['title']; ?>:</div>
							<div class="history-radio enableHistory">
								<input type="radio" name="<?php echo $historySubRow['name']; ?>" <?php echo $accesshistoryRow['type_id'] == 1 ? 'checked="checked"' : ''; ?> class="history-enable" value="1">
								<label>Enable</label>
							</div>
							<div class="runningtask-radio desableHistory">
								<input type="radio" name="<?php echo $historySubRow['name']; ?>" <?php echo $accesshistoryRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> class="history-disable" value="0">
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

						<input type="checkbox" name="section_check[]" id="stock-section" value="5" <?php echo in_array('5', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />

						<label>Stock</label>

					</div>

					<div style="display:none;" id="show-stock-detail">

						<div class="store-access-box">
							<div style="width:200px;">Store Access:</div>
							<div>
							<?php
							

							while ($storeRow=mysqli_fetch_array($storeRes)) 
							{
								?>
								<input type="checkbox" name="store_check[]" id="stock-section"
								class="stock-section" value="<?php echo $storeRow['id'] ?>" <?php echo in_array($storeRow['id'], $storeIdsArr) ? 'checked="checked"' : ''?> />

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
								$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '". $stockSubRow['name']."' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
								$accessStockRes = mysqli_query($con, $sql);
								$accessStockRow = mysqli_fetch_array($accessStockRes);

							?>
							<div class="stock-main-radio-box">
								<div class="stock-radio-label-box"><?php echo $stockSubRow['title'] ?>:</div>
								<div class="stock-radio enableStock">
									<input type="radio" name="<?php echo $stockSubRow['name'] ?>" class="stock-enable" value="1" <?php echo $accessStockRow['type_id'] == 1 ? 'checked="checked"' : ''; ?> />
									<label>Enable</label>
								</div>
								<div class="stock-radio desableStock">
									<input type="radio" name="<?php echo $stockSubRow['name'] ?>" class="stock-disable" value="0" <?php echo $accessStockRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> />
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
						<input type="checkbox" name="section_check[]" id="new-stocktake-section" value="6" <?php echo in_array('6', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />
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
							$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '". $newStockTakeSubRow['name']."' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
							$accessNewStockTakeRes = mysqli_query($con, $sql);
							$accessNewStockTakeRow = mysqli_fetch_array($accessNewStockTakeRes);

							?>
							<div class="newStockTake-main-box">
								<div class="newStockTake-inner-box"><?php echo $newStockTakeSubRow['title'] ?>:</div>
								<div class="newStockTake-radio enableNewStockTake">
									<input type="radio" name="<?php echo $newStockTakeSubRow['name'] ?>" class="newStockTake-enable" value="1" <?php echo $accessNewStockTakeRow['type_id'] == 1 ? 'checked="checked"' : ''; ?> />
									<label>Enable</label>
								</div>
								<div class="newStockTake-radio desableNewStockTake">
									<input type="radio" name="<?php echo $newStockTakeSubRow['name'] ?>" class="newStockTake-disable" value="0" <?php echo$accessNewStockTakeRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> />
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

						<input type="checkbox" name="section_check[]" id="revenueCenter-section" value="7" <?php echo in_array('7', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />

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
					foreach ($revCenterArr as $revCenterSubRow)
					{
						$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '". $revCenterSubRow['name']."' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
						$accessRevCenterRes = mysqli_query($con, $sql);
						$accessRevCenterRow = mysqli_fetch_array($accessRevCenterRes);

						?>
						<div class="revenueCenter-main-radio-box">
							<div class="revenueCenter-radio-label-box"><?php echo $revCenterSubRow['title'] ?>:</div>
							<div class="revenueCenter-radio enableRevenueCenter">
								<input type="radio" name="<?php echo $revCenterSubRow['name'] ?>" class="revenueCenter-enable" value="1" <?php echo $accessRevCenterRow['type_id'] == 1 ? 'checked="checked"' : ''; ?> />
								<label>Enable</label>
							</div>
							<div class="revenueCenter-radio desableRevenueCenter">
								<input type="radio" name="<?php echo $revCenterSubRow['name'] ?>" class="revenueCenter-disable" value="0" <?php echo $accessRevCenterRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> />
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

						<input type="checkbox" name="section_check[]" id="setup-section" value="8" <?php echo in_array('8', $selectedSectionArr) ? 'checked="checked"' : ''; ?> />

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

						$sql = " SELECT * FROM tbl_designation_sub_section_permission WHERE type = '". $setupSubRow['name']."' AND designation_id = '".$_GET['id']."' AND account_id = '".$accountId."' ";
						$accessSetupRes = mysqli_query($con, $sql);
						$accessSetupRow = mysqli_fetch_array($accessSetupRes);
						
						?>
						<div class="stock-main-radio-box">
							<div class="stock-radio-label-box"><?php echo $setupSubRow['title'] ?>:</div>
							<div class="stock-radio enableSetup">
								<input type="radio" name="<?php echo $setupSubRow['name'] ?>" class="setup-enable" value="1" <?php echo $accessSetupRow['type_id'] == 1 ? 'checked="checked"' : ''; ?> />
								<label>Enable</label>
							</div>
							<div class="stock-radio desableSetup">
								<input type="radio" name="<?php echo $setupSubRow['name'] ?>" class="setup-disable" value="0" <?php echo $accessSetupRow['type_id'] == 0 ? 'checked="checked"' : ''; ?> />
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

							</form>

						</div>
					</div>
				</div><!-- panel body -->
			</div>
		</div><!-- content -->
	</div>
</div>



<script>

	//Check whether a section is checked or not
	$(document).ready(function(){
		if ($('#new-order-section').attr('checked')) {

			$('#show-supplier-detail').css('display','block');

			$("#supplierCheckall").on('click', function(){

				$('.supplierCheckbox:checkbox').not(this).prop('checked', this.checked);
			});

				var totalCount = $('.supplierCheckbox').length;
		  		
	  			var totalCheckedCount = $('.supplierCheckbox:checked').length;


		  		if (totalCount == totalCheckedCount) {

		  			$('#supplierCheckall').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#supplierCheckall').prop('checked', false);
		  		}

		  		$('.supplierCheckbox').click(function(){

		  			var totalCount = $('.supplierCheckbox').length;

	  				var totalCheckedCount = $('.supplierCheckbox:checked').length;

		  			if (totalCount == totalCheckedCount) {

			  			$('#supplierCheckall').prop('checked', true);
			  		}
			  		else
			  		{
			  			$('#supplierCheckall').prop('checked', false);
			  		}

			  	});
		}

		if ($('#new-requisition-section').attr('checked')) {
	
			$('#show-requisition-detail').css('display','block');

			$("#memberCheckall").on('click', function(){

				$('.requisitionCheckbox:checkbox').not(this).prop('checked', this.checked);
			});

				var totalCount = $('.requisitionCheckbox').length;
		  		
	  			var totalCheckedCount = $('.requisitionCheckbox:checked').length;


		  		if (totalCount == totalCheckedCount) {

		  			$('#memberCheckall').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#memberCheckall').prop('checked', false);
		  		}

		  		$('.requisitionCheckbox').click(function(){

		  			var totalCount = $('.requisitionCheckbox').length;

	  				var totalCheckedCount = $('.requisitionCheckbox:checked').length;

		  			if (totalCount == totalCheckedCount) {

			  			$('#memberCheckall').prop('checked', true);
			  		}
			  		else
			  		{
			  			$('#memberCheckall').prop('checked', false);
			  		}

			  	});
		}

		if ($('#runningTask-section').attr('checked')) {
			
			$('#show-runningTask-detail').css('display','block');

			var totalCount = $('.runningtask-enable').length;
	  		
  			var totalEnableCheckedCount = $('.runningtask-enable:checked').length;

  			var totalDisableCheckedCount = $('.runningtask-disable:checked').length;


	  		if (totalCount == totalEnableCheckedCount) {

	  			$('#checkallRunningtask').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#checkallRunningtask').prop('checked', false);
	  		}

	  		if (totalCount == totalDisableCheckedCount) {

	  			$('#uncheckallRunningtask').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#uncheckallRunningtask').prop('checked', false);
	  		}

	  		$('.runningtask-enable, .runningtask-disable').click(function(){

	  			var totalCount = $('.runningtask-enable').length;
	  		
  				var totalEnableCheckedCount = $('.runningtask-enable:checked').length;

  				var totalDisableCheckedCount = $('.runningtask-disable:checked').length;

	  			if (totalCount == totalEnableCheckedCount) {

		  			$('#checkallRunningtask').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#checkallRunningtask').prop('checked', false);
		  		}

		  		if (totalCount == totalDisableCheckedCount) {

		  			$('#uncheckallRunningtask').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#uncheckallRunningtask').prop('checked', false);
		  		}

		  	});
		}

		if ($('#history-section').attr('checked')) {
			
			$('#show-history-detail').css('display','block');

			var totalCount = $('.history-enable').length;
	  		
  			var totalEnableCheckedCount = $('.history-enable:checked').length;

  			var totalDisableCheckedCount = $('.history-disable:checked').length;


	  		if (totalCount == totalEnableCheckedCount) {

	  			$('#checkallHistory').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#checkallHistory').prop('checked', false);
	  		}

	  		if (totalCount == totalDisableCheckedCount) {

	  			$('#uncheckallHistory').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#uncheckallHistory').prop('checked', false);
	  		}

	  		$('.history-enable, .history-disable').click(function(){

	  			var totalCount = $('.history-enable').length;
	  		
  				var totalEnableCheckedCount = $('.history-enable:checked').length;

  				var totalDisableCheckedCount = $('.history-disable:checked').length;

	  			if (totalCount == totalEnableCheckedCount) {

		  			$('#checkallHistory').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#checkallHistory').prop('checked', false);
		  		}

		  		if (totalCount == totalDisableCheckedCount) {

		  			$('#uncheckallHistory').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#uncheckallHistory').prop('checked', false);
		  		}

		  	});
		}

		if ($('#stock-section').attr('checked')) {
			
			$('#show-stock-detail').css('display','block');

			var totalCount = $('.stock-enable').length;
	  		
  			var totalEnableCheckedCount = $('.stock-enable:checked').length;

  			var totalDisableCheckedCount = $('.stock-disable:checked').length;


	  		if (totalCount == totalEnableCheckedCount) {

	  			$('#checkallStock').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#checkallStock').prop('checked', false);
	  		}

	  		if (totalCount == totalDisableCheckedCount) {

	  			$('#uncheckallStock').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#uncheckallStock').prop('checked', false);
	  		}

	  		$('.stock-enable, .stock-disable').click(function(){

	  			var totalCount = $('.stock-enable').length;
	  		
  				var totalEnableCheckedCount = $('.stock-enable:checked').length;

  				var totalDisableCheckedCount = $('.stock-disable:checked').length;

	  			if (totalCount == totalEnableCheckedCount) {

		  			$('#checkallStock').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#checkallStock').prop('checked', false);
		  		}

		  		if (totalCount == totalDisableCheckedCount) {

		  			$('#uncheckallStock').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#uncheckallStock').prop('checked', false);
		  		}

		  	});
		}

		if ($('#new-stocktake-section').attr('checked')) {
			
			$('#show-stocktake-detail').css('display','block');

			var totalCount = $('.newStockTake-enable').length;
	  		
  			var totalEnableCheckedCount = $('.newStockTake-enable:checked').length;

  			var totalDisableCheckedCount = $('.newStockTake-disable:checked').length;


	  		if (totalCount == totalEnableCheckedCount) {

	  			$('#checkallNewStockTake').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#checkallNewStockTake').prop('checked', false);
	  		}

	  		if (totalCount == totalDisableCheckedCount) {

	  			$('#uncheckallNewStockTake').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#uncheckallNewStockTake').prop('checked', false);
	  		}

	  		$('.newStockTake-enable, .newStockTake-disable').click(function(){

	  			var totalCount = $('.newStockTake-enable').length;
	  		
  				var totalEnableCheckedCount = $('.newStockTake-enable:checked').length;

  				var totalDisableCheckedCount = $('.newStockTake-disable:checked').length;

	  			if (totalCount == totalEnableCheckedCount) {

		  			$('#checkallNewStockTake').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#checkallNewStockTake').prop('checked', false);
		  		}

		  		if (totalCount == totalDisableCheckedCount) {

		  			$('#uncheckallNewStockTake').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#uncheckallNewStockTake').prop('checked', false);
		  		}

		  	});
		}

		if ($('#revenueCenter-section').attr('checked')) {
			
			$('#show-revenueCenter-detail').css('display','block');

			var totalCount = $('.revenueCenter-enable').length;
	  		
  			var totalEnableCheckedCount = $('.revenueCenter-enable:checked').length;

  			var totalDisableCheckedCount = $('.revenueCenter-disable:checked').length;


	  		if (totalCount == totalEnableCheckedCount) {

	  			$('#checkallRevenueCenter').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#checkallRevenueCenter').prop('checked', false);
	  		}

	  		if (totalCount == totalDisableCheckedCount) {

	  			$('#uncheckallRevenueCenter').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#uncheckallRevenueCenter').prop('checked', false);
	  		}

	  		$('.revenueCenter-enable, .revenueCenter-disable').click(function(){

	  			var totalCount = $('.revenueCenter-enable').length;
	  		
  				var totalEnableCheckedCount = $('.revenueCenter-enable:checked').length;

  				var totalDisableCheckedCount = $('.revenueCenter-disable:checked').length;

	  			if (totalCount == totalEnableCheckedCount) {

		  			$('#checkallRevenueCenter').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#checkallRevenueCenter').prop('checked', false);
		  		}

		  		if (totalCount == totalDisableCheckedCount) {

		  			$('#uncheckallRevenueCenter').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#uncheckallRevenueCenter').prop('checked', false);
		  		}

		  	});
		}

		if ($('#setup-section').attr('checked')) {
			
			$('#show-setup-detail').css('display','block');

			var totalCount = $('.setup-enable').length;
	  		
  			var totalEnableCheckedCount = $('.setup-enable:checked').length;

  			var totalDisableCheckedCount = $('.setup-disable:checked').length;


	  		if (totalCount == totalEnableCheckedCount) {

	  			$('#checkallSetup').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#checkallSetup').prop('checked', false);
	  		}

	  		if (totalCount == totalDisableCheckedCount) {

	  			$('#uncheckallSetup').prop('checked', true);
	  		}
	  		else
	  		{
	  			$('#uncheckallSetup').prop('checked', false);
	  		}

	  		$('.setup-enable, .setup-disable').click(function(){

	  			var totalCount = $('.setup-enable').length;
	  		
  				var totalEnableCheckedCount = $('.setup-enable:checked').length;

  				var totalDisableCheckedCount = $('.setup-disable:checked').length;

	  			if (totalCount == totalEnableCheckedCount) {

		  			$('#checkallSetup').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#checkallSetup').prop('checked', false);
		  		}

		  		if (totalCount == totalDisableCheckedCount) {

		  			$('#uncheckallSetup').prop('checked', true);
		  		}
		  		else
		  		{
		  			$('#uncheckallSetup').prop('checked', false);
		  		}

		  	});
		}
		
		
		
	})
</script>


<script>
  	$('#new-order-section').click(function(){

  		var current_display = $('#show-supplier-detail').css('display');
  		
  		if (current_display == 'none' || '') 
  		{
  			$('#show-supplier-detail').css('display','block');
  			$('#new-order-section').prop('checked', true); 
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
  		}
  		else
  		{
  			$('#show-requisition-detail').css('display','none');
  			$('#new-requisition-section').prop('checked', false);
  		}

  	});
  </script>

  <script>
  	$('#runningTask-section').click(function(){

  		var current_display = $('#show-runningTask-detail').css('display');
  		
  		if (current_display == 'none' || '') 
  		{
  			$('#show-runningTask-detail').css('display','block');
  			$('#runningTask-section').prop('checked', true);
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
  		}
  		else
  		{
  			$('#show-setup-detail').css('display','none');
  			$('#setup-section').prop('checked', false);
  		}

  	});


  	// RunningTask check all/ uncheck all
	$("#checkallRunningtask").on('click', function(){
		
		if($("#uncheckallRunningtask").is(':checked'))
		{
			$('#uncheckallRunningtask').prop('checked', false);
		}

	$('.enableRunningtask input:radio').not(this).prop('checked', this.checked);
	});

	$("#uncheckallRunningtask").on('click', function(){
			
			if($("#checkallRunningtask").is(':checked'))
			{
				$('#checkallRunningtask').prop('checked', false);
			}

		$('.desableRunningtask input:radio').not(this).prop('checked', this.checked);
	});

	//History check all/uncheck all
	$("#checkallHistory").on('click', function(){
			
			if($("#uncheckallHistory").is(':checked'))
			{
				$('#uncheckallHistory').prop('checked', false);
			}

		$('.enableHistory input:radio').not(this).prop('checked', this.checked);
	});

	$("#uncheckallHistory").on('click', function(){
			
			if($("#checkallHistory").is(':checked'))
			{
				$('#checkallHistory').prop('checked', false);
			}

		$('.desableHistory input:radio').not(this).prop('checked', this.checked);
	});

	//Stock check all/uncheck all
	$("#checkallStock").on('click', function(){
			
			if($("#uncheckallStock").is(':checked'))
			{
				$('#uncheckallStock').prop('checked', false);
			}

		$('.enableStock input:radio').not(this).prop('checked', this.checked);
	});

	$("#uncheckallStock").on('click', function(){
			
			if($("#checkallStock").is(':checked'))
			{
				$('#checkallStock').prop('checked', false);
			}

		$('.desableStock input:radio').not(this).prop('checked', this.checked);
	});

	//NewStockTake check all/uncheck all
	$("#checkallNewStockTake").on('click', function(){
			
			if($("#uncheckallNewStockTake").is(':checked'))
			{
				$('#uncheckallNewStockTake').prop('checked', false);
			}

		$('.enableNewStockTake input:radio').not(this).prop('checked', this.checked);
	});

	$("#uncheckallNewStockTake").on('click', function(){
			
			if($("#checkallNewStockTake").is(':checked'))
			{
				$('#checkallNewStockTake').prop('checked', false);
			}

		$('.desableNewStockTake input:radio').not(this).prop('checked', this.checked);
	});

	//RevenueCenter check all/uncheck all
	$("#checkallRevenueCenter").on('click', function(){
			
			if($("#uncheckallRevenueCenter").is(':checked'))
			{
				$('#uncheckallRevenueCenter').prop('checked', false);
			}

		$('.enableRevenueCenter input:radio').not(this).prop('checked', this.checked);
	});

	$("#uncheckallRevenueCenter").on('click', function(){
			
			if($("#checkallRevenueCenter").is(':checked'))
			{
				$('#checkallRevenueCenter').prop('checked', false);
			}

		$('.desableRevenueCenter input:radio').not(this).prop('checked', this.checked);
	});

	//Setup check all/uncheck all
	$("#checkallSetup").on('click', function(){
			
			if($("#uncheckallSetup").is(':checked'))
			{
				$('#uncheckallSetup').prop('checked', false);
			}

		$('.enableSetup input:radio').not(this).prop('checked', this.checked);
	});

	$("#uncheckallSetup").on('click', function(){
			
			if($("#checkallSetup").is(':checked'))
			{
				$('#checkallSetup').prop('checked', false);
			}

		$('.desableSetup input:radio').not(this).prop('checked', this.checked);
	});
  </script>
  
<?php require_once('common/footer.php');?>