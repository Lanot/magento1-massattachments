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

class Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
    extends Lanot_Attachments_Model_Mysql4_Attachments_Collection
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('lanot_massattachments/attachments');
    }

    /**
     * @return string
     */
    protected function _getProductsTable()
    {
        return $this->getTable('lanot_massattachments/products');
    }

    /**
     * @return string
     */
    protected function _getCategoriesTable()
    {
        return $this->getTable('lanot_massattachments/categories');
    }

    /**
     * @return string
     */
    protected function _getCmsPageTable()
    {
        return $this->getTable('lanot_massattachments/cms_pages');
    }

    /**
     * @return string
     */
    protected function _getSetTable()
    {
        return $this->getTable('lanot_massattachments/set');
    }

    /**
     * @param Lanot_MassAttachments_Model_Set $set
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    public function useSet(Lanot_MassAttachments_Model_Set $set)
    {
        $this->addFieldToFilter('set_id', $set->getId());
        return $this;
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    public function useCategory(Mage_Catalog_Model_Category $category)
    {
        $this->getSelect()->joinInner(array('addtab' => $this->_getCategoriesTable()),
            'addtab.attachment_id=main_table.attachment_id AND addtab.category_id = ' . (int) $category->getId(),
            array()
        );
        return $this;
    }

    /**
     * @param Mage_Cms_Model_Page $cmsPage
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    public function useCmsPage(Mage_Cms_Model_Page $cmsPage)
    {
        $this->getSelect()->joinInner(array('addtab' => $this->_getCmsPageTable()),
            'addtab.attachment_id=main_table.attachment_id AND addtab.page_id = ' . (int) $cmsPage->getId(),
            array()
        );
        return $this;
    }

    /**
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    public function addSetToFields()
    {
        $this->getSelect()->joinInner(array('set' => $this->_getSetTable()),
            'set.set_id=main_table.set_id',
            array('set_title' => 'title')
        );
        return $this;
    }

    /**
     * @param $websiteId
     * @param $groupId
     * @param $time
     * @return Lanot_MassAttachments_Model_Mysql4_Attachments_Collection
     */
    public function addFilters($websiteId, $groupId, $time)
    {
        $conn = $this->getConnection();

        $cond  = $conn->quoteInto(' (addtab.website_id = ? OR website_id IS NULL)', $websiteId);
        $cond .= $conn->quoteInto(' AND (addtab.customer_group_id = ? OR addtab.customer_group_id IS NULL)', $groupId);
        $cond .= $conn->quoteInto(' AND (addtab.from_time <= ? OR addtab.from_time = 0)', $time);
        $cond .= $conn->quoteInto(' AND (addtab.to_time >= ? OR addtab.to_time = 0)', $time);

        $this->getSelect()->where($cond);
        return $this;
    }
}
