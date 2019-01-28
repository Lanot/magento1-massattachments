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

class Lanot_Attachments_Block_Adminhtml_Product_Edit
extends Lanot_Attachments_Block_Adminhtml_Abstract
{
	protected $_block = 'lanot_attachments/adminhtml_product_tab';
		
	public function canShowTab()
	{
        /* @var $helper Lanot_Attachments_Helper_Admin */
        $helper = Mage::helper('lanot_attachments/admin');
        $isAllowed = $helper->isActionAllowed('manage_attachments');

		$setId = false;
		$product = Mage::registry('current_product');
		
		if ($product) {
			$setId = $product->getAttributeSetId();
		}		
		if (!$setId) { 
			$setId = $this->getRequest()->getParam('set', null);
		}
				
		return ($setId && $isAllowed) ? true : false;
	}	
}