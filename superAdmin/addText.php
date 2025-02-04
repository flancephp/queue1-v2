<?php
require_once('common/header.php'); 
include_once('script/addText.php');
checkUesrLogin();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-text-width" aria-hidden="true"></i>
                    <span class="panel-span">Add Text</span>
                </h3>
            </div>


            <div class="panelData-Bdy">

                <!-- Show all type of message here -->
                <div class="show-message-box">

                    <?php

          $color = (isset($_GET['error']) && $_GET['error'] > 0)  ? 'color: #FF0000' : 'color: #038B00';

          ?>

                    <h6 style="<?php echo $color?>">
                        <?php 

            echo isset($_GET['error']) ? 'This Main Text already exist' : '';
            ?>
                    </h6>
                </div>
                <div class="table-responsive">
                    <form action="" method="post" name="frm" id="frm">

                        <table class="tb-box table">
                            <tr>
                                <td>Text : </td>
                                <td>
                                    <input type="text" name="main_text" class="form-control">
                                </td>
                            </tr>
                        </table>

                        <!-- start of group button -->
                        <div class="ftBtn-Grp">
                            <button type="submit" class="btn btn-info nglb-Btn">Save</button>
                            <button class="btn btn-info nglb-Btn" type="button"
                                onclick="window.location.href= 'languageSetup.php' ">Back</button>
                        </div>
                        <!-- end of group butto -->

                    </form>

                </div>
            </div>
        </div>


    </div>

</div>

</body>

</html>