<?php
require_once('common/header.php'); 
include_once('script/addLanguage.php');
checkUesrLogin();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">

            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-language" aria-hidden="true"></i>
                    <span class="panel-span">Add Language</span>
                </h3>
            </div>


            <div class="panelData-Bdy">

                <!-- Show all type of message here -->
                <?php if(isset($_SESSION['warning'])){?>
                <div class="alert alert-warning alert-dismissible"><i class="fa fa-check-circle"></i><strong>Warning!</strong> <?php echo $_SESSION['warning'];?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
                <?php unset($_SESSION['warning']); } ?>

                <div class="table-responsive">
                    <form action="" method="post" name="frm" id="frm">

                        <table class="tb-box table">
                            <tr>
                                <td>Language :</td>
                                <td>
                                    <input type="text" name="language" id="language" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>Language Type :</td>
                                <td>
                                    <input type="radio" name="languageType" id="languageType" value="0" />
                                    <label>Left</label>

                                    &nbsp; &nbsp; &nbsp;

                                    <input type="radio" name="languageType" id="languageType" value="1" />
                                    <label>Right</label>
                                </td>
                            </tr>

                        </table>

                        <!-- start of group button -->
                        <div class="ftBtn-Grp">
                            <button type="submit" class="btn btn-info nglb-Btn">Save</button>
                            <button class="btn btn-info nglb-Btn" type="button"
                                onclick="window.location.href= 'manageLanguage.php' ">Back</button>
                        </div>
                        <!-- end of group butto -->

                    </form>

                </div>
            </div>
        </div>
    </div>


</div>

</div>

</body>

</html>