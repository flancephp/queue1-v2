<?php
    if($receivingStep == 1){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo showOtherLangText('Receiving') ?></h2>
        </div>
        <?php
    }
    else if($receivingStep == 2){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>receiveOrder1.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"># <?php echo $orderDet['ordNumber'];?></h2>
        </div>
        <?php
    }
    else if($receivingStep == 3){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>receiveOrder2.php?start=1&assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"># <?php echo $orderDet['ordNumber'];?></h2>
        </div>
        <?php
    }
    else if($receivingStep == 4){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>receiveOrder3.php?assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"># <?php echo $orderDet['ordNumber'];?></h2>
        </div>
        <?php
    }
    else if($receivingStep == 5){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>receiveOrder4.php?assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"># <?php echo $orderDet['ordNumber'];?></h2>
        </div>
        <?php
    }
    else if($receivingStep == 6){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"># <?php echo $orderDet['ordNumber'];?></h2>
        </div>
        <?php
    }
?>