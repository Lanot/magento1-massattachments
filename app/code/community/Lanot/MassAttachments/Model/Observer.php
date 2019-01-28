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

class Lanot_MassAttachments_Model_Observer
    extends Lanot_Attachments_Model_Observer
{
    protected $_postToObjectKeys = array(
        'products'   => 'catalog_product_ids',
        'cmspages'   => 'cms_page_ids',
        //'categories' => 'catalog_category_ids',
    );

    /**
     * @var array
     */
    protected $_cacheData = array(
        'before' => array(),
        'after'  => array()
    );

    /**
     * Override parent method, to avoid issues with deleting set
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection
     */
    protected function _getDeleteProductCollection(Mage_Catalog_Model_Product $product)
    {
        return parent::_getDeleteProductCollection($product)->addFieldToFilter('set_id', array('null' => 1));
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_MassAttachments_Model_Observer
     */
    public function attachmentsSetSaveBefore($observer)
    {
        $set = $observer->getEvent()->getSet();
        foreach($this->_postToObjectKeys as $postKey => $objKey) {
            $data = $set->getData($postKey);
            if (is_string($data) && !empty($data)) {
                $set->setData($objKey, $this->_decode($data));
            } elseif ($data !== null) {
                $set->setData($objKey, is_string($data)? array() : null);
            }
        }
        //categories handling
        $data = $set->getData('category_ids');
        if (is_string($data) && !empty($data)) {
            $set->setData('catalog_category_ids', array_unique(array_filter(explode(',', $data))));
        } elseif ($data !== null) {
            $set->setData('catalog_category_ids', is_string($data)? array() : null);
        }
        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_MassAttachments_Model_Observer
     */
    public function attachmentsSetSaveAfter($observer)
    {
        /** @var $set Lanot_MassAttachments_Model_Set */
        $set = $observer->getEvent()->getSet();
        if ($set && $set->getId()) {
            $data = array(
                'set_id'             => $set->getId(),
                'product_id'         => $set->getSelectedProducts(),
                'category_id'        => $set->getSelectedCategories(),
                'page_id'            => $set->getSelectedCmsPages(),
                'website_ids'        => $set->getSelectedWebsiteIds(),
                'customer_group_ids' => $set->getSelectedCustomerGroupIds(),
                'from_date'          => $set->getFromDate(),
                'to_date'            => $set->getToDate(),
            );

            $this->_cacheData['after'] = array(
                'catalog_product'  => $set->getSelectedProducts(),
                'catalog_category' => $set->getSelectedCategories(),
                'cms_page'         => $set->getSelectedCmsPages(),
            );

            $this->_saveEntityAttachments($this->_getAttachments(), $data);
        }

        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_MassAttachments_Model_Observer
     */
    public function attachmentsSetDeleteBefore($observer)
    {
        /** @var Lanot_MassAttachments_Model_Set */
        $set = $observer->getEvent()->getSet();
        if ($set && $set->getId()) {
            $this->_deleteCollections['set'][$set->getId()] = $this->_getDeleteSetCollection($set)->load();
        }
        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_MassAttachments_Model_Observer
     */
    public function attachmentsSetDeleteAfter($observer)
    {
        /** @var Lanot_MassAttachments_Model_Set */
        $set = $observer->getEvent()->getSet();
        if ($set->getId() && !empty($this->_deleteCollections['set'][$set->getId()])) {
            $this->deleteCollection($this->_deleteCollections['set'][$set->getId()]);
            $this->_cacheData['after'] = array();
        }
        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_MassAttachments_Model_Observer
     */
    public function initCachedPages($observer)
    {
        /** @var Lanot_MassAttachments_Model_Set */
        $set = $observer->getEvent()->getSet();
        if ($set->getId()) {
            $this->_cacheData['before'] = array(
                'catalog_product'  => $set->getSelectedProducts(),
                'catalog_category' => $set->getSelectedCategories(),
                'cms_page'         => $set->getSelectedCmsPages(),
            );
        }
        return $this;
    }

    /**
     * @param Varien_Object $observer
     * @return Lanot_MassAttachments_Model_Observer
     */
    public function clearCachedPages($observer)
    {
        /** @var Lanot_MassAttachments_Model_Set */
        $set = $observer->getEvent()->getSet();
        if ($set->getId()) {
            foreach($this->_cacheData['before'] as $tag => $data) {
                $cacheIds = array_merge($this->_cacheData['before'][$tag], $this->_cacheData['after'][$tag]);
                $cacheIds = array_unique($cacheIds);
                if (!empty($cacheIds)) {
                    foreach($cacheIds as $id) {
                        $this->_cleanCache($tag, $id);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param Lanot_MassAttachments_Model_Set $set
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    protected function _getDeleteSetCollection(Lanot_MassAttachments_Model_Set $set)
    {
        return $this->_getAttachmentsModel()->getCollection()->useSet($set);
    }

    /**
     * @param $input
     * @return mixed
     */
    protected function _decode($input)
    {
        return Mage::helper('adminhtml/js')->decodeGridSerializedInput($input);
    }

    /**
     * @return Lanot_MassAttachments_Helper_Config
     */
    protected function _getHelperConfig()
    {
        return Mage::helper('lanot_massattachments/config');
    }

    /**
     * @param $name
     * @param $id
     * @return Lanot_MassAttachments_Model_Observer
     */
    protected function _cleanCache($name, $id)
    {
        Mage::app()->cleanCache($name . '_' . $id);
        return $this;
    }
}
