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
 * Categories List admin grid container
 *
 * @author Lanot
 */
class Lanot_MassAttachments_Block_Adminhtml_Set
	extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'lanot_massattachments';
        $this->_controller = 'adminhtml_Set';
        $this->_headerText = $this->_getHelper()->__('Manage Attachment Sets');

        parent::__construct();

        if ($this->_getAclHelper()->isActionAllowed('manage_set/save')) {
            $this->_updateButton('add', 'label', $this->_getHelper()->__('Add New Attachment Set'));
        } else {
            $this->_removeButton('add');
        }
    }

    /**
     * @return Lanot_MassAttachments_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_massattachments/admin');
    }

    /**
     * @return Lanot_MassAttachments_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_massattachments');
    }
}
