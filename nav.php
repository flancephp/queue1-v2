<nav class="navbar d-flex flex-wrap align-items-stretch">
    <div>
        <div class="logo">
            <img src="Assets/icons/logo_Q.svg" alt="Logo" class="lg-Img">
            <div class="clsBar" id="clsBar">
                <a href="javascript:void(0)"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="nav-bar">
            <ul class="nav flex-column h2">
                <li class="nav-item dropdown dropend">
                    <a class="nav-link <?php echo isMenuActive(['addOrder.php','addRecusation.php']); ?> d-flex align-items-center text-center dropdown-toggle" aria-current="page" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="d-block w-100"> 
                            <img src="Assets/icons/new_task.svg" alt="Task" class="navIcon">
                            <img src="Assets/icons/new_task_hv.svg" alt="Task" class="mb_navIcn">
                            <p class="mt-1">New Task</p>
                        </span>
                    </a>
                    <ul class="dropdown-menu nwSub-Menu" aria-labelledby="navbarDropdown">
                        <li><a class="nav-link nav_sub <?php echo isSubMenuActive('addOrder.php'); ?>"
                                aria-current="page" href="addOrder.php">
                                <img src="Assets/icons/new_order.svg" alt="New order" class="navIcon align-middle">
                                <img src="Assets/icons/new_order_hv.svg" alt="New order"
                                    class="mb_nvSubIcn align-middle">
                                <span class="align-middle"><?php
                        if ($showOtherLangText = showOtherLangText('New_Order')) 
                        {
                            echo $showOtherLangText;
                        }
                        ?></span>
                            </a>
                        </li>
                        <li><a class="nav-link nav_sub <?php echo isSubMenuActive('addRecusation.php'); ?>"
                                aria-current="page" href="addRecusation.php">
                                <img src="Assets/icons/new_req.svg" alt="Req" class="navIcon align-middle">
                                <img src="Assets/icons/new_req_hv.svg" alt="Req" class="mb_nvSubIcn align-middle">
                                <span class="align-middle"><?php
                        if ($showOtherLangText = showOtherLangText('New_Requisition')) 
                        {
                            echo $showOtherLangText;
                        }
                        ?></span></a>
                        </li>
                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                <img src="Assets/icons/new_stock.svg" alt="Stock" class="navIcon align-middle">
                                <img src="Assets/icons/new_stock_hv.svg" alt="Stock" class="mb_nvSubIcn align-middle">
                                <span class="align-middle">New Stocktake</span></a>
                        </li>
                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                <img src="Assets/icons/new_prod.svg" alt="Product" class="navIcon align-middle">
                                <img src="Assets/icons/new_prod_hv.svg" alt="Product" class="mb_nvSubIcn align-middle">
                                <span class="align-middle">New Production</span></a>
                        </li>
                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                <img src="Assets/icons/new_payment.svg" alt="Payment" class="navIcon align-middle">
                                <img src="Assets/icons/new_payment_hv.svg" alt="Payment"
                                    class="mb_nvSubIcn align-middle">
                                <span class="align-middle">New Payment</span></a>
                        </li>
                        <li><a class="nav-link nav_sub" aria-current="page" href="javascript:void(0)">
                                <img src="Assets/icons/new_invoice.svg" alt="Invoice" class="navIcon align-middle">
                                <img src="Assets/icons/new_invoice_hv.svg" alt="Invoice"
                                    class="mb_nvSubIcn align-middle">
                                <span class="align-middle">New Invoice</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isMenuActive(['runningOrders.php','receiveOrder.php','editRequisition.php','editOrder.php']); ?> d-flex align-items-center text-center" href="runningOrders.php">
                        <span class="d-block w-100"> 
                            <img src="Assets/icons/run_task.svg" alt="Run Task" class="navIcon">
                            <img src="Assets/icons/run_task_hv.svg" alt="Run Task" class="navIcon mb_navIcn">
                            <p class="mt-1">Running Tasks</p>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isMenuActive('history.php'); ?> d-flex align-items-center text-center" href="history.php">
                        <span class="d-block w-100"> 
                            <img src="Assets/icons/office.svg" alt="office" class="navIcon">
                            <img src="Assets/icons/office_hv.svg" alt="office" class="mb_navIcn">
                            <p class="mt-1">Office</p>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isMenuActive('stockView.php'); ?> d-flex align-items-center text-center" href="stockView.php">
                        <span class="d-block w-100"> 
                            <img src="Assets/icons/storage.svg" alt="storage" class="navIcon">
                            <img src="Assets/icons/storage_hv.svg" alt="storage" class="mb_navIcn">
                            <p class="mt-1">Storage</p>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" <?php echo isMenuActive('revenueCenter.php'); ?> href="revenueCenter.php">
                        <span class="d-block w-100"> 
                            <img src="Assets/icons/revenue_center.svg" alt="Revenue" class="navIcon">
                            <img src="Assets/icons/revenue_center_hv.svg" alt="Revenue" class="mb_navIcn">
                            <p class="mt-1">Revenue Centers</p>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="nav-bar lgOut">
        <ul class="nav flex-column h2">
            <li class="nav-item">
                <a class="<?php echo isMenuActive(['setup.php','accountSetup.php','users.php','addUser.php','editUser.php','listDesignation.php','addDesignation.php','editDesignation.php','manageSuppliers.php','addEditSupplier.php','manageOutlets.php','addOutlet.php','editOutlet.php','revenueCenterSetup.php','addRevenueCenter.php','editRevenueCenter.php','itemsManager.php','addProduct.php','editProduct.php','physicalStorages.php','manageDepartments.php','categories.php','subCategories.php','manageUnits.php','manageCurrency.php','addCurrency.php','editCurrency.php','editMainCurrency.php','manageAccounts.php','addAccount.php','editAccount.php','manageServiceFee.php','addServiceFee.php','editServiceFee.php','manageAdditionalFee.php','addAdditionalFee.php','editAdditionalFee.php']); ?> nav-link text-center d-flex align-items-center" href="setup.php">
                    <span class="d-block w-100"> 
                        <img src="Assets/icons/setup.svg" alt="setup" class="navIcon">
                        <img src="Assets/icons/setup_hv.svg" alt="setup" class="mb_navIcn">
                        <p>Setup</p>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-center d-flex align-items-center" href="<?php echo $siteUrl;?>logout.php">
                    <span class="d-block w-100"> 
                        <img src="Assets/icons/logout.svg" alt="logout" class="navIcon">
                        <img src="Assets/icons/logout_hv.svg" alt="logout" class="mb_navIcn">
                        <p><?php
                            if ($showOtherLangText = showOtherLangText('Logout')) 
                            {
                                echo $showOtherLangText;
                            }
                            ?></p>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</nav>