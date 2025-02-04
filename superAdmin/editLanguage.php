<?php
require_once('common/header.php');
include_once('script/editLanguage.php');
checkUesrLogin();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil" aria-hidden="true"></i>
                    <span class="panel-span">Edit Language</span>
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

          echo isset($_GET['error']) ? 'This language already exist' : '';
          ?>
                    </h6>
                </div>

                <div class="table-responsive" style="padding: 20px 20px;">
                    <form name="frm" id="frm" action="" method="post">
                        <table class="tb-box table">
                            <tr>
                                <td>Language : </td>
                                <td>
                                    <input type="text" name="language" id="language" class="form-control"
                                        value="<?php echo $fetchTableDetails['language_name']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>Language Type : </td>
                                <td>
                                    <input type="radio" name="languageType" id="languageType" value="0"
                                        <?php echo $fetchTableDetails['language_type'] == '0' ? 'checked="checked"' : '' ; ?> />
                                    <label>Left</label>
                                    &nbsp; &nbsp; &nbsp;
                                    <input type="radio" name="languageType" id="languageType" value="1"
                                        <?php echo $fetchTableDetails['language_type'] == '1' ? 'checked="checked"' : '' ; ?> />
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

</body>

</html>