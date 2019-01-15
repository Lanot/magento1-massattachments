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

class Lanot_Attachments_Model_Mysql4_Attachments_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('lanot_attachments/attachments');
    }

    /**
     * @return string
     */
    protected function _getFilesTable()
    {
        return $this->getTable('lanot_attachments/links');
    }

    /**
     * @return string
     */
    protected function _getTitlesTable()
    {
        return $this->getTable('lanot_attachments/titles');
    }

    /**
     * @return string
     */
    protected function _getProductsTable()
    {
        return $this->getTable('lanot_attachments/products');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection
     */
    public function useProduct(Mage_Catalog_Model_Product $product)
    {
        $this->getSelect()->joinInner(array('addtab' => $this->_getProductsTable()),
            'addtab.attachment_id=main_table.attachment_id AND addtab.product_id = ' . (int) $product->getId(),
            array()
        );
        return $this;
    }

    /**
     * @param int $storeId
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection
     */
    public function addTitleToFields($storeId = 0)
    {
        $ifNullDefaultTitle = $this->getResource()->getIfNullSql('at.title', 'a.title');
        $this->getSelect()
            ->joinLeft(array('a' => $this->_getTitlesTable()),
                'a.attachment_id=main_table.attachment_id AND a.store_id = 0',
                array(
                    'default_title' => 'title'
                )
            )
            ->joinLeft(array('at' => $this->_getTitlesTable()),
                'at.attachment_id=main_table.attachment_id AND at.store_id = ' . (int)$storeId,
                array(
                    'store_title' => 'title',
                    'title' => $ifNullDefaultTitle
                )
            );
        return $this;
    }

    /**
     * @param int $storeId
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection
     */
    public function addLinkToFields($storeId = 0)
    {
        $ifNullDefaultType = $this->getResource()->getIfNullSql('lt.type', 'l.type');
        $ifNullDefaultFile = $this->getResource()->getIfNullSql('lt.file', 'l.file');
        $ifNullDefaultUrl = $this->getResource()->getIfNullSql('lt.url', 'l.url');

        $this->getSelect()
            ->joinLeft(array('l' => $this->_getFilesTable()),
                'l.attachment_id=main_table.attachment_id AND l.store_id = 0',
                array(
                    'default_type' => 'type',
                    'default_file' => 'file',
                    'default_url' => 'url',
                )
            )
            ->joinLeft(array('lt' => $this->_getFilesTable()),
                'lt.attachment_id=main_table.attachment_id AND lt.store_id = ' . (int)$storeId,
                array(
                    'store_type' => 'type',
                    'type' => $ifNullDefaultType,
                    'store_url' => 'url',
                    'url' => $ifNullDefaultUrl,
                    'store_file' => 'file',
                    'file' => $ifNullDefaultFile,
                )
            );
        return $this;
    }
}
