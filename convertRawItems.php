<?php 

include('inc/dbConfig.php'); //connection details

//Get language Type 
$getLangType = getLangType($_SESSION['language_id']);

?>
<div class="modal-header">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h1 class="modal-title h1"><?php echo showOtherLangText('Qty in stock'); ?></h1>
</div>

<form method="post" id="stocksubmit" action="stockView.php" autocomplete="off">

    <input type="hidden" name="barCode" id="barCode" value="<?php echo $_GET['barCode'];?>" />
    <input type="hidden" name="returnUrl" value="<?php echo $_GET['returnUrl'];?>" />
    <div class="modal-body d-flex flex-column gap-3">
		<div class="d-flex align-items-center">
			<div class="col">
				<select name="rawItem" id="rawItem" class="form-select mt-0 w-100"
					oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
					onchange="this.setCustomValidity('')" required>
					<option value=""><?php echo showOtherLangText('Select Raw item'); ?></option>
					<?php 
						$rawProducts = getAllProducts(['proType' => 3]);
						if( !empty($rawProducts) )
						{
							foreach($rawProducts as $pId=>$itemName)
							{
								echo '<option value="'.$pId.'">'.$itemName.'</option>';
							}
						}
					?>
				</select> 
			</div>
			<div style="width:6rem;" class="ps-4"> 
				<div id="rawProId"></div>
			</div>
		</div>
		<div class="d-flex align-items-center">
			<div class="col">
				<div id="convertItemTxt">
					<select name="convertItem" class="form-select mt-0 w-100"
						oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')"
						onchange="this.setCustomValidity('')" required>
						<option><?php echo showOtherLangText('Select'); ?></option>
					</select>
				</div> 
			</div>
			<div style="width:6rem;" class="ps-4"> 
				<div id="convrtProId"></div>
			</div>
		</div> 
        <div class="d-flex align-items-center"> 
			<div class="col"> 
				<input 
					type="text" name="qtyToConvert" class="form-control mt-0 w-100" onChange="showUnitPrice()"
					placeholder="<?php echo showOtherLangText('Quantity to Convert');?>" id="qtyToConvert" 
				/>
			</div>
			<div style="width:6rem;"></div>
		</div>
		<div class="d-flex align-items-center"> 
			<div class="col"> 
				<input 
					type="text" name="convertedQty" class="form-control mt-0 w-100" id="convertedQty"
					placeholder="<?php echo showOtherLangText('Converted Quantity');?>" onChange="showUnitPrice()" 
				/>
			</div>
			<div style="width:6rem;" class="ps-4"></div>
		</div>
		<div class="d-flex align-items-center"> 
			<div class="col"> 
				<input 
					type="text" name="unitPrice" id="unitPrice" class="form-control mt-0 w-100"
					placeholder="<?php echo showOtherLangText('Unit Price'); ?>" 
				/>
			</div>
			<div style="width:6rem;"></div>
		</div>
    </div><!--./modal-body -->
    <div class="modal-footer">
        <div class="btnBg">
            <button type="submit" name="btnSbt" onclick="" class="deletelink btn sub-btn std-btn"><?php echo showOtherLangText('Save'); ?></button>
        </div>
        <div class="btnBg">
            <button type="button" data-bs-dismiss="modal" class="btn sub-btn std-btn"><?php echo showOtherLangText('Cancel'); ?></button>
        </div>
    </div>
</form>
<script>
$('#rawItem').change(function() {

    var proId = $('#rawItem').val();

    $.ajax({
            method: "POST",
            url: "ajax.php",
            data: {
                rawItem: proId
            }
        })
        .done(function(data) {

            var res = $.parseJSON(data);

            $('#convertItemTxt').html(res.selectData);
            $('#rawProId').text(res.stockQty);
            $('#rawStockQty').val(parseFloat(res.stockQty));

        });
});

function showQty() {

    var proId = parseFloat($('#convertItem').val());

    $.ajax({
            method: "POST",
            url: "ajax.php",
            data: {
                convertItem: proId
            }
        })
        .done(function(data) {

            $('#convrtProId').text(data);
            $('#convertItemQty').val(data);
        });
}

function showUnitPrice() {
    var qtyToCont = $('#qtyToConvert').val();
    var qtyCont = $('#convertedQty').val();
    var proId = $('#rawItem').val();

    if (qtyToCont > 0 && qtyCont > 0 && proId > 0) {
        $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    rawItemId: proId,
                    qtyToCont: qtyToCont,
                    qtyCont: qtyCont
                }
            })
            .done(function(retnVal) {
                $('#unitPrice').val(retnVal);
            });
    }
}
</script>