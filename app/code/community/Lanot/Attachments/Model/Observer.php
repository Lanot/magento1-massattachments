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

class Lanot_Attachments_Model_Observer
{
    protected $_postKey = 'attachments';
    protected $_deleteCollections  = array();

    /**
     * @param Varien_Object $observer
     * @return Lanot_Attachments_Model_Observer
     */
    public function catalogProductSaveAfter($observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product && $product->getId()) {
            $this->_saveEntityAttachments($this->_getAttachments(), array('product_id' => $product->getId()));
            $this->_removeCache('catalog_product', $product->getId(), $this->_getStoreId());
        }
        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_Attachments_Model_Observer
     */
    public function catalogProductDeleteBefore($observer)
    {
        /** @var Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        if ($product && $product->getId()) {
            $this->_deleteCollections['product'][$product->getId()] = $this->_getDeleteProductCollection($product)->load();
        }
        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_Attachments_Model_Observer
     */
    public function catalogProductDeleteAfter($observer)
    {
        /** @var Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        if ($product->getId() && !empty($this->_deleteCollections['product'][$product->getId()])) {
            $this->deleteCollection($this->_deleteCollections['product'][$product->getId()]);
        }
        return $this;
    }

    /**
     * @param array $files
     * @param array $fileData
     * @return $this
     */
    protected function _saveEntityAttachments(array $files, array $fileData = array())
    {
        foreach($files as &$file) {
            $file = array_merge($file, $fileData);//add relations data
            $attachment = $this->_getAttachmentsModel()->setStoreId($this->_getStoreId())->prepareLinkData($file);
            if ($attachment->isDeleted() && $attachment->getId()) {
                $attachment->delete();
            } elseif (!$attachment->isDeleted()) {
                $attachment->save();
            }
        }
        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Product
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection
     */
    protected function _getDeleteProductCollection(Mage_Catalog_Model_Product $product)
    {
        return $this->_getAttachmentsModel()->getCollection()->useProduct($product);
    }

    /**
     * @param Lanot_Attachments_Model_Mysql4_Attachments_Collection $collection
     * @return Lanot_Attachments_Model_Observer
     */
    protected function deleteCollection(Lanot_Attachments_Model_Mysql4_Attachments_Collection $collection)
    {
        /** @var $item Lanot_Attachments_Model_Attachments */
        foreach($collection as $item) {
            $item->delete();
        }
        return $this;
    }

    /**
     * @param $name
     * @param $id
     * @param $storeId
     * @return Lanot_Attachments_Model_Observer
     */
    protected function _removeCache($name, $id, $storeId)
    {
        $cacheKey = $this->_getHelperConfig()->getCacheKey($name, $id, $storeId);
        if ($cacheKey) {
            Mage::app()->removeCache($cacheKey);
        }
        return $this;
    }

    /**
     * @return Lanot_Attachments_Helper_Config
     */
    protected function _getHelperConfig()
    {
        return Mage::helper('lanot_attachments/config');
    }

    /**
     * @return Lanot_Attachments_Helper_File
     */
    protected function _getHelperFile()
    {
        return Mage::helper('lanot_attachments/file');
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _getAttachmentsModel()
    {
        return Mage::getModel('lanot_attachments/attachments');
    }

    /**
     * @return array
     */
    protected function _getAttachments()
    {
        return Mage::app()->getRequest()->getPost($this->_postKey, array());
    }

    /**
     * @return int
     */
    protected function _getStoreId()
    {
        return Mage::app()->getRequest()->getParam('store', 0);
    }
}
