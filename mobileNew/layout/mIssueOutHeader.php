<?php
    if($issueOutStep == 1){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo showOtherLangText('Issue Out') ?></h2>
        </div>
        <?php
    }
    else if($issueOutStep == 2){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>issueOut1.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo $mebRes['name'];?></h2>
        </div>
        <?php
    }
    else if($issueOutStep == 3){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>issueOut2.php?assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo $mebRes['name'];?></h2>
        </div>
        <?php
    }
    else if($issueOutStep == 4){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>issueOut3.php?assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo $mebRes['name'];?></h2>
        </div>
        <?php
    }
    else if($issueOutStep == 5){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>issueOut4.php?assignId=<?php echo $_GET['assignId'];?>&stockTakeId=<?php echo $_GET['stockTakeId'];?>" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo $mebRes['name'];?></h2>
        </div>
        <?php
    }
    else if($issueOutStep == 6){?>
        <div class="col-md-6 bckClm">
            <a href="<?php echo $mobileSiteUrl;?>index.php" class="mblBack-Btn"><i class="fa-solid fa-chevron-left"></i></a>
            <h2 class="mblFnt2"><?php echo $mebRes['name'];?></h2>
        </div>
        <?php
    }
?>