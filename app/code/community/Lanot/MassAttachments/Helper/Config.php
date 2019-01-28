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

class Lanot_MassAttachments_Helper_Config extends Lanot_Attachments_Helper_Config
{
    /**
     * @return string
     */
    public function getCacheTag($type)
    {
        if ($type == 'catalog_product') {
            return Mage_Catalog_Model_Product::CACHE_TAG;
        } elseif ($type == 'catalog_category') {
            return Mage_Catalog_Model_Category::CACHE_TAG;
        } elseif ($type == 'cms_page') {
            return Mage_Cms_Model_Page::CACHE_TAG;
        }
        return 'attachments';
    }

    /**
     * @param $type
     * @param $id
     * @param $storeId
     * @param $wId
     * @param $gId
     * @param $date
     * @return string
     */
    public function getAdvancedCacheKey($type, $id, $sId, $wId, $gId, $date)
    {
        return $this->getCacheKey($type, $id, $sId) . '_w'. $wId . '_cg'. $gId . 'd' . $date;
    }
}
