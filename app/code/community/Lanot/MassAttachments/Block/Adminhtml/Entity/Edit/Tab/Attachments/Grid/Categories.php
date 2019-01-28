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

class Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Grid_Categories
    extends Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Grid_Abstract
{
    protected $_url = 'lanotmassattachmnets/adminhtml_attachments/categoriesgrid';
    protected $_category = null;

    /**
     * Retrieve selected items
     *
     * @return array
     */
    public function getSelectedLinks()
    {
        if (null !== $this->_selectedLinks) {
            return $this->_selectedLinks;
        }

        $this->_selectedLinks = $this->_getAttachmentsModel()
            ->getSelectedAttachmentIdsToCategory($this->_getCategory(), true);

        return $this->_selectedLinks;
    }

    /**
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    protected function _getCollection()
    {
        return $this->_initCollection()->useCategory($this->_getCategory())
            ->addFieldToFilter('main_table.attachment_id', array('in' => $this->getSelectedLinks()));
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    protected function _getCategory()
    {
        if (null === $this->_category) {
            $categoryId = (int) $this->getRequest()->getParam('category_id', 0);
            $this->_category = Mage::getModel('catalog/category')->load($categoryId);
        }
        return $this->_category;
    }
}
