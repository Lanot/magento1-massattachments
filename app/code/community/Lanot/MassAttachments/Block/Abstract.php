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

abstract class Lanot_MassAttachments_Block_Abstract
    extends Lanot_Attachments_Block_Abstract
{
    /**
     * @return string
     */
    protected function _getCacheTag()
    {
        return $this->_getHelperConfig()->getCacheTag($this->_entity_type) . ' ' . $this->getEntity()->getId();
    }

    /**
     * @return string
     */
    protected function _getCacheKey()
    {
        return $this->_getHelperConfig()->getAdvancedCacheKey($this->_entity_type,
            $this->getEntity()->getId(),
            $this->_storeId,
            $this->_getHelper()->getWebsiteId(),
            $this->_getHelper()->getCustomerGroupId(),
            date('Y-m-d')
        );
    }

    /**
     * @return Lanot_MassAttachments_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_massattachments');
    }

    /**
     * @return Lanot_MassAttachments_Helper_Config
     */
    protected function _getHelperConfig()
    {
        return Mage::helper('lanot_massattachments/config');
    }
}
