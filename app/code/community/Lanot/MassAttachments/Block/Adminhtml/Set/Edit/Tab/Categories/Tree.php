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

class Lanot_MassAttachments_Block_Adminhtml_Set_Edit_Tab_Categories_Tree
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    public function __construct()
    {
        parent::__construct();
        $this->_withProductCount = false;
    }

    /**
     * Overrides parent method
     *
     * @return array
     */
    protected function getCategoryIds()
    {
        return $this->getSelectedLinks();
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
            $this->_selectedLinks = $set->getSelectedCategories();
        }
        return $this->_selectedLinks;
    }

    /**
     * @return Lanot_MassAttachments_Model_Set
     */
    protected function _getItemModel()
    {
        return Mage::getSingleton('lanot_massattachments/set');
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
     * Overrides parent method
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return !$this->_getAclHelper()->isActionAllowed('manage_set/assign');
    }

    /**
     * Overrides parent method
     *
     * @param null $expanded
     * @return string
     */
    public function getLoadTreeUrl($expanded=null)
    {
        return $this->getUrl('*/*/categoriesJson', array('_current' => true, '_secure' => true));
    }
}
