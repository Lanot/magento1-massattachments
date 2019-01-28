<?php
/**
 * Private Entrepreneur Anatolii Lehkyi (aka Lanot)
 *
 * @category    Lanot
 * @package     Lanot_Attachments
 * @copyright   Copyright (c) 2010 Anatolii Lehkyi
 * @license     http://opensource.org/licenses/osl-3.0.php
 * @link        http://www.lanot.biz/
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
