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
