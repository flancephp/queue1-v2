<?php
require_once('common/header.php');
checkUesrLogin(); 
$fetchDetails = fetchTableDetailsWithoutCond('tbl_language');

if (isset($_POST['submitBtn'])) 
{
    //start updation of main language
    foreach ($_POST['main_language'] as $mainLangId => $mainLangVal) 
    {
        $mainTextKey = str_replace(" ","_",$mainLangVal);

        $fieldsArr = 
            [
                'main_text' => $mainLangVal,
                'main_text_key' => $mainTextKey,
                'created_on' => date('Y-m-d h:i:s')
            ];

        $whereArr = 
        [
            'id' => $mainLangId
        ];

        if ($mainLangVal !='') 
        {
            //Fetch data from table if row data exist then update
            $fetchedDataRow = fetchTableDetails('tbl_site_main_text', $whereArr);

            if ($fetchedDataRow > '0') 
            {
                //Update row data
                updateTable('tbl_site_main_text', $fieldsArr, $whereArr);
            }       
        } 
    }//end updation of main language


    //start updation of other language
    foreach ($_POST['other_language'] as $mainLangId => $otherLangArr ) 
    {

        foreach ($otherLangArr as $otherLangId => $otherLangValue)
        {

            $fieldsArr = 
            [
                'site_main_text_id' => $mainLangId,
                'other_language_id' => $otherLangId,
                'other_language_text' => $otherLangValue,
                'created_on' => date('Y-m-d h:i:s')
            ];

            $whereArr = 
            [
                'site_main_text_id' => $mainLangId,
                'other_language_id' => $otherLangId
            ];

            //If input value not blank then only that value will update or insert
            if ($otherLangValue !='') 
            {
                //Fetch data from table if row data exist then update else insert
                $fetchedDataRow = fetchTableDetails('tbl_site_other_language_text', $whereArr);

                if ($fetchedDataRow > 0) 
                {
                    //Update row data
                    updateTable('tbl_site_other_language_text', $fieldsArr, $whereArr);

                   // echo "<script>window.location='languageSetup.php?updateOtherLang=1'</script>";
                }
                else
                {
                    //insert row data for the first time
                    insert('tbl_site_other_language_text', $fieldsArr);

                    //echo "<script>window.location='languageSetup.php?otherLangAdd=1'</script>";
                }        
            }     
        }    
    }

    echo "<script>window.location='languageSetup.php?updateOtherLang=1'</script>";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-lg-2">
            <?php require_once('common/nav.php');?>
        </div>

        <div class="col-md-10 col-sm-9 col-lg-10 content-clm">

            <form name="frm" id="frm" action="" method="post">
                <input type="hidden" name="submitBtn">

                <div class="pull-right">
                    <button type="button" data-toggle="tooltip" title="Add Text" class="btn btn-primary" onclick="window.location.href= 'addText.php' "><i class="fa fa-plus"></i></button>
                    &nbsp;
                    <button type="submit" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    &nbsp;
                    <button type="button" data-toggle="tooltip" title="Back" class="btn btn-default" onclick="window.location.href= 'accountsManager.php' "><i class="fa fa-reply"></i></button>
                </div>


                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-language" aria-hidden="true"></i>
                        <span class="panel-span">Language Setup</span>
                    </h3>
                </div>

                <div class="panelData-Bdy">


                    <?php

                      $color = (isset($_GET['added']) && $_GET['added'] > 0 || isset($_GET['updateOtherLang']) && $_GET['updateOtherLang'] > 0 || isset($_GET['otherLangAdd']) && $_GET['otherLangAdd'] > 0)  ? 'color: #038B00' : 'color: #FF0000';

                      ?>

                    <h6 style="<?php echo $color?>">
                        <?php 
                          echo isset($_GET['added']) ? 'Main Text Added Successfully' : '';
                          echo isset($_GET['updateOtherLang']) ? 'Other Language Updated Successfully' : '';
                          echo isset($_GET['otherLangAdd']) ? 'Other Language Add Successfully' : '';
                        ?>
                    </h6>



                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <tr class="bc-color">
                                <th scope="col">Main Text</th>
                                <?php
             while ($mainLangRow = mysqli_fetch_array($fetchDetails)) 
             {
               ?>
                                <th scope="col"><?php echo $mainLangRow['language_name'] ?></th>
                                <?php
             }
             ?>
                            </tr>

                            <?php
            $fetchTableAllRowData = fetchTableDetailsWithoutCond('tbl_site_main_text');

            $fetchLangTableAllRowData = fetchTableDetailsWithoutCond('tbl_language');

            $langRows = [];
            while ($langRow = mysqli_fetch_array($fetchLangTableAllRowData)) 
            {
              $langRows[] = $langRow;
            }

            while ($mainLangRow = mysqli_fetch_array($fetchTableAllRowData)) 
            {
              ?>

                            <tr>
                                <td><input type="text" name="main_language[<?php echo $mainLangRow['id'] ?>]" value="<?php echo $mainLangRow['main_text'] ?>" /></td>

                                <?php
                foreach($langRows as $otherLangListRow)
                {

                  $whereArr = [
                      'site_main_text_id' => $mainLangRow['id'],
                      'other_language_id' => $otherLangListRow['id']
                  ];
                  //print_r($whereArr);

                  $fetchedDataRow = fetchTableDetails('tbl_site_other_language_text', $whereArr);
                  
                  ?>
                                <td><input type="text"
                                        name="other_language[<?php echo $mainLangRow['id'] ?>][<?php echo $otherLangListRow['id'] ?>]"
                                        value="<?php echo $fetchedDataRow['other_language_text']; ?>" /></td>
                                <?php
                }
                ?>
                            </tr>


                            <?php
            }
            ?>
                        </table>
                    </div>
                </div>

            </form>
        </div>

    </div>


    <?php require_once('common/footer.php'); ?>

    </body>

    </html>