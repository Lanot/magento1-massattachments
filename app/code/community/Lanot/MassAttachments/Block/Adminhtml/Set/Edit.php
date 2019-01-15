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
 
/**
 * Banner Set admin edit form container
 *
 * @author Lanot
 */
class Lanot_MassAttachments_Block_Adminhtml_Set_Edit
	extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'id';        
        $this->_blockGroup = 'lanot_massattachments';
        $this->_controller = 'adminhtml_set';

        parent::__construct();

        //check permissions
        if ($this->_getAclHelper()->isActionAllowed('manage_set/save')) {
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);
            
            
            $this->_formScripts[] = "            
            	function saveAndContinueEdit(){
            		editForm.submit($('edit_form').action+'back/edit/');
            	}
            ";                        
        } else {
            $this->_removeButton('save');
        }

        if ($this->_getAclHelper()->isActionAllowed('manage_set/delete')) {
            $this->_updateButton('delete', 'label', $this->_getHelper()->__('Delete Attachment Set Item'));
        } else {
            $this->_removeButton('delete');
        }
    }

    public function getHeaderText()
    {
    	$header = $this->_getHelper()->__('New Attachment Set Item');
        $model = $this->_getHelper()->getSetItemInstance();
        
        if ($model->getId()) {
        	$title = $this->escapeHtml($model->getTitle());
            $header = $this->_getHelper()->__("Edit Attachment Set Item '%s'", $title);
        }        
        return $header;
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
