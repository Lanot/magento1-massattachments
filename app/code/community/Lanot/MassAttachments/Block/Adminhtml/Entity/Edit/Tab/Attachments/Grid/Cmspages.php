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

class Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Grid_Cmspages
    extends Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Grid_Abstract
{
    protected $_url = 'lanotmassattachmnets/adminhtml_attachments/cmspagesgrid';
    protected $_page = null;

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
            ->getSelectedAttachmentIdsToCmsPage($this->_getCmsPage(), true);

        return $this->_selectedLinks;
    }

    /**
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    protected function _getCollection()
    {
        return $this->_initCollection()->useCmsPage($this->_getCmsPage())
            ->addFieldToFilter('main_table.attachment_id', array('in' => $this->getSelectedLinks()));
    }

    /**
     * @return Mage_Cms_Model_Page
     */
    protected function _getCmsPage()
    {
        if (null === $this->_page) {
            $pageId = (int) $this->getRequest()->getParam('page_id', 0);
            $this->_page = Mage::getModel('cms/page')->load($pageId);
        }
        return $this->_page;
    }
}
