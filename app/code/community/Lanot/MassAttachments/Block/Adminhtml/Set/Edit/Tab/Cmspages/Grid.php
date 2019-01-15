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

class Lanot_MassAttachments_Block_Adminhtml_Set_Edit_Tab_Cmspages_Grid
    extends Lanot_MassAttachments_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId = 'massAttachmentsCmsPagesGrid';
    protected $_entityIdField = 'page_id';
    protected $_itemParam = 'page_id';
    protected $_formFieldName = 'cmspages';

    protected $_columnPrefix = 'cmspages_';
    protected $_checkboxFieldName = 'cmspages_in_selected';

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/cmspagesgridonly', array('_current' => true, '_secure'=>true));
    }

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

        $this->_selectedLinks = array();
        $setId = $this->getRequest()->getParam('set_id');
        if ($setId) {
            $set = $this->_getItemModel()->load($setId);
            $this->_selectedLinks = $set->getSelectedCmsPages();
        }
        return $this->_selectedLinks;
    }

    /**
     * @return Mage_Cms_Model_Resource_Page_Collection
     */
    protected function _getCollection()
    {
        return Mage::getModel('cms/page')->getCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        if ($this->_isTabGrid) {
            $this->addColumn('in_selected', array(
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_selected',
                'values' => $this->_getSelectedLinks(),
                'align' => 'center',
                'index' => $this->_entityIdField,
            ));
        }

        $this->addColumn($this->_entityIdField, array(
            'header'    => Mage::helper('cms')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => $this->_entityIdField
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('cms')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('cms')->__('URL Key'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));

        $this->addColumn('root_template', array(
            'header'    => Mage::helper('cms')->__('Layout'),
            'index'     => 'root_template',
            'type'      => 'options',
            'options'   => Mage::getSingleton('page/source_layout')->getOptions(),
        ));

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => Mage::getSingleton('cms/page')->getAvailableStatuses()
        ));

        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('cms')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header'    => Mage::helper('cms')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }
}
