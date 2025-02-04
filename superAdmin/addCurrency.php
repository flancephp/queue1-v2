<?php require_once('common/header.php'); 
 checkUesrLogin();
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $data = [];
  $data['currency']  = $_POST['currency'];
  $data['currencyCode']  = $_POST['currencyCode'];
  $data['symbol_left']  = $_POST['symbol_left'];
  $data['symbol_right']  = $_POST['symbol_right'];
  $data['decimal_place']  = $_POST['decimal_place'];
  $data['value']  = $_POST['value'];
  $data['status']  = $_POST['status'];
  addCurrency($data);
}?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-usd" aria-hidden="true"></i>
                    <span class="panel-span">Add Currency</span>
                </h3>
            </div>

            <div class="panelData-Bdy">
                <form id="form-currency" action="addCurrency.php" method="post" enctype="multipart/form-data">
                    <div class="table-responsive">
                        <table class="tb-box table">
                            <tr>
                                <td>Currency Name : </td>
                                <td>
                                    <input type="text" name="currency" id="currency" class="form-control" required>
                                </td>
                            </tr>

                            <tr>
                                <td>Currency Code : </td>
                                <td>
                                    <input type="text" name="currencyCode" id="currencyCode" class="form-control"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td>Symbol Left: </td>
                                <td>
                                    <input type="text" name="symbol_left" id="symbol_left" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>Symbol Right: </td>
                                <td>
                                    <input type="text" name="symbol_right" id="symbol_right" class="form-control">
                                </td>
                            </tr>

                            <tr>
                                <td> Decimal Places: </td>
                                <td>
                                    <input type="text" name="decimal_place" id="decimal_place" class="form-control"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td>Value: </td>
                                <td>
                                    <input type="text" name="value" id="value" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <td>Status: </td>
                                <td>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- start of group button -->
                    <div class="ftBtn-Grp">
                        <button type="submit" class="btn btn-info nglb-Btn">Save</button>
                        <button class="btn btn-info nglb-Btn"
                            onclick="window.location.href= 'manageCurrency.php' ">Back</button>
                    </div>
                    <!-- end of group butto -->
                </form>
            </div>
        </div>


    </div>

</div>

<?php require_once('common/footer.php'); ?>