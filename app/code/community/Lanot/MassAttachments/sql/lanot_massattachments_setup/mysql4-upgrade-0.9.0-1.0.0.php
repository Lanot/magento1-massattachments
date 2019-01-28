<?php
/**
 * Private Entrepreneur Anatolii Lehkyi (aka Lanot)
 *
 * @category    Lanot
 * @package     Lanot_MassAttachments
 * @copyright   Copyright (c) 2010 Anatolii Lehkyi
 * @license     http://opensource.org/licenses/osl-3.0.php
 * @link        http://www.lanot.biz/
 */

$installer = $this;

$installer->startSetup();

$attachmentsProductsTable = $installer->getTable('lanot_attachments/products');
$massAttachmentsProductsTable = $installer->getTable('lanot_massattachments/products');

//populate mass attachments relations table
$installer->run("
  INSERT INTO `{$massAttachmentsProductsTable}` (`attachment_id`, `product_id`)
  SELECT a.`attachment_id`, a.`product_id` FROM `{$attachmentsProductsTable}` as a
    LEFT JOIN `{$massAttachmentsProductsTable}` as b ON
      b.`attachment_id` = a.`attachment_id`
      AND b.`product_id` = a.`product_id`
      AND b.customer_group_id IS NULL
      AND b.website_id IS NULL
    WHERE b.attachment_id IS NULL
");

$installer->endSetup();
