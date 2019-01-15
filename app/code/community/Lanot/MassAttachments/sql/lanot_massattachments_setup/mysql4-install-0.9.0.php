<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Lanot
 * @package     Lanot_Attachments
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$coreStoreTable = $installer->getTable('core/store');
$websiteTable = $this->getTable('core/website');
$customerGroupTable = $this->getTable('customer/customer_group');

$catalogProductTable = $installer->getTable('catalog/product');
$catalogCategoryTable = $installer->getTable('catalog/category');
$cmsPageTable = $installer->getTable('cms/page');

$attachmentsEntityTable = $installer->getTable('lanot_attachments/entity');
$attachmentsSetTable = $installer->getTable('lanot_massattachments/set');

$attachmentsProductsTable = $installer->getTable('lanot_massattachments/products');
$attachmentsCategoryTable = $installer->getTable('lanot_massattachments/categories');
$attachmentsCmsPageTable = $installer->getTable('lanot_massattachments/cms_pages');


//CREATE TABLE IF NOT EXISTSfor attachments sets
$installer->run("
	CREATE TABLE IF NOT EXISTS`{$attachmentsSetTable}` (
        `set_id`   int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Set ID',
        `title`         varchar(255) DEFAULT NULL,
        `description`   tinytext,

        `from_date` date DEFAULT NULL COMMENT 'From Date',
        `to_date` date DEFAULT NULL COMMENT 'To Date',
        `customer_group_ids` text COMMENT 'Customer Group Ids',
        `website_ids` text COMMENT 'Website Ids',
        `catalog_product_ids` text COMMENT 'Catalog Product Ids',
        `catalog_category_ids` text COMMENT 'Catalog Category Ids',
        `cms_page_ids` text COMMENT 'Cms Page Ids',

        `created_at` timestamp NULL DEFAULT NULL COMMENT 'Creation Time',
        `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',
        PRIMARY KEY (`set_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachment Set Table';
");

//add new columns
try {
    $installer->getConnection()->query("SELECT `set_id` FROM `{$attachmentsEntityTable}` LIMIT 1");
    Mage::log("Column `set_id` already exists in table `{$attachmentsEntityTable}`");
} catch (Zend_Db_Statement_Exception $e) {
    $installer->run("
      ALTER TABLE `{$attachmentsEntityTable}` ADD `set_id` int(10) unsigned DEFAULT NULL COMMENT 'Attachments Set ID';
      ALTER TABLE `{$attachmentsEntityTable}` ADD CONSTRAINT FK_LNTATTACHMENTS_SET_ID_SET_ID FOREIGN KEY (`set_id`) REFERENCES `{$attachmentsSetTable}`(`set_id`) ON DELETE CASCADE ON UPDATE CASCADE;
    ");
    Mage::log("Column `set_id` added to table `{$attachmentsEntityTable}`");
} catch (Exception $e) {
    Mage::logException($e);
}

//CREATE TABLE IF NOT EXISTSattachments to products
$this->run("
	CREATE TABLE IF NOT EXISTS`{$attachmentsProductsTable}` (
	    `product_id`   int(10) unsigned NOT NULL COMMENT 'Product ID',
	    `attachment_id`   int(10) unsigned NOT NULL COMMENT 'Attachment ID',

        `from_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'From Time',
        `to_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'To time',
        `customer_group_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Customer Group Id',
        `website_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Website Id',

        UNIQUE KEY `UNQ_LNTATCMH_PRODUCT` (`product_id`, `from_time`, `to_time`, `website_id`, `customer_group_id`, `attachment_id`),

        KEY `IDX_LNTATCMH_PRODUCT_PRODUCT_ID` (`product_id`),
        KEY `IDX_LNTATCMH_PRODUCT_CUSTOMER_GROUP_ID` (`customer_group_id`),
        KEY `IDX_LNTATCMH_PRODUCT_WEBSITE_ID` (`website_id`),
        KEY `IDX_LNTATCMH_PRODUCT_FROM_TIME` (`from_time`),
        KEY `IDX_LNTATCMH_PRODUCT_TO_TIME` (`to_time`),
        KEY `IDX_LNTATCMH_PRODUCT_STICKER_ID` (`attachment_id`),

  		CONSTRAINT `FK_LNTATCMH_PRODUCT_ATTACHMENT_ATTACHMENT_ID` FOREIGN KEY (`attachment_id`) REFERENCES `{$attachmentsEntityTable}` (`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  		CONSTRAINT `FK_LNTATCMH_PRODUCT_PRODUCT_ENTITY_ID` FOREIGN KEY (`product_id`) REFERENCES `{$catalogProductTable}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_LNTATCMH_PRODUCT_WEBSITE_WEBSITE_ID` FOREIGN KEY (`website_id`) REFERENCES `{$websiteTable}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_LNTATCMH_PRODUCT_CSTR_GROUP_GROUP_ID` FOREIGN KEY (`customer_group_id`) REFERENCES `{$customerGroupTable}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments To Products Table';
");


//CREATE TABLE IF NOT EXISTSattachments to categories
$this->run("
	CREATE TABLE IF NOT EXISTS`{$attachmentsCategoryTable}` (
	    `category_id`   int(10) unsigned NOT NULL COMMENT 'Category ID',
	    `attachment_id`   int(10) unsigned NOT NULL COMMENT 'Attachment ID',

        `from_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'From Time',
        `to_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'To time',
        `customer_group_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Customer Group Id',
        `website_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Website Id',

        UNIQUE KEY `UNQ_LNTATCMH_CATEGORY` (`category_id`, `from_time`, `to_time`, `website_id`, `customer_group_id`, `attachment_id`),

        KEY `IDX_LNTATCMH_CATEGORY_CATEGORY_ID` (`category_id`),
        KEY `IDX_LNTATCMH_CATEGORY_CUSTOMER_GROUP_ID` (`customer_group_id`),
        KEY `IDX_LNTATCMH_CATEGORY_WEBSITE_ID` (`website_id`),
        KEY `IDX_LNTATCMH_CATEGORY_FROM_TIME` (`from_time`),
        KEY `IDX_LNTATCMH_CATEGORY_TO_TIME` (`to_time`),
        KEY `IDX_LNTATCMH_CATEGORY_STICKER_ID` (`attachment_id`),

  		CONSTRAINT `FK_LNTATCMH_CATEGORY_ATTACHMENT_ATTACHMENT_ID` FOREIGN KEY (`attachment_id`) REFERENCES `{$attachmentsEntityTable}` (`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  		CONSTRAINT `FK_LNTATCMH_CATEGORY_CATEGORY_ENTITY_ID` FOREIGN KEY (`category_id`) REFERENCES `{$catalogCategoryTable}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_LNTATCMH_CATEGORY_WEBSITE_WEBSITE_ID` FOREIGN KEY (`website_id`) REFERENCES `{$websiteTable}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_LNTATCMH_CATEGORY_CSTR_GROUP_GROUP_ID` FOREIGN KEY (`customer_group_id`) REFERENCES `{$customerGroupTable}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments To Categories Table';
");


//CREATE TABLE IF NOT EXISTSattachments to cms pages
$this->run("
	CREATE TABLE IF NOT EXISTS`{$attachmentsCmsPageTable}` (
	    `page_id`   smallint(6) NOT NULL COMMENT 'Cms Page ID',
	    `attachment_id`   int(10) unsigned NOT NULL COMMENT 'Attachment ID',

        `from_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'From Time',
        `to_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'To time',
        `customer_group_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Customer Group Id',
        `website_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Website Id',

        UNIQUE KEY `UNQ_LNTATCMH_CMS_PAGE` (`page_id`, `from_time`, `to_time`, `website_id`, `customer_group_id`, `attachment_id`),

        KEY `IDX_LNTATCMH_CMS_PAGE_CMS_PAGE_ID` (`page_id`),
        KEY `IDX_LNTATCMH_CMS_PAGE_CUSTOMER_GROUP_ID` (`customer_group_id`),
        KEY `IDX_LNTATCMH_CMS_PAGE_WEBSITE_ID` (`website_id`),
        KEY `IDX_LNTATCMH_CMS_PAGE_FROM_TIME` (`from_time`),
        KEY `IDX_LNTATCMH_CMS_PAGE_TO_TIME` (`to_time`),
        KEY `IDX_LNTATCMH_CMS_PAGE_STICKER_ID` (`attachment_id`),

  		CONSTRAINT `FK_LNTATCMH_CMS_PAGE_ATTACHMENT_ATTACHMENT_ID` FOREIGN KEY (`attachment_id`) REFERENCES `{$attachmentsEntityTable}` (`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  		CONSTRAINT `FK_LNTATCMH_CMS_PAGE_CMS_PAGE_ID` FOREIGN KEY (`page_id`) REFERENCES `{$cmsPageTable}` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_LNTATCMH_CMS_PAGE_WEBSITE_WEBSITE_ID` FOREIGN KEY (`website_id`) REFERENCES `{$websiteTable}` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_LNTATCMH_CMS_PAGE_CSTR_GROUP_GROUP_ID` FOREIGN KEY (`customer_group_id`) REFERENCES `{$customerGroupTable}` (`customer_group_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments To Cms Pages Table';
");

$installer->endSetup();
