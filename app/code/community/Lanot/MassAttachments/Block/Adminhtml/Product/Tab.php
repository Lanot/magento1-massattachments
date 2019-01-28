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

class Lanot_MassAttachments_Block_Adminhtml_Product_Tab
    extends Lanot_Attachments_Block_Adminhtml_Product_Tab
{
    public function getCollection()
    {
        if (null === $this->_collection && $this->getEntity()) {
            $ids = $this->_getAttachmentsModel()->getSelectedAttachmentIdsToProduct($this->getEntity(), false);
            $this->_collection = $this->_initCollection()
                ->useProduct($this->getEntity())
                ->addFieldToFilter('main_table.set_id', array('null' => 1))
                ->addFieldToFilter('main_table.attachment_id', array('in' => $ids))
                ->addOrder('main_table.sort_order', Varien_Data_Collection::SORT_ORDER_ASC);
        }
        return parent::getCollection();
    }
}
