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

$attachmentsEntityTable = $installer->getTable('lanot_attachments/entity');
$attachmentsProductsTable = $installer->getTable('lanot_attachments/products');

//populate attachments relations table
$installer->run("
  INSERT INTO `{$attachmentsProductsTable}` (`attachment_id`, `product_id`)
  SELECT `attachment_id`, `product_id` FROM `{$attachmentsEntityTable}`
");

$installer->endSetup();
