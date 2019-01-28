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

class Lanot_MassAttachments_Block_Product_View
    extends Lanot_MassAttachments_Block_Abstract
{
    protected $_entity_type = 'catalog_product';

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getEntity()
    {
        return Mage::registry('current_product');
    }

    /**
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection|null
     */
    public function getCollection()
    {
        if (null === $this->_collection && $this->getEntity()->getId()) {
            $this->_collection = $this->_initCollection()
                ->useProduct($this->getEntity())
                ->addFilters(
                    $this->_getHelper()->getWebsiteId(),
                    $this->_getHelper()->getCustomerGroupId(),
                    time()
                );
        }
        return $this->_collection;
    }
}
