CREATE TABLE `civicrm_ds_saved_search` (

   `id` INT unsigned NOT NULL AUTO_INCREMENT COMMENT 'Donor Search saved search ID',
   `search_criteria` LONGTEXT NOT NULL COMMENT 'Serialized donor search data',

   PRIMARY KEY (`id`)

  ) ENGINE = InnoDB;
