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

$productEntityTable = $installer->getTable('catalog/product');
$attachmentsEntityTable = $installer->getTable('lanot_attachments/entity');
$attachmentsProductsTable = $installer->getTable('lanot_attachments/products');

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
