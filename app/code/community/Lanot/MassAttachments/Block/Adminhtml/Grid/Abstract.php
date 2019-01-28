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

/**
 * Grid Abstract
 *
 * @author Lanot
 */
class Lanot_MassAttachments_Block_Adminhtml_Grid_Abstract
    extends Lanot_Core_Block_Adminhtml_Grid_Abstract
{
    protected $_isTabGrid = true;

    /**
     * @return Lanot_MassAttachments_Model_Set
     */
    protected function _getItemModel()
    {
        return Mage::getSingleton('lanot_massattachments/set');
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

    /**
     * @return int
     */
    protected function _getStoreId()
    {
        return $this->getRequest()->getParam('store_id', 0);
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return !$this->_getAclHelper()->isActionAllowed('manage_set/assign');
    }
}
