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

abstract class Lanot_Attachments_Block_Abstract extends Mage_Core_Block_Template
{
    const CACHE_LIFETIME = 3600;

    protected $_entity_type = null;
    protected $_collection = null;
    protected $_storeId = 0;
    protected $_entity = null;
    protected $_enabled = true;
    protected $_tmpl = 'lanot/attachments/list.phtml';

    /**
     * Prepare template
     */
    protected function _construct()
    {
        $this->_enabled = $this->_getHelperConfig()->isEnabled($this->_entity_type);
        if ($this->_enabled) {
            $this->setTemplate($this->_tmpl);
            $this->_storeId = $this->_getStoreId();
            $this->_initCache();
        } else {
            $this->setTemplate(null);
        }
    }

    /**
     * @return int
     */
    protected function _getStoreId()
    {
        return Mage::app()->getStore()->getId();
    }

    /**
     * @return string
     */
    protected function _getCacheTag()
    {
        return $this->_getHelperConfig()->getCacheTag($this->_entity_type);
    }

    /**
     * @return string
     */
    protected function _getCacheKey()
    {
        return $this->_getHelperConfig()->getCacheKey($this->_entity_type, $this->getEntity()->getId(), $this->_storeId);
    }

    /**
     * @throws Exception
     */
    protected function _initCache()
    {
        if (null === $this->_getCacheTag()) {
            throw new Exception($this->__('Undefined cache key passed to block'));
        }

        $cacheData = array(
            'cache_lifetime' => self::CACHE_LIFETIME,
            'cache_tags' => array($this->_getCacheTag()),
            'cache_key' => $this->_getCacheKey(),
        );
        $this->addData($cacheData);
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getEntity()
    {
        return null;
    }

    /**
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection|null
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection
     */
    protected function _initCollection()
    {
        return $this->_getAttachmentsModel()->getCollection()
            ->addTitleToFields($this->_storeId)
            ->addLinkToFields($this->_storeId)
            ->addOrder('sort_order', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    /**
     * @return bool
     */
    public function hasItems()
    {
        return ($this->getEntity() && $this->getCollection()->count());
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->_entity_type;
    }

    /**
     * @return Lanot_Attachments_Helper_Config
     */
    protected function _getHelperConfig()
    {
        return Mage::helper('lanot_attachments/config');
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _getAttachmentsModel()
    {
        return Mage::getModel('lanot_attachments/attachments');
    }
}
