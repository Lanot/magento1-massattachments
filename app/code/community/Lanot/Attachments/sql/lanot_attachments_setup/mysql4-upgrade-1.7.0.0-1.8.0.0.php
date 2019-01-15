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
