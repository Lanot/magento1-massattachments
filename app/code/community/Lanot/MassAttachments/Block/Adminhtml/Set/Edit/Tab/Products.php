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

class Lanot_MassAttachments_Block_Adminhtml_Set_Edit_Tab_Products
    extends Mage_Core_Block_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('Associated Products');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return $this->_getAclHelper()->isActionAllowed('manage_set/save');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return !$this->canShowTab();
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('*/*/productsgrid',
            array('set_id' => $this->_getHelper()->getSetItemInstance()->getId(), '_secure'=>true)
        );
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * @return Lanot_MassAttachments_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_massattachments');
    }

    /**
     * @return Lanot_MassAttachments_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_massattachments/admin');
    }
}
