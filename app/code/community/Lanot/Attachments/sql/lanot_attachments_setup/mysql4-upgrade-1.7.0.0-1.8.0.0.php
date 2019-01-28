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

//add new columns
$installer->run("
    ALTER TABLE `{$attachmentsEntityTable}` ADD `created_at` timestamp NULL DEFAULT NULL COMMENT 'Creation Time';
    ALTER TABLE `{$attachmentsEntityTable}` ADD `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Update Time';
");

//remove existed columns and foreign keys
$installer->run("
    ALTER TABLE `{$attachmentsEntityTable}` DROP FOREIGN KEY `FK_ATTACHMENTS_PARENT_ID`;
    ALTER TABLE `{$attachmentsEntityTable}` DROP COLUMN `product_id`;
");

$installer->endSetup();
