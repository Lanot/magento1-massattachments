<?php
/**
 * Private Entrepreneur Anatolii Lehkyi (aka Lanot)
 *
 * @category    Lanot
 * @package     Lanot_Attachments
 * @copyright   Copyright (c) 2010 Anatolii Lehkyi
 * @license     http://opensource.org/licenses/osl-3.0.php
 * @link        http://www.lanot.biz/
 */

$installer = $this;

$installer->startSetup();

$coreStoreTable = $installer->getTable('core/store');
$productEntityTable = $installer->getTable('catalog/product');

$attachmentsEntityTable = $installer->getTable('lanot_attachments/entity');
$attachmentsTitlesTable = $installer->getTable('lanot_attachments/titles');
$attachmentsLinksTable = $installer->getTable('lanot_attachments/links');
$attachmentsProductsTable = $installer->getTable('lanot_attachments/products');

//create table for attachments entities
$installer->run("
    DROP TABLE IF EXISTS `{$attachmentsEntityTable}`;
    CREATE TABLE `{$attachmentsEntityTable}` (
        `attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Attachment ID',
        `sort_order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Sort Order',
        `created_at` timestamp NULL DEFAULT NULL COMMENT 'Creation Time',
        `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Update Time',
        PRIMARY KEY (`attachment_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments Entity Table';
");

//create table for attachments titles
$installer->run("
    DROP TABLE IF EXISTS `{$attachmentsTitlesTable}`;
    CREATE TABLE `{$attachmentsTitlesTable}` (
        `attachment_id` int(10) unsigned NOT NULL COMMENT 'Attachment ID',
        `store_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Store ID',
        `title` varchar(255) DEFAULT NULL COMMENT 'Title',  
        PRIMARY KEY (`attachment_id`, `store_id`),
        KEY `IDX_ATTACHMENT_ID` (`attachment_id`),
        KEY `IDX_STORE_ID` (`store_id`),
        CONSTRAINT `FK_ATTACHMENTS_TITLES_ATTACHMENTS_ID` FOREIGN KEY (`attachment_id`) REFERENCES `{$attachmentsEntityTable}` (`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE,  
        CONSTRAINT `FK_ATTACHMENTS_TITLES_STORE_ID` FOREIGN KEY (`store_id`) REFERENCES `{$coreStoreTable}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments Titles Table';
");

//create table for attachments links
$installer->run("
    DROP TABLE IF EXISTS `{$attachmentsLinksTable}`;
    CREATE TABLE `{$attachmentsLinksTable}` (
        `attachment_id` int(10) unsigned NOT NULL COMMENT 'Attachment ID',
        `store_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Store ID',
        `url` varchar(255) DEFAULT NULL COMMENT 'Attachment URL',
        `file` varchar(255) DEFAULT NULL COMMENT 'Attachment file',
        `type` enum('file', 'url') DEFAULT NULL,        
        PRIMARY KEY (`attachment_id`, `store_id`),
        KEY `IDX_ATTACHMENT_ID` (`attachment_id`),
        KEY `IDX_STORE_ID` (`store_id`),        
        CONSTRAINT `FK_ATTACHMENTS_LINKS_ATTACHMENTS_ID` FOREIGN KEY (`attachment_id`) REFERENCES `{$attachmentsEntityTable}` (`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE,  
        CONSTRAINT `FK_ATTACHMENTS_LINKS_STORE_ID` FOREIGN KEY (`store_id`) REFERENCES `{$coreStoreTable}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments Links Table';
");

//create table for attachments to products entities
$installer->run("
    DROP TABLE IF EXISTS `{$attachmentsProductsTable}`;
    CREATE TABLE `{$attachmentsProductsTable}` (
        `attachment_id` int(10) unsigned NOT NULL COMMENT 'Attachment ID',
        `product_id`    int(10) unsigned NOT NULL COMMENT 'Product ID',
        PRIMARY KEY (`attachment_id`, `product_id`),
        CONSTRAINT `FK_ATTACHMENTS_PRODUCTS_ATTACHMENT_ID` FOREIGN KEY (`attachment_id`) REFERENCES `{$attachmentsEntityTable}` (`attachment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `FK_ATTACHMENTS_PRODUCTS_PRODUCT_ID` FOREIGN KEY (`product_id`) REFERENCES `{$productEntityTable}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments Entity To Products Table';
");

$installer->endSetup();
