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
