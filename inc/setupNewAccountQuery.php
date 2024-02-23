<?php

// When we create a new account we always have to follow these steps.
----------------------------------------------------------------------


// 1) Add all the details in tbl_client with new accountId.
--------------------------------------------------
// Sample Query to add details in tbl_client
---------------------------------------------------

INSERT INTO `tbl_client` (`id`, `language_id`, `accountNumber`, `name`, `accountName`, `email`, `phone`, `address_one`, `address_two`, `city`, `zipCode`, `country`, `taxId`, `logo`, `created_date`)
 
 VALUES 

 (4, 1, 1004, 'Saleh Diab', 'Hostel Tamra', 'inventory@our-zanzibar-hebrew.com', '+255743419217', 'P.o Box 4146', 'Jambiani(Hebrew)', 'Zanzibar(Hebrew)', 4146, '210', '1001/4567892', '1674141988our zanzibar logo copy2.png', '2023-03-16 08:41:51');





// 2) Add all section permissions detail in tbl_designation_section_permission with new accountId.

--------------------------------------------------
// Sample Query to add details in tbl_designation_section_permission
---------------------------------------------------

INSERT INTO `tbl_designation_section_permission` (`designation_id`, `designation_section_list_id`, `account_id`) VALUES
(1, 1, 4),
(1, 2, 4),
(1, 3, 4),
(1, 4, 4),
(1, 5, 4),
(1, 6, 4),
(1, 7, 4),
(1, 8, 4);


// 3) Add all sub section permissions detail in tbl_designation_sub_section_permission with new accountId.

--------------------------------------------------
// Sample Query to add details in tbl_designation_sub_section_permission
---------------------------------------------------

INSERT INTO `tbl_designation_sub_section_permission` (`designation_id`, `designation_Section_permission_id`, `type`, `type_id`, `account_id`) VALUES

(21,	1,	'order_supplier',	5,	4),
(21,	1,	'order_supplier',	9,	4),
(21,	1,	'order_supplier',	10,	4),
(21,	1,	'order_supplier',	11,	4),
(21,	1,	'order_supplier',	12,	4),
(21,	1,	'order_supplier',	14,	4),
(21,	1,	'order_supplier',	15,	4),
(21,	1,	'order_supplier',	16,	4),
(21,	1,	'order_supplier',	17,	4),
(21,	1,	'order_supplier',	18,	4),
(21,	1,	'order_supplier',	19,	4),
(21,	1,	'order_supplier',	20,	4),
(21,	1,	'order_supplier',	21,	4),
(21,	1,	'order_supplier',	22,	4),
(21,	1,	'order_supplier',	23,	4),
(21,	1,	'order_supplier',	24,	4),
(21,	1,	'order_supplier',	25,	4),
(21,	1,	'order_supplier',	26,	4),
(21,	1,	'order_supplier',	27,	4),
(21,	1,	'order_supplier',	28,	4),
(21,	1,	'order_supplier',	29,	4),
(21,	1,	'order_supplier',	30,	4),
(21,	1,	'order_supplier',	31,	4),
(21,	1,	'order_supplier',	32,	4),
(21,	1,	'order_supplier',	33,	4),
(21,	1,	'order_supplier',	34,	4),
(21,	1,	'order_supplier',	35,	4),
(21,	1,	'order_supplier',	36,	4),
(21,	1,	'order_supplier',	37,	4),
(21,	1,	'order_supplier',	38,	4),
(21,	1,	'order_supplier',	39,	4),
(21,	1,	'order_supplier',	187,	4),
(21,	1,	'order_supplier',	193,	4),
(21,	1,	'order_supplier',	194,	4),
(21,	1,	'order_supplier',	197,	4),
(21,	1,	'order_supplier',	198,	4),
(21,	1,	'order_supplier',	199,	4),
(21,	1,	'order_supplier',	200,	4),
(21,	1,	'access_payment',	1,	4),
(21,	2,	'member',	4,	4),
(21,	2,	'member',	5,	4),
(21,	2,	'member',	7,	4),
(21,	2,	'member',	8,	4),
(21,	2,	'member',	9,	4),
(21,	2,	'member',	10,	4),
(21,	2,	'member',	11,	4),
(21,	2,	'member',	13,	4),
(21,	2,	'member',	14,	4),
(21,	2,	'member',	15,	4),
(21,	2,	'member',	16,	4),
(21,	2,	'member',	17,	4),
(21,	2,	'member',	18,	4),
(21,	2,	'member',	19,	4),
(21,	2,	'member',	20,	4),
(21,	2,	'member',	21,	4),
(21,	2,	'member',	22,	4),
(21,	2,	'member',	23,	4),
(21,	2,	'member',	24,	4),
(21,	2,	'member',	25,	4),
(21,	2,	'member',	26,	4),
(21,	2,	'member',	27,	4),
(21,	2,	'member',	28,	4),
(21,	2,	'member',	29,	4),
(21,	2,	'member',	30,	4),
(21,	2,	'member',	31,	4),
(21,	2,	'member',	475,	4),
(21,	2,	'member',	476,	4),
(21,	2,	'member',	477,	4),
(21,	2,	'member',	481,	4),
(21,	2,	'access_invoice',	1,	4),
(21,	3,	'edit_order',	1,	4),
(21,	3,	'edit_requisition',	1,	4),
(21,	3,	'receive_order',	1,	4),
(21,	3,	'issue_out',	1,	4),
(21,	3,	'assign_mobile',	1,	4),
(21,	3,	'access_delete_runningtask',	1,	4),
(21,	4,	'access_history_xcl_pdf',	1,	4),
(21,	4,	'access_delete_history',	1,	4),
(21,	5,	'stock',	5,	4),
(21,	5,	'stock',	10,	4),
(21,	5,	'stock',	11,	4),
(21,	5,	'stock',	12,	4),
(21,	5,	'stock',	13,	4),
(21,	5,	'stock',	14,	4),
(21,	5,	'stock',	15,	4),
(21,	5,	'convert_raw_items',	1,	4),
(21,	5,	'view_stock_take',	1,	4),
(21,	5,	'import_stock_take',	1,	4),
(21,	5,	'access_stock_xcl_pdf',	1,	4),
(21,	6,	'edit_stocktake',	1,	4),
(21,	6,	'delete_stocktake',	1,	4),
(21,	7,	'guest_no',	1,	4),
(21,	7,	'ezee_data',	1,	4),
(21,	7,	'reImport_data',	1,	4),
(21,	8,	'account_setup',	1,	4),
(21,	8,	'designation',	1,	4),
(21,	8,	'user',	1,	4),
(21,	8,	'outlet',	1,	4),
(21,	8,	'supplier',	1,	4),
(21,	8,	'revenue_center',	1,	4),
(21,	8,	'physical_storage',	1,	4),
(21,	8,	'department_type',	1,	4),
(21,	8,	'category',	1,	4),
(21,	8,	'unit',	1,	4),
(21,	8,	'item_manager',	1,	4),
(21,	8,	'currency',	1,	4),
(21,	8,	'account',	1,	4),
(21,	8,	'service_item',	1,	4),
(21,	8,	'additional_fee',	1,	4);