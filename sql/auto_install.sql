CREATE TABLE `civicrm_ds_saved_search` (

   `id` INT unsigned NOT NULL AUTO_INCREMENT COMMENT 'Donor Search saved search ID',
   `contact_id` INT unsigned NOT NULL COMMENT 'Foreign key to the contact for this record',
   `creator_id` INT unsigned DEFAULT NULL COMMENT 'Foreign key to the contact who created this record',
   `search_criteria` LONGTEXT NOT NULL COMMENT 'Serialized donor search data',

   PRIMARY KEY (`id`),

   CONSTRAINT FK_civicrm_ds_saved_search_contact_id FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE CASCADE,
   CONSTRAINT FK_civicrm_ds_saved_search_creator_id FOREIGN KEY (`creator_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL

  ) ENGINE = InnoDB;
