<?php

25/07/2022 for offer module
1. C:\xampp\htdocs\pristine-andaman\routes\web.php
2. C:\xampp\htdocs\pristine-andaman\app\Http\Controllers\OfferController.php
3. C:\xampp\htdocs\pristine-andaman\app\Models\Offer.php
4. C:\xampp\htdocs\pristine-andaman\resources\views\offers
5. C:\xampp\htdocs\pristine-andaman\resources\views\layouts\app.blade.php

26/07/2022 for testimonial module
1. C:\xampp\htdocs\pristine-andaman\routes\web.php
2. C:\xampp\htdocs\pristine-andaman\app\Http\Controllers\TestimonialController.php
3. C:\xampp\htdocs\pristine-andaman\app\Models\testimonial.php
4. C:\xampp\htdocs\pristine-andaman\resources\views\testimonial
5. C:\xampp\htdocs\pristine-andaman\resources\views\layouts\app.blade.php

26/07/2022 for blog module
1. C:\xampp\htdocs\pristine-andaman\routes\web.php
2. C:\xampp\htdocs\pristine-andaman\app\Http\Controllers\BlogController.php
3. C:\xampp\htdocs\pristine-andaman\app\Models\blog.php
4. C:\xampp\htdocs\pristine-andaman\resources\views\blog
5. C:\xampp\htdocs\pristine-andaman\resources\views\layouts\app.blade.php

27/07/2022 for frontend contact us
1. C:\xampp\htdocs\pristine-andaman\routes\web.php
C:\xampp\htdocs\pristine-andaman\app\Http\Controllers\Frontend\ContactusController.php
C:\xampp\htdocs\pristine-andaman\resources\views\frontend\contact.blade.php
C:\xampp\htdocs\pristine-andaman\app\Models\Contactus.php

27/07/2022 for contact module
1. C:\xampp\htdocs\pristine-andaman\routes\web.php
2. C:\xampp\htdocs\pristine-andaman\app\Http\Controllers\ContactController.php
3. C:\xampp\htdocs\pristine-andaman\app\Models\contact.php
4. C:\xampp\htdocs\pristine-andaman\resources\views\contacts
5. C:\xampp\htdocs\pristine-andaman\resources\views\layouts\app.blade.php





//// 02/08/2022
ALTER TABLE `package_itinerary` ADD `night_stay` ENUM('Y','N') NULL AFTER `description`, ADD `transport` ENUM('Y','N') NULL AFTER `night_stay`, ADD `activity` ENUM('Y','N') NULL AFTER `transport`, ADD `ferry_count` ENUM('Y','N') NULL AFTER `activity`;

//// 08/08/2022
ALTER TABLE `package_description` ADD `actual_price` DECIMAL(10,2) NULL AFTER `map_plan_price`;
ALTER TABLE `package_itinerary` ADD `header` VARCHAR(255) NULL AFTER `package_day`;

//// 09/08/2022
ALTER TABLE `packages` ADD `night_stay` ENUM('Y','N') NULL AFTER `pkg_duration`, ADD `transport` ENUM('Y','N') NULL AFTER `night_stay`, ADD `activity` ENUM('Y','N') NULL AFTER `transport`, ADD `ferry_count` ENUM('Y','N') NULL AFTER `activity`;

// 10/08/2022
ALTER TABLE `users` ADD `is_deleted` ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER `updated_at`;


ALTER TABLE `users` ADD `related_module` VARCHAR(255) NULL AFTER `remember_token`, ADD `module_id` INT(11) NULL AFTER `related_module`;
ALTER TABLE `users` CHANGE `related_module` `related_module` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'asp,courier,customer';
ALTER TABLE `courier` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `requsition` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default,1=closed,2=done';
ALTER TABLE `ticket_assign` ADD `tracking_id` VARCHAR(255) NULL AFTER `assign_type`;
//06/01/2022
ALTER TABLE `asset_plan` CHANGE `no_page` `no_page` INT(11) NULL;
ALTER TABLE `asset_plan` CHANGE `cost_page` `cost_page` FLOAT(10,2) NULL;
ALTER TABLE `asset_plan` CHANGE `rental` `rental` FLOAT(10,2) NULL;
ALTER TABLE `asset_plan` CHANGE `start_date` `start_date` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `item_master` ADD `umo` VARCHAR(255) NULL AFTER `item_type`;
ALTER TABLE `item_master` CHANGE `umo` `uom` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
//// 09.01.2022

ALTER TABLE `bank_branch` CHANGE `location` `city` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `bank_branch` ADD `state` VARCHAR(255) NULL AFTER `city`, ADD `pincode` VARCHAR(255) NULL AFTER `state`;
ALTER TABLE `bank_branch` ADD `manager_name` VARCHAR(255) NULL AFTER `bank_id`, ADD `contact_no2` VARCHAR(255) NULL AFTER `manager_name`;
ALTER TABLE `asset_plan` ADD `installation_date` TIMESTAMP NULL AFTER `rental`;
ALTER TABLE `asset_plan` CHANGE `plan` `plan` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=Rental,2=Cumulative,3=Maintenance';

////12.01.2022
ALTER TABLE `upload_status` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=pending,1=uploaded,2=Approved,3=Rejected';

///////13/01/2022
ALTER TABLE `upload_status_file` ADD `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default,2=approved,3=rejected' AFTER `updated_by`;

////16/01.2022
ALTER TABLE `item_master` ADD `toner` TINYINT NOT NULL DEFAULT '0' COMMENT '1=selected' AFTER `uom`, ADD `ink` TINYINT NOT NULL DEFAULT '0' COMMENT '1=selected' AFTER `toner`, ADD `maintenance_box` TINYINT NOT NULL DEFAULT '0' COMMENT '1=selected' AFTER `ink`, ADD `drum` TINYINT NOT NULL DEFAULT '0' COMMENT '1=selected' AFTER `maintenance_box`;


/////// 17.01.2022

ALTER TABLE `ticket_assign` ADD `service_engineer` VARCHAR(255) NULL AFTER `tracking_id`, ADD `engineer_assign_date` TIMESTAMP NULL AFTER `service_engineer`, ADD `comment` TEXT NULL AFTER `engineer_assign_date`, ADD `part_details` TEXT NULL AFTER `comment`;


////////// 19/01/2023

ALTER TABLE `ticket_assign` ADD `file_name` VARCHAR(255) NULL AFTER `part_details`, ADD `file_extension` VARCHAR(255) NULL AFTER `file_name`;

////// 20/01/2022

ALTER TABLE `item_master` ADD `printer_make` VARCHAR(255) NULL AFTER `item_type`, ADD `printer_model` VARCHAR(255) NULL AFTER `printer_make`, ADD `cartridge_model` VARCHAR(255) NULL AFTER `printer_model`;


///////23.01.2022

ALTER TABLE `bank_branch` ADD `email2` VARCHAR(255) NULL AFTER `email`;

CREATE TABLE `vendor_documents` (
    `id` int(11) NOT NULL,
    `vendor_id` int(11) NOT NULL,
    `doc_type` varchar(255) NOT NULL,
    `file_name` varchar(255) NOT NULL,
    `file_extension` varchar(25) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `created_by` int(11) NOT NULL,
    `updated_by` int(11) DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=default,1=deleted'
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  ALTER TABLE `vendor_documents` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
  ALTER TABLE `bank_branch` ADD `share_id` VARCHAR(255) NOT NULL AFTER `pincode`;



  ////////30.01.2023

  ALTER TABLE `item_master` ADD `cartridge_model_unit` VARCHAR(255) NULL AFTER `cartridge_model`, ADD `drum_model` VARCHAR(255) NULL AFTER `cartridge_model_unit`, ADD `drum_model_unit` VARCHAR(255) NULL AFTER `drum_model`, ADD `ink_model` VARCHAR(255) NULL AFTER `drum_model_unit`, ADD `ink_model_unit` VARCHAR(255) NULL AFTER `ink_model`, ADD `mbox_model` VARCHAR(255) NULL AFTER `ink_model_unit`, ADD `mbox_model_unit` VARCHAR(255) NULL AFTER `mbox_model`, ADD `spare_part` VARCHAR(255) NULL AFTER `mbox_model_unit`, ADD `part_code` VARCHAR(255) NULL AFTER `spare_part`, ADD `spare_part_unit` VARCHAR(255) NULL AFTER `part_code`;

  item_spare_parts table

  ALTER TABLE `item_master`
  DROP `spare_part`,
  DROP `part_code`,
  DROP `spare_part_unit`;



  //////////
  08.02.2023

  1. Asset plan table model field delete needed


  //////////////13.02.2023

  ALTER TABLE `purchase_invoice` ADD `printer_make_id` INT(11) NULL AFTER `invoice_no`, ADD `printer_model_id` INT(11) NULL AFTER `printer_make_id`;
  ALTER TABLE `purchase_invoice` ADD `reference_no` VARCHAR(255) NULL AFTER `total_amount`, ADD `file_name` VARCHAR(255) NULL AFTER `reference_no`, ADD `file_extension` VARCHAR(255) NULL AFTER `file_name`;


  ALTER TABLE `purchase_invoice`
  DROP `printer_make_id`,
  DROP `printer_model_id`,
  DROP `item_name`,
  DROP `item_code`,
  DROP `umo`,
  DROP `quantity`,
  DROP `rate`,
  DROP `total_amount`;

  purchase invoice asset table
  
  //////// 15.02.2023
  
  
  ALTER TABLE `purchase_invoice_asset` CHANGE `printer_model_id` `printer_model_id` INT(11) NOT NULL COMMENT 'item master table id';


  ///////16.02.2023

  ALTER TABLE `users` CHANGE `related_module` `related_module` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'asp,courier,customer,rit,bank';

  /////// 17.02.2023

  ALTER TABLE `bank` ADD `email` VARCHAR(255) NULL AFTER `description`;

//////////////////////////////////////  chanhes on live ///////////////////////////////////////////////////////////////////////////////////////////////



////// 22.02.2023

ALTER TABLE `upload_status_file` ADD `previous_date` TIMESTAMP NULL AFTER `asset_plan_id`, ADD `previous_page_count` INT(11) NULL AFTER `previous_date`;
ALTER TABLE `upload_status_file` ADD `invoice_no` VARCHAR(255) NULL AFTER `upload_date`, ADD `invoice_date` TINYINT NULL AFTER `invoice_no`;
ALTER TABLE `upload_status_file` ADD `price_with_gst` FLOAT(10,2) NOT NULL AFTER `invoice_date`, ADD `total_price` FLOAT(10,2) NOT NULL AFTER `price_with_gst`;


/////// 24.02.2023

ALTER TABLE `ticket_remarks` ADD `dispatch_date` TIMESTAMP NULL AFTER `drum`, ADD `dispatch_status` TINYINT(1) NOT NULL DEFAULT '0' AFTER `dispatch_date`, ADD `plan` TINYINT(1) NULL COMMENT '1=Rental,2=Cumulative,3=Maintenance' AFTER `dispatch_status`, ADD `cost_page` FLOAT(10,2) NULL AFTER `plan`, ADD `no_free_page` INT(11) NULL AFTER `cost_page`, ADD `rental` FLOAT(10,2) NULL AFTER `no_free_page`;

/////////  28.02.2023

ALTER TABLE `upload_status` ADD `is_share_link` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'upload from share link' AFTER `bank_branch_id`;


////// 02.03.2023

ALTER TABLE `ticket_remarks`
  DROP `dispatch_date`,
  DROP `dispatch_status`;


  /////// 03.03.2023
  ALTER TABLE `requsition` ADD `status_update_date` TIMESTAMP NULL AFTER `updated_by`;
  ALTER TABLE `requsition` ADD `reopen_parent_id` INT(11) NULL AFTER `ticket_no`, ADD `reopen_reason` VARCHAR(255) NULL AFTER `reopen_parent_id`

  /////////////10.03.2023
  ALTER TABLE `requsition` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default/pending/new ticket,1=closed,2=done,3=work in progress, 4=Intransit,';

  ALTER TABLE `asset_plan` ADD `po_no` VARCHAR(255) NULL AFTER `sl_no`;

  //////// 13.03.2023

  ALTER TABLE `bank_branch` ADD `branch_division` VARCHAR(255) NULL AFTER `email2`;


  ////// 15.03.2023

  CREATE TABLE `asset_plan_old_data` (
    `id` int(11) NOT NULL,
    `old_id` int(11) NOT NULL,
    `customer_id` int(11) NOT NULL,
    `item_master_id` int(11) NOT NULL,
    `model` varchar(255) DEFAULT NULL,
    `sl_no` varchar(255) DEFAULT NULL,
    `po_no` varchar(255) DEFAULT NULL,
    `plan` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Rental,2=Cumulative,3=Maintenance',
    `no_page` int(11) DEFAULT NULL,
    `cost_page` float(10,2) DEFAULT NULL,
    `no_free_page` int(11) DEFAULT NULL,
    `rental` float(10,2) DEFAULT NULL,
    `installation_date` timestamp NULL DEFAULT NULL,
    `start_date` timestamp NULL DEFAULT NULL,
    `end_date` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `created_by` int(11) NOT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `updated_by` int(11) DEFAULT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 0,
    `deleted` tinyint(1) NOT NULL DEFAULT 0
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ALTER TABLE `asset_plan_old_data`
    ADD PRIMARY KEY (`id`);
    ALTER TABLE `asset_plan_old_data`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1406;

    ALTER TABLE `asset_plan_old_data` ADD `date_of_update` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `old_id`;

    ////////////////////////////////////////////////////////////////////////////////
    DELIMITER $$

    CREATE TRIGGER before_update_assetplan  
    AFTER UPDATE  
    ON asset_plan FOR EACH ROW  
    BEGIN  
    IF (OLD.item_master_id <> new.item_master_id) OR (OLD.sl_no <> new.sl_no) OR (OLD.po_no <> new.po_no) OR (OLD.plan <> new.plan) OR (OLD.no_page <> new.no_page) OR (OLD.cost_page <> new.cost_page) OR (OLD.no_free_page <> new.no_free_page) OR (OLD.rental <> new.rental) OR (OLD.installation_date <> new.installation_date) OR (OLD.start_date <> new.start_date) OR (OLD.end_date <> new.end_date) OR (OLD.status <> new.status) OR (OLD.deleted <> new.deleted) 
    THEN
        INSERT into asset_plan_old_data VALUES ('', OLD.id,current_timestamp(),OLD.customer_id,OLD.item_master_id,OLD.model,OLD.sl_no,OLD.po_no,OLD.plan,OLD.no_page,OLD.cost_page,OLD.no_free_page,OLD.rental,OLD.installation_date,OLD.start_date,OLD.end_date,OLD.created_at,OLD.created_by,OLD.updated_at,OLD.updated_by,OLD.status,OLD.deleted);  
    END IF;
    END $$  

    DELIMITER ;
///asset_plan_AFTER_UPDATE for live
//////////////////////////////////////////////
DROP TRIGGER before_update_assetplan;

/////////////////// workbench live 
DROP TRIGGER IF EXISTS `yashujee_live`.`asset_plan_AFTER_UPDATE`;

DELIMITER $$
USE `yashujee_live`$$
CREATE DEFINER = CURRENT_USER TRIGGER `yashujee_live`.`asset_plan_AFTER_UPDATE` AFTER UPDATE ON `asset_plan` FOR EACH ROW
BEGIN
IF (OLD.item_master_id <> new.item_master_id) OR (OLD.sl_no <> new.sl_no) OR (OLD.po_no <> new.po_no) OR (OLD.plan <> new.plan) OR (OLD.no_page <> new.no_page) OR (OLD.cost_page <> new.cost_page) OR (OLD.no_free_page <> new.no_free_page) OR (OLD.rental <> new.rental) OR (OLD.installation_date <> new.installation_date) OR (OLD.start_date <> new.start_date) OR (OLD.end_date <> new.end_date) OR (OLD.status <> new.status) OR (OLD.deleted <> new.deleted) 
    THEN
        INSERT into asset_plan_old_data VALUES ('', OLD.id,current_timestamp(),OLD.customer_id,OLD.item_master_id,OLD.model,OLD.sl_no,OLD.po_no,OLD.plan,OLD.no_page,OLD.cost_page,OLD.no_free_page,OLD.rental,OLD.installation_date,OLD.start_date,OLD.end_date,OLD.created_at,OLD.created_by,OLD.updated_at,OLD.updated_by,OLD.status,OLD.deleted);  
    END IF;
END
$$
DELIMITER ;


////////////////////
ALTER TABLE `asset_plan` ADD `is_import` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=import' AFTER `updated_by`;
ALTER TABLE `bank_branch` ADD `is_import` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=import' AFTER `updated_by`;
ALTER TABLE `item_master` ADD `is_import` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=import' AFTER `updated_by`;
ALTER TABLE `printer_make` ADD `is_import` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=import' AFTER `created_by`;
ALTER TABLE `rit_master` ADD `is_import` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=import' AFTER `updated_by`;



/////////16.03.2023
ALTER TABLE `ticket_assign` CHANGE `dispatch_status` `dispatch_status` TINYINT(1) NULL DEFAULT NULL COMMENT '0=default , 1=pending , 2=done';
ALTER TABLE `ticket_assign` ADD `is_rto` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'only for courier assign\r\n0=default,1-yes' AFTER `assign_type`;

///////////17.03.2023

ALTER TABLE `item_master` ADD `hsn_code` VARCHAR(255) NOT NULL AFTER `item_id`;

/////18.03.2023
ALTER TABLE `requsition` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default/pending/new ticket,1=closed,2=done,3=work in progress, 4=Intransit,5=part required';
ALTER TABLE `requsition` ADD `part_required_details` VARCHAR(255) NULL AFTER `response_date`;

/////20.03.2023
ALTER TABLE `rit_master` ADD `regional_address` TEXT NULL AFTER `state`;

ALTER TABLE `item_master` ADD `cartridge_model_hsn` VARCHAR(255) NULL AFTER `cartridge_model`;
ALTER TABLE `item_master` ADD `drum_model_hsn` VARCHAR(255) NULL AFTER `drum_model`;
ALTER TABLE `item_master` ADD `ink_model_hsn` VARCHAR(255) NULL AFTER `ink_model`;
ALTER TABLE `item_master` ADD `mbox_model_hsn` VARCHAR(255) NULL AFTER `mbox_model`;

ALTER TABLE `item_spare_parts` ADD `hsn` VARCHAR(255) NULL AFTER `spare_part`;

ticket_required_part table

ALTER TABLE `sales_order` ADD `contact_no` VARCHAR(255) NULL AFTER `sales_date`, ADD `contact_no2` VARCHAR(255) NULL AFTER `contact_no`, ADD `email` VARCHAR(255) NULL AFTER `contact_no2`, ADD `yashujee_address` TEXT NULL AFTER `email`, ADD `regional_address` TEXT NULL AFTER `yashujee_address`, ADD `branch_address` TEXT NULL AFTER `regional_address`;

ALTER TABLE `sales_order_asset` ADD `product_type` VARCHAR(255) NULL COMMENT 'cartridge,ink,drum,mbox,sparepart' AFTER `order_no`, ADD `product_model` VARCHAR(255) NULL AFTER `product_type`;

ALTER TABLE `sales_order_asset` ADD `item_id` INT(11) NULL AFTER `sales_order_id`;



///////// 21.03.2023

ALTER TABLE `sales_order_asset` CHANGE `item_name` `printer_make` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `sales_order_asset` CHANGE `umo` `printer_model` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE `sales_order_asset` ADD `spare_part_id` INT(11) NULL COMMENT 'if exists spare part' AFTER `product_model`;

ALTER TABLE `sales_order_asset` ADD `asset_serial` VARCHAR(255) NULL AFTER `printer_model`;

ALTER TABLE `sales_order_asset` ADD `hsn` VARCHAR(255) NULL AFTER `quantity`;

ALTER TABLE `requsition` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default/pending/new ticket,1=closed,2=done,3=work in progress, 4=Intransit,5=part required,6=delivered';


///// 22.03.2023

ALTER TABLE `requsition` ADD `close_remarks` TEXT NULL AFTER `status_update_date`;



/////////////////
18.03.2023
-------------
ALTER TABLE `requsition` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default/pending/new ticket,1=closed,2=done,3=work in progress, 4=Intransit,5=part required';
ALTER TABLE `requsition` ADD `part_required_details` VARCHAR(255) NULL AFTER `response_date`;


/////////
23.03.2023
ALTER TABLE `ticket_assign` CHANGE `user_id` `user_id` INT(11) NOT NULL COMMENT 'user table id';


///////
24.03.2023
ALTER TABLE `requsition` ADD `hold` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default/no,1=yes' AFTER `close_remarks`;



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


CREATE TABLE `ticket_remarks_trigger` (
  `id` int(11) NOT NULL,
  `old_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL COMMENT 'requisition table is',
  `asset_plan_id` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `other_remarks` text DEFAULT NULL,
  `toner` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=selected',
  `ink` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=selected',
  `maintenance_box` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=selected',
  `drum` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=selected',
  `plan` tinyint(1) DEFAULT NULL COMMENT '1=Rental,2=Cumulative,3=Maintenance',
  `cost_page` float(10,2) DEFAULT NULL,
  `no_free_page` int(11) DEFAULT NULL,
  `rental` float(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `ticket_remarks_trigger` ADD `date_of_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `old_id`;

ALTER TABLE `ticket_remarks_trigger`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `ticket_remarks_trigger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

///////////////

  DROP TRIGGER IF EXISTS `yashujee_live`.`ticket_remarks_AFTER_UPDATE`;

DELIMITER $$
USE `yashujee_live`$$
CREATE DEFINER = CURRENT_USER TRIGGER `yashujee_live`.`ticket_remarks_AFTER_UPDATE` AFTER UPDATE ON `ticket_remarks` FOR EACH ROW
BEGIN
IF (OLD.asset_plan_id <> new.asset_plan_id) OR (OLD.remarks <> new.remarks) OR (OLD.other_remarks <> new.other_remarks) OR (OLD.toner <> new.toner) OR (OLD.maintenance_box <> new.maintenance_box) OR (OLD.ink <> new.ink) OR (OLD.drum <> new.drum) OR (OLD.plan <> new.plan) OR (OLD.cost_page <> new.cost_page) OR (OLD.no_free_page <> new.no_free_page) OR (OLD.rental <> new.rental) OR (OLD.updated_at <> new.updated_at) OR (OLD.deleted <> new.deleted) 
    THEN
        INSERT into ticket_remarks_trigger VALUES ('',OLD.id,current_timestamp(),OLD.ticket_id,OLD.asset_plan_id,OLD.remarks,OLD.other_remarks,OLD.toner,OLD.ink,OLD.maintenance_box,OLD.drum,OLD.plan,OLD.cost_page,OLD.no_free_page,OLD.rental,OLD.created_at,OLD.created_by,OLD.updated_at,OLD.updated_by,OLD.deleted);  
    END IF;
END
$$
DELIMITER ;

//////////////////////////////////////////////////////////////

CREATE TABLE `ticket_assign_trigger` (
  `id` int(11) NOT NULL,
  `old_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL COMMENT 'requsition id',
  `user_id` int(11) NOT NULL COMMENT 'user table id',
  `assign_type` enum('asp','courier') DEFAULT NULL COMMENT 'asp,courier',
  `is_rto` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'only for courier assign\r\n0=default,1-yes',
  `tracking_id` varchar(255) DEFAULT NULL,
  `dispatch_status` tinyint(1) DEFAULT NULL COMMENT '0=default , 1=pending , 2=done',
  `dispatch_date` timestamp NULL DEFAULT NULL,
  `service_engineer` varchar(255) DEFAULT NULL,
  `engineer_assign_date` timestamp NULL DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `part_details` text DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_extension` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `ticket_assign_trigger` ADD `date_of_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `old_id`;
ALTER TABLE `ticket_assign_trigger`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `ticket_assign_trigger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


/////////
DROP TRIGGER IF EXISTS `yashujee`.`ticket_assign_AFTER_UPDATE`;

DELIMITER $$
USE `yashujee`$$
CREATE DEFINER = CURRENT_USER TRIGGER `yashujee`.`ticket_assign_AFTER_UPDATE` AFTER UPDATE ON `ticket_assign` FOR EACH ROW
BEGIN
IF (OLD.user_id <> new.user_id) OR (OLD.assign_type <> new.assign_type) OR (OLD.is_rto <> new.is_rto) OR (OLD.tracking_id <> new.tracking_id) OR (OLD.dispatch_status <> new.dispatch_status) OR (OLD.dispatch_date <> new.dispatch_date) OR (OLD.service_engineer <> new.service_engineer) OR (OLD.engineer_assign_date <> new.engineer_assign_date) OR (OLD.comment <> new.comment) OR (OLD.part_details <> new.part_details) OR (OLD.file_name <> new.file_name) OR (OLD.file_extension <> new.file_extension) OR (OLD.updated_at <> new.updated_at) OR (OLD.updated_by <> new.updated_by) OR (OLD.status <> new.status) OR (OLD.deleted <> new.deleted)
    THEN
        INSERT into ticket_assign_trigger VALUES ('',OLD.id,current_timestamp(),OLD.ticket_id,OLD.user_id,OLD.assign_type,OLD.is_rto,OLD.tracking_id,OLD.dispatch_status,OLD.dispatch_date,OLD.service_engineer,OLD.engineer_assign_date,OLD.comment,OLD.part_details,OLD.file_name,OLD.file_extension,OLD.created_at,OLD.created_by,OLD.updated_at,OLD.updated_by,OLD.status,OLD.deleted);  
    END IF;
END
$$
DELIMITER ;

/////////////////////////
ALTER TABLE `requsition` ADD `delivered_date` TIMESTAMP NULL AFTER `status_update_date`;


////////////////////////////////////
ALTER TABLE `ticket_remarks` ADD `customer_reference` VARCHAR(255) NULL AFTER `asset_plan_id`;
ALTER TABLE `ticket_remarks_trigger` ADD `customer_reference` VARCHAR(255) NULL AFTER `asset_plan_id`;



CREATE DEFINER = CURRENT_USER TRIGGER `yashujee`.`ticket_remarks_AFTER_UPDATE` AFTER UPDATE ON `ticket_remarks` FOR EACH ROW BEGIN IF (OLD.asset_plan_id <> new.asset_plan_id) OR (OLD.remarks <> new.remarks) OR (OLD.other_remarks <> new.other_remarks) OR (OLD.toner <> new.toner) OR (OLD.maintenance_box <> new.maintenance_box) OR (OLD.ink <> new.ink) OR (OLD.drum <> new.drum) OR (OLD.plan <> new.plan) OR (OLD.cost_page <> new.cost_page) OR (OLD.no_free_page <> new.no_free_page) OR (OLD.rental <> new.rental) OR (OLD.updated_at <> new.updated_at) OR (OLD.deleted <> new.deleted) THEN INSERT into ticket_remarks_trigger VALUES ('',OLD.id,current_timestamp(),OLD.ticket_id,OLD.asset_plan_id,OLD.customer_reference,OLD.remarks,OLD.other_remarks,OLD.toner,OLD.ink,OLD.maintenance_box,OLD.drum,OLD.plan,OLD.cost_page,OLD.no_free_page,OLD.rental,OLD.created_at,OLD.created_by,OLD.updated_at,OLD.updated_by,OLD.deleted); END IF; END;

CREATE TABLE `mail_sending` (
  `id` int(11) NOT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=sent,0=default',
  `from_email` varchar(255) NOT NULL,
  `to_email` varchar(255) NOT NULL,
  `attachment_link` text DEFAULT NULL,
  `body` longtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `mail_sending`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `mail_sending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
ALTER TABLE `mail_sending` ADD `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `ticket_id`;

///////////////////03.04.2023


ALTER TABLE `upload_status` ADD `is_import` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=import' AFTER `status`;



/////////////////////06.04.2022

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `ticket_callreport_file` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_extension` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `ticket_callreport_file`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `ticket_callreport_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;



///////// 19.04.2023

ALTER TABLE `requsition` ADD `remarks_update_date` TIMESTAMP NULL AFTER `status_update_date`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `remarks_read_flag` (
  `id` int(22) NOT NULL,
  `remarks_history_id` int(22) NOT NULL,
  `read_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=unread , 1=read',
  `read_by` int(22) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `remarks_read_flag`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `remarks_read_flag`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;
COMMIT;


////////////////////////// 04.05.223

ALTER TABLE `asset_plan` ADD `po_date` TIMESTAMP NULL AFTER `po_no`;


ALTER TABLE `sales_order_asset` ADD `is_chargable` TINYINT(1) NULL DEFAULT '0' AFTER `total_amount`;



//////////////// 08.05.2023

CREATE TABLE `courier_documents` (
  `id` int(11) NOT NULL,
  `courier_id` int(11) NOT NULL,
  `doc_type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_extension` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=default,1=deleted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `courier_documents`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `courier_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  ALTER TABLE `ticket_assign` ADD `asp_chargable_amount` DOUBLE(10,2) NULL AFTER `is_rto`;



  ////// 06.16.2023

  ALTER TABLE `upload_status` ADD `insert_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'database inserted date' AFTER `file_extension`;






























































?>