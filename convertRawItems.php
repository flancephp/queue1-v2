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
            
                 <input type="hidden" name="barCode" id="barCode" value="<?php echo $_GET['barCode'];?>"/>
                 <input type="hidden" name="returnUrl" value="<?php echo $_GET['returnUrl'];?>"/>
                <div class="modal-body">
                   <select  name="rawItem" id="rawItem" class="form-control" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" onchange="this.setCustomValidity('')" required> 
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
					<div id="rawProId" align="right"></div>
					<div id="convertItemTxt">
					<select  name="convertItem" class="form-control" oninvalid="this.setCustomValidity('<?php echo showOtherLangText('Please select an item in the list.') ?>')" onchange="this.setCustomValidity('')" required>
						<option><?php echo showOtherLangText('Select'); ?></option>
						</select>
					</div>
					<div  id="convrtProId" align="right"></div>
              <input type="text" name="qtyToConvert"  class="form-control" onChange="showUnitPrice()" placeholder="<?php echo showOtherLangText('Quantity to Convert');?>" id="qtyToConvert" />
          <input type="text" name="convertedQty"  class="form-control" id="convertedQty" placeholder="<?php echo showOtherLangText('Converted Quantity');?>" onChange="showUnitPrice()" />
             <input type="text" name="unitPrice" id="unitPrice"  class="form-control" placeholder="<?php echo showOtherLangText('Unit Price'); ?>" />    
                </div>
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
$('#rawItem').change(function(){

	var proId = $('#rawItem').val();
	
	$.ajax({
			  method: "POST",
			  url: "ajax.php",
			  data: { rawItem:proId }
			})
		  .done(function( data ) {
		  
		  		var res = $.parseJSON(data);
				
				$('#convertItemTxt').html(res.selectData);
				$('#rawProId').text(res.stockQty);
				$('#rawStockQty').val( parseFloat(res.stockQty) );
				
			});
});

function showQty()
{

	var proId = parseFloat($('#convertItem').val());
	
	$.ajax({
			  method: "POST",
			  url: "ajax.php",
			  data: { convertItem:proId }
			})
		  .done(function( data ) {
		  				
				$('#convrtProId').text(data);
				$('#convertItemQty').val(data);
			});
}

function showUnitPrice()
{
	var qtyToCont = $('#qtyToConvert').val();
	var qtyCont = $('#convertedQty').val();
	var proId = $('#rawItem').val();
	
	if(qtyToCont > 0 && qtyCont > 0 && proId > 0)
	{
		$.ajax({
				  method: "POST",
				  url: "ajax.php",
				  data: { rawItemId:proId, qtyToCont:qtyToCont, qtyCont:qtyCont }
				})
			  .done(function( retnVal ) {
					$('#unitPrice').val(retnVal);
				});
	}
}
 </script>
