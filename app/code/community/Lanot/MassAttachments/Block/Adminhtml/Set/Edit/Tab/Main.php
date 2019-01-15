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
 * Set admin edit form main tab block
 *
 * @author Lanot
 */
class Lanot_MassAttachments_Block_Adminhtml_Set_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        /* @var $model Lanot_MassAttachments_Model_Set */
        $model = $this->_getHelper()->getSetItemInstance();
        $isElementDisabled = !$this->_getAclHelper()->isActionAllowed('manage_set/save');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('set_main_');

        //--------------------------------------------------------------//
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->_getHelper()->__('Attachment Set Info')
        ));

        if ($model->getId()) {
            $fieldset->addField('set_id', 'hidden', array(
                'name' => 'id',
            ));
        }

        //Add main elements to the set
        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => $this->_getHelper()->__('Title'),
            'title' => $this->_getHelper()->__('Title'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'     => 'description',
            'label'    => $this->_getHelper()->__('Description'),
            'title'    => $this->_getHelper()->__('Description'),
            'required' => false,
            'disabled' => $isElementDisabled,
            'style'    => 'height: 100px',
        ));
        //--------------------------------------------------------------//

        $fieldset = $form->addFieldset('auditory_fieldset', array(
            'legend' => $this->_getHelper()->__('Target Auditory')
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('website_ids', 'multiselect', array(
                'name'      => 'website_ids[]',
                'label'     => $this->_getHelper()->__('Websites'),
                'title'     => $this->_getHelper()->__('Websites'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_config_source_website')->toOptionArray(),
            ));
        }
        else {
            $fieldset->addField('website_ids', 'hidden', array(
                'name'      => 'website_ids[]',
                'value'     => Mage::app()->getStore(true)->getWebsiteId()
            ));
            $model->setWebsiteIds(Mage::app()->getStore(true)->getWebsiteId());
        }

        $customerGroups = Mage::getResourceModel('customer/group_collection')->load()->toOptionArray();
        $found = false;
        foreach ($customerGroups as $group) {
            if ($group['value']==0) {
                $found = true;
            }
        }
        if (!$found) {
            array_unshift($customerGroups, array('value' => 0, 'label' => $this->_getHelper()->__('NOT LOGGED IN')));
        }

        $fieldset->addField('customer_group_ids', 'multiselect', array(
            'name'      => 'customer_group_ids[]',
            'label'     => $this->_getHelper()->__('Customer Groups'),
            'title'     => $this->_getHelper()->__('Customer Groups'),
            'required'  => true,
            'values'    => $customerGroups,
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('from_date', 'date', array(
            'name'   => 'from_date',
            'label'  => $this->_getHelper()->__('From Date'),
            'title'  => $this->_getHelper()->__('From Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));
        $fieldset->addField('to_date', 'date', array(
            'name'   => 'to_date',
            'label'  => $this->_getHelper()->__('To Date'),
            'title'  => $this->_getHelper()->__('To Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));
        //--------------------------------------------------------------//

        $form->setValues($model->getData());

        Mage::dispatchEvent('adminhtml_lanot_attachments_set_edit_tab_main_prepare_form', array('form' => $form));

        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return $this->_getHelper()->__('Info and Target Auditory');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
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
