<?php
    if($storageStocktakingStep == 1){?>
        <div class="col-md-6 bckClm">
            <!-- <img src="Assets/icons/logo.svg" alt="Logo"> -->
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo showOtherLangText('Storage Stocktaking') ?></h2>
        </div>
        <?php
    }
    else if($storageStocktakingStep == 2){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>storageStocktaking1.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo $storageName;?></h2>
        </div>
        <?php
    }
    else if($storageStocktakingStep == 3){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>storageStocktaking2.php?storageId=<?php echo $_GET['storageId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($storageDeptRow['name']) ? $storageDeptRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
    else if($storageStocktakingStep == 4){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>storageStocktaking3.php?storageId=<?php echo $_GET['storageId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($storageDeptRow['name']) ? $storageDeptRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
    else if($storageStocktakingStep == 5){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>storageStocktaking4.php?storageId=<?php echo $_GET['storageId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($storageDeptRow['name']) ? $storageDeptRow['name'] : ' '. showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
    else if($storageStocktakingStep == 6){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($storageDeptRow['name']) ? $storageDeptRow['name'] : ' '. showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
?>