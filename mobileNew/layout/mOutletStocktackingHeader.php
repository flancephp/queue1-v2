<?php
    if($outletStocktackingStep == 1){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo showOtherLangText('Outlet Stocktaking') ?></h2>
        </div>
        <?php
    }
    else if($outletStocktackingStep == 2){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>outletStocktaking1.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo showOtherLangText('Outlet Stocktaking') ?></h2>
        </div>
        <?php
    }
    else if($outletStocktackingStep == 3){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>outletStocktaking2.php?revenueId=<?php echo $_GET['revenueId'];?>&outLetId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ' '.showOtherLangText('Select OutLet').' ';?></h2>
        </div>
        <?php
    }
    else if($outletStocktackingStep == 4){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>outletStocktaking3.php?revenueId=<?php echo $_GET['revenueId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>&start=1" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ''.showOtherLangText('Select Storage').'';?></h2>
        </div>
        <?php
    }
    else if($outletStocktackingStep == 5){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>outletStocktaking2.php?revenueId=<?php echo $_GET['revenueId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>&start=1" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
    else if($outletStocktackingStep == 6){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo isset($stockTakeRow['name']) ? $stockTakeRow['name'] : ' '.showOtherLangText('Select Storage').' ';?></h2>
        </div>
        <?php
    }
?>