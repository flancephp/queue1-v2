<?php include('inc/dbConfig.php'); //connection details

//Update for new items
if (isset($_POST['productIds'])) {

        $selectAtLeastOneItem = false;
        foreach ($_POST['qty'] as $qty) {

                if ($qty > 0) {
                        $selectAtLeastOneItem = true;
                }
        }

        if ($selectAtLeastOneItem === false) {
                echo '<script>window.location="editOrder.php?orderId=' . $_GET['orderId'] . '&selectAtleastOneProduct=1"</script>';

                exit;
        }

        $sql = "UPDATE  `tbl_order_details_temp` SET
                                           
                                            `editOrdNewItemStatus` = 0
                                           

                                           where 
                                           `ordId` = '" . $_GET['orderId'] . "'
                                           AND`account_id` = '" . $_SESSION['accountId'] . "'  ";

        mysqli_query($con, $sql); //die($sql);


        echo '<script>window.location="editOrder.php?orderId=' . $_GET['orderId'] . '&newItemsAdded=1"</script>';
        exit;
} //End if  
