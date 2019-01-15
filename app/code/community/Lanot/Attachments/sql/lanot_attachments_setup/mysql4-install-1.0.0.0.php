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

$installer = $this;

$installer->startSetup();

$coreStoreTable = $installer->getTable('core/store');
$productEntityTable = $installer->getTable('catalog/product');

$attachmentsEntityTable = $installer->getTable('lanot_attachments/entity');
$attachmentsTitlesTable = $installer->getTable('lanot_attachments/titles');
$attachmentsLinksTable = $installer->getTable('lanot_attachments/links');

//create table for attachments entities
$installer->run("
	DROP TABLE IF EXISTS `{$attachmentsEntityTable}`;
	CREATE TABLE `{$attachmentsEntityTable}` (
	    `attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Attachment ID',
		`product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'product_id',
		`sort_order` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Sort Order',
		PRIMARY KEY (`attachment_id`),
  		CONSTRAINT `FK_ATTACHMENTS_PARENT_ID` FOREIGN KEY (`product_id`) REFERENCES `{$productEntityTable}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments Table';
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
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attachments Table';
");
$installer->endSetup();
