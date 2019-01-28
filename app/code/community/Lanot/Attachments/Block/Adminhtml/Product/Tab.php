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
 
class Lanot_Attachments_Block_Adminhtml_Product_Tab
    extends Lanot_Attachments_Block_Adminhtml_Tab_Abstract
{
	//protected $_type_id = 'catalog_product';	
	public function getEntity()
	{
		return Mage::registry('current_product');		
	}

    public function getCollection()
    {
        if (null === $this->_collection && $this->getEntity()) {
            $this->_collection = $this->_initCollection()->useProduct($this->getEntity());
            $this->_collection->addOrder('sort_order', Varien_Data_Collection::SORT_ORDER_ASC);
        }
        return parent::getCollection();
    }
}