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

class Lanot_MassAttachments_Model_Set extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'lanot_attachments_set';
    protected $_eventObject = 'set';

    protected $_arrayToStringKeys = array(
        'website_ids',
        'customer_group_ids',
        'catalog_product_ids',
        'catalog_category_ids',
        'cms_page_ids',
    );

    protected function _construct()
    {
        $this->_init('lanot_massattachments/set');
    }

    /**
     * @return Lanot_MassAttachments_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_massattachments');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        if ($this->isObjectNew()) {
            $this->setData('created_at', $this->getResource()->formatDate(time()));
        }
        $this->setData('updated_at', $this->getResource()->formatDate(time()));

        if (!$this->getData('from_date')) {
            $this->setData('from_date', null);
        }
        if (!$this->getData('to_date')) {
            $this->setData('to_date', null);
        }

        parent::_beforeSave();

        //convert from array to raw string
        foreach($this->_arrayToStringKeys as $key) {
            if (is_array($this->getData($key))) {
                $this->setData($key, implode(',', $this->getData($key)));
            }
        }
    }

    /**
     * @param $key
     * @return array|mixed
     */
    protected function _getItemAsArray($key)
    {
        $val = $this->getData($key);
        if (!is_null($val) && !is_array($val)) {
            $ids = explode(',', $this->getData($key));
            $this->setData($key, $ids);
            return $ids;
        } elseif (!is_null($val) && is_array($val)) {
            return $val;
        } else {
            return array();
        }
    }

    /**
     * @return array
     */
    public function getSelectedWebsiteIds()
    {
        return $this->_getItemAsArray('website_ids');
    }

    /**
     * @return array
     */
    public function getSelectedCustomerGroupIds()
    {
        return $this->_getItemAsArray('customer_group_ids');
    }

    /**
     * @return array
     */
    public function getSelectedProducts()
    {
        return array_filter($this->_getItemAsArray('catalog_product_ids'));
    }

    /**
     * @return array
     */
    public function getSelectedCategories()
    {
        return array_filter($this->_getItemAsArray('catalog_category_ids'));
    }

    /**
     * @return array
     */
    public function getSelectedCmsPages()
    {
        return array_filter($this->_getItemAsArray('cms_page_ids'));
    }
}
