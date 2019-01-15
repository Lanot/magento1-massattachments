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
