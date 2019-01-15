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
 * @package     Lanot_MassAttachments
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
