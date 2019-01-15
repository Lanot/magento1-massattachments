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
 * @package     Lanot_Attachments
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

abstract class Lanot_Attachments_Block_Adminhtml_Abstract
    extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_helper = null;
    protected $_block = null;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('lanot/attachments/edit.phtml');
        $this->_helper = Mage::helper('lanot_attachments');
    }

    protected function _toHtml()
    {
        $uploadsBlock = $this->getLayout()
            ->createBlock($this->_block)
            ->setId('attachmentsInfo');

        $this->setChild('lanot.attachments.list', $uploadsBlock);

        return parent::_toHtml();
    }

    public function getTabLabel()
    {
        return $this->_helper->__('Attachments');
    }

    public function getTabTitle()
    {
        return $this->_helper->__('Attachments');
    }

    public function canShowTab()
    {
        return $this->_isAllowed();
    }

    public function isHidden()
    {
        return false;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::helper('lanot_attachments/admin')->isActionAllowed('manage_attachments');
    }
}
