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

abstract class Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Grid_Abstract
    extends Lanot_MassAttachments_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId = 'massProductsAttachmentsGrid';
    protected $_productIdField = 'attachment_id';
    protected $_formFieldName = 'attachment_id';

    protected $_columnPrefix = 'attachments_';
    protected $_checkboxFieldName = 'attachments_in_selected';

    protected $_url = null;

    /**
     * @return Lanot_MassAttachments_Model_Attachments
     */
    protected function _getAttachmentsModel()
    {
        return Mage::getSingleton('lanot_massattachments/attachments');
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl($this->_url, array('_current' => true, '_secure'=>true));
    }

    /**
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    protected function _initCollection()
    {
        $collection = $this->_getAttachmentsModel()->getCollection();
        $collection->addTitleToFields($this->_getStoreId())
            ->addLinkToFields($this->_getStoreId())
            ->addSetToFields()
            ->addFieldToFilter('main_table.set_id', array('notnull' => 1))
            ->distinct(true);
        return $collection;
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn($this->_productIdField, array(
            'header'    => $this->_getHelper()->__('ID'),
            'sortable'  => true,
            'index'     => $this->_productIdField,
            'width'     => 60,
        ));

        $this->addColumn('title', array(
            'header'    => $this->_getHelper()->__('Title'),
            'index'     => 'title'
        ));

        $this->addColumn('set_title', array(
            'header'    => $this->_getHelper()->__('Attachments Set'),
            'index'     => 'set_title',
            'width'     => 150,
            'frame_callback' => array($this, 'prepareSetUrl')
        ));

        $this->addColumn('file_url', array(
            'header'    => $this->_getHelper()->__('Download'),
            'index'     => 'download',
            'sortable'  => false,
            'filter'    => false,
            'width'     => 150,
            'frame_callback' => array($this, 'prepareFileUrl')
        ));

        $this->addColumn('type', array(
            'header'    => $this->_getHelper()->__('Type'),
            'index'     => 'type',
            'filter'    => false,
            'width'     => 50,
            'frame_callback' => array($this, 'prepareType')
        ));

        $this->addColumn('updated_at', array(
            'header'  => $this->_getHelper()->__('Updated'),
            'type'    => 'date',
            'index'   => 'updated_at',
            'width'   => 150,
        ));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }

    /**
     * @param $value
     * @param Lanot_MassAttachments_Model_Attachments $attachment
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param $isExport
     * @return string
     */
    public function prepareFileUrl($value, $attachment, $column, $isExport)
    {
        if ($attachment->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_URL) {
            $name = $attachment->getUrl();
        } elseif ($attachment->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_FILE) {
            $name = basename($attachment->getFile());
        }

        $url = $attachment->getAttachmentUrl();
        return sprintf('<a href="%s" target="_blank">%s</a>', $url, $name);
    }

    /**
     * @param $value
     * @param Lanot_MassAttachments_Model_Attachments $attachment
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param $isExport
     * @return string
     */
    public function prepareSetUrl($value, $attachment, $column, $isExport)
    {
        $url = $this->getUrl('lanotmassattachmnets/adminhtml_set/edit', array('id' => $attachment->getSetId()));
        return sprintf('<a href="%s" target="_blank">%s</a>', $url, $value);
    }

    /**
     * @param $value
     * @param Lanot_MassAttachments_Model_Attachments $attachment
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param $isExport
     * @return string
     */
    public function prepareType($value, $attachment, $column, $isExport)
    {
        return ucfirst($value);
    }
}
