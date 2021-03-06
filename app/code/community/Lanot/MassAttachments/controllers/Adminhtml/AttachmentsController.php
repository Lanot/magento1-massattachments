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

class Lanot_MassAttachments_Adminhtml_AttachmentsController
    extends Lanot_Core_Controller_Adminhtml_AbstractController
{
    protected $_msgTitle = 'Attachments';
    protected $_msgHeader = 'Manage Attachment Sets';
    protected $_msgItemDoesNotExist = 'The Attachment item does not exist.';
    protected $_msgItemNotFound = 'Unable to find the Attachment item #%s.';
    protected $_msgItemEdit = 'Edit Attachment Item';
    protected $_msgItemNew = 'New Attachment Item';
    protected $_msgItemSaved = 'The Attachment item has been saved.';
    protected $_msgItemDeleted = 'The Attachment item has been deleted';
    protected $_msgError = 'An error occurred while edit the Attachment item.';
    protected $_msgErrorItems = 'An error occurred while edit the Attachment items %s.';
    protected $_msgItems = 'The Attachment items (#%s) has been';

    protected $_menuActive = 'lanot/lanot_massattachments';
    protected $_aclSection = 'manage_set';

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_massattachments/attachments');
    }

    /**
     * @param Mage_Core_Model_Abstract $model
     * @return Lanot_MassAttachments_Adminhtml_AttachmentsController
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        Mage::register('lanot.attachments.attachments.item', $model);
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
     *  Grid ajax action from product edit page
     */
    public function productsgridAction()
    {
        $this->_loadLayouts();
    }

    /**
     *  Grid ajax action from category edit page
     */
    public function categoriesgridAction()
    {
        $this->_loadLayouts();
    }

    /**
     *  Grid ajax action from cms pgea edit page
     */
    public function cmspagesgridAction()
    {
        $this->_loadLayouts();
    }
}
