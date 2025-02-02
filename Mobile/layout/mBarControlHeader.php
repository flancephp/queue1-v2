<?php
    if($barControlStep == 1){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($outLetDet['name']) ? $outLetDet['name'] : ' '.showOtherLangText('Select OutLet').' ';?></h2>
        </div>
        <?php
    }
    else if($barControlStep == 2){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>barControl1.php?revenueId=<?php echo $_GET['revenueId']; ?>&outLetId=<?php echo $_GET['stockTakeId']; ?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ''.showOtherLangText('Select OutLet').'';?></h2>
        </div>
        <?php
    }
    else if($barControlStep == 3){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>barControl2.php?revenueId=<?php echo $_GET['revenueId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
    else if($barControlStep == 4){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>barControl3.php?stockTakeId=<?php echo $_GET['stockTakeId'];?>&revenueId=<?php echo $_GET['revenueId']?>&start=1" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
    else if($barControlStep == 5){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ''.showOtherLangText('Select Storage').'';?></h2>
        </div>
        <?php
    }
?>