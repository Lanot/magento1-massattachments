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

class Lanot_MassAttachments_Block_Category_View
    extends Lanot_MassAttachments_Block_Abstract
{
    protected $_entity_type = 'catalog_category';

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getEntity()
    {
        return Mage::registry('current_category');
    }

    /**
     * @return Lanot_Attachments_Model_Mysql4_Attachments_Collection|null
     */
    public function getCollection()
    {
        if (null === $this->_collection && $this->getEntity()->getId()) {
            $this->_collection = $this->_initCollection()
                ->useCategory($this->getEntity())
                ->addFilters(
                    $this->_getHelper()->getWebsiteId(),
                    $this->_getHelper()->getCustomerGroupId(),
                    time()
                );
        }
        return $this->_collection;
    }
}
