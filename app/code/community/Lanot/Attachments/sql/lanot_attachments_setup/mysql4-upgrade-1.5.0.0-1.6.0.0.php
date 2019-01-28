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
