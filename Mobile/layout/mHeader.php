    <div class="row align-items-center">
        <?php
        if ($pgnm == 'Home') { ?>
            <div class="col-md-6">
                <img src="<?php echo $mobileSiteUrl; ?>Assets/icons/logo.svg" alt="Logo">
            </div>
        <?php
        } else if ($pgnm == 'Issue Out') {
            include('layout/mIssueOutHeader.php');
        } else if ($pgnm == 'Receiving') {
            include('layout/mReceivingHeader.php');
        } else if ($pgnm == 'Storage Stocktaking') {
            include('layout/mStorageStocktakingHeader.php');
        } else if ($pgnm == 'Bar Control') {
            include('layout/mBarControlHeader.php');
        } else if ($pgnm == 'Outlet Stocktacking') {
            include('layout/mOutletStocktackingHeader.php');
        }
        ?>
        <div class="col-md-6">
            <div class="mblUsr-clm d-flex align-items-center justify-content-end">
                <div class="usrLg-Col d-flex align-items-center">
                    <div class="dropdown mbl-Out">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-angle-down"></i> <span class="mblFnt1"><?php echo $_SESSION['name']; ?></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $siteUrl; ?>logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <?php
                    $sql = " SELECT * FROM tbl_client WHERE id = '" . $_SESSION['accountId'] . "' ";
                    $sqlQry = mysqli_query($con, $sql);
                    $qryRow = mysqli_fetch_array($sqlQry);
                    $accountName = $qryRow['accountName'];


                    if ($qryRow['logo'] != '' && file_exists("/home/ftpaccess/queue1.com/www/uploads/" . $accountImgPath . '/clientLogo/' . $qryRow['logo'])) {

                        echo '<img src="' . $siteUrl . 'uploads/' . $accountImgPath . '/clientLogo/' . $qryRow['logo'] . '"   class="usrLogo">';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>