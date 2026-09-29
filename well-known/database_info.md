ALTER TABLE `users` ADD `related_module` VARCHAR(255) NULL AFTER `remember_token`, ADD `module_id` INT(11) NULL AFTER `related_module`;
ALTER TABLE `users` CHANGE `related_module` `related_module` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'asp,courier,customer';
ALTER TABLE `courier` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `requsition` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=default,1=closed,2=done';
ALTER TABLE `ticket_assign` ADD `tracking_id` VARCHAR(255) NULL AFTER `assign_type`;
ALTER TABLE `asset_plan` CHANGE `no_page` `no_page` INT(11) NULL;



