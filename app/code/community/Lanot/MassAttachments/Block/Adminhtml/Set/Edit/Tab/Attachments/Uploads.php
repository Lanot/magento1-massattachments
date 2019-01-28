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

class Lanot_MassAttachments_Block_Adminhtml_Set_Edit_Tab_Attachments_Uploads
    extends Lanot_Attachments_Block_Adminhtml_Tab_Abstract
{
    /**
     * @return Lanot_MassAttachments_Model_Set
     */
    public function getEntity()
    {
        return $this->_getHelper()->getSetItemInstance();
    }

    /**
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    public function getCollection()
    {
        if (null === $this->_collection && $this->getEntity()) {
            $this->_collection = $this->_initCollection()->useSet($this->getEntity());
            $this->_collection->addOrder('sort_order', Varien_Data_Collection::SORT_ORDER_ASC);
        }
        return parent::getCollection();
    }

    /**
     * @return Lanot_MassAttachments_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_massattachments');
    }
}
