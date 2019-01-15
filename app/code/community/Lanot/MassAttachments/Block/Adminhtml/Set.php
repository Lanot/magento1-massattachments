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
