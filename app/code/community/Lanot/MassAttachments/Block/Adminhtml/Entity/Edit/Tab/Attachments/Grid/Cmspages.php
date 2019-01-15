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
