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

class Lanot_MassAttachments_Model_Attachments
    extends Lanot_Attachments_Model_Attachments
{
    protected function _construct()
    {
        $this->_init('lanot_massattachments/attachments');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param bool $onlyMass
     * @return array
     */
    public function getSelectedAttachmentIdsToProduct($product, $onlyMass = true)
    {
        return $this->getResource()->getSelectedAttachmentIdsToProduct($product, $onlyMass);
    }

    /*
     * @param Mage_Catalog_Model_Category $category
     * @param bool $onlyMass
     * @return array
     */
    public function getSelectedAttachmentIdsToCategory($category, $onlyMass = true)
    {
        return $this->getResource()->getSelectedAttachmentIdsToCategory($category, $onlyMass);
    }

    /*
     * @param Mage_Cms_Model_Page $cmspage
     * @param bool $onlyMass
     * @return array
     */
    public function getSelectedAttachmentIdsToCmsPage($cmspage, $onlyMass = true)
    {
        return $this->getResource()->getSelectedAttachmentIdsToCmsPage($cmspage, $onlyMass);
    }
}
