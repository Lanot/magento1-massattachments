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

class Lanot_MassAttachments_Adminhtml_SetController
    extends Lanot_Core_Controller_Adminhtml_AbstractController
{
    protected $_msgTitle = 'Attachments';
    protected $_msgHeader = 'Manage Attachment Sets';
    protected $_msgItemDoesNotExist = 'The Attachment Set item does not exist.';
    protected $_msgItemNotFound = 'Unable to find the Attachment Set item #%s.';
    protected $_msgItemEdit = 'Edit Attachment Set Item';
    protected $_msgItemNew = 'New Attachment Set Item';
    protected $_msgItemSaved = 'The Attachment Set item has been saved.';
    protected $_msgItemDeleted = 'The Attachment Set item has been deleted';
    protected $_msgError = 'An error occurred while edit the Attachment Set item.';
    protected $_msgErrorItems = 'An error occurred while edit the Attachment Set items %s.';
    protected $_msgItems = 'The Attachment Set items (#%s) has been';

    protected $_menuActive = 'lanot/lanot_massattachments';
    protected $_aclSection = 'manage_set';

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_massattachments/set');
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     * @return Lanot_MassAttachments_Adminhtml_SetController
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        Mage::register('lanot.attachments.set.item', $model);
        return $this;
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
     * Products Grid with serializer ajax action
     */
    public function productsgridAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Products Grid only ajax action
     */
    public function productsgridonlyAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Cms Pages Grid with serializer ajax action
     */
    public function cmspagesgridAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Cms Pages Grid only ajax action
     */
    public function cmspagesgridonlyAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Categories Tree action
     */
    public function categoriestreeAction()
    {
        $this->_loadLayouts();
    }

    /**
     * Get tree node (Ajax version)
     */
    public function categoriesJsonAction()
    {
        $block = $this->getLayout()->createBlock('lanot_massattachments/adminhtml_set_edit_tab_categories_tree');
        if ($block) {
            $this->getResponse()->setBody($block->getCategoryChildrenJson($this->getRequest()->getParam('category')));
        }
    }
}
