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

class Lanot_MassAttachments_Model_Mysql4_Attachments
    extends Lanot_Attachments_Model_Mysql4_Attachments
{
    const SECONDS_IN_DAY = 86400;

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
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    public function saveRelations(Lanot_Attachments_Model_Attachments $object)
    {
        parent::saveRelations($object);

        $this->_saveRelationsToCategories($object);
        $this->_saveRelationsToPage($object);
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _saveRelationsToCategories(Lanot_Attachments_Model_Attachments $object)
    {
        if (null === $object->getCategoryId()) {
            return $this;
        }

        $newCategories = is_array($object->getCategoryId()) ? $object->getCategoryId() : array($object->getCategoryId());

        $this->_deleteCategoriesToAttachment($object);
        if (!empty($newCategories)) {
            $this->_insertCategoriesToAttachment($newCategories, $object);
        }
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _saveRelationsToPage(Lanot_Attachments_Model_Attachments $object)
    {
        if (null === $object->getPageId()) {
            return $this;
        }

        $newPages = is_array($object->getPageId()) ? $object->getPageId() : array($object->getPageId());

        $this->_deletePagesToAttachment($object);
        if (!empty($newPages)) {
            $this->_insertPagesToAttachment($newPages, $object);
        }
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    public function getSelectedProducts(Lanot_Attachments_Model_Attachments $object)
    {
        return $this->getReadConnection()->fetchCol($this->_getSelectedProductsSelect($object)->distinct(true));
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    public function getSelectedCategories(Lanot_Attachments_Model_Attachments $object)
    {
        return $this->getReadConnection()->fetchCol($this->_getSelectedCategoriesSelect($object)->distinct(true));
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Varien_Db_Select
     */
    protected function _getSelectedCategoriesSelect(Lanot_Attachments_Model_Attachments $object)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->_getCategoriesTable(), array('category_id'))
            ->where('attachment_id = ?', $object->getId());
        return $select;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    public function getSelectedPages(Lanot_Attachments_Model_Attachments $object)
    {
        return $this->getReadConnection()->fetchCol($this->_getSelectedPagesSelect($object)->distinct(true));
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Varien_Db_Select
     */
    protected function _getSelectedPagesSelect(Lanot_Attachments_Model_Attachments $object)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->_getCmsPageTable(), array('page_id'))
            ->where('attachment_id = ?', $object->getId());
        return $select;
    }

    /**
     * @param array $categoryIds
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _insertCategoriesToAttachment(array $categoryIds, Lanot_Attachments_Model_Attachments $object)
    {
        $data = $this->_prepareInsertData('category_id', $categoryIds, $object);
        $this->_getWriteAdapter()->insertArray($this->_getCategoriesTable(), array_keys(current($data)), $data);
        return $this;
    }

    /**
     * @param array $pageIds
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _insertPagesToAttachment(array $pageIds, Lanot_Attachments_Model_Attachments $object)
    {
        $data = $this->_prepareInsertData('page_id', $pageIds, $object);
        $this->_getWriteAdapter()->insertArray($this->_getCmsPageTable(), array_keys(current($data)), $data);
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _deleteCategoriesToAttachment(Lanot_Attachments_Model_Attachments $object)
    {
        $where = $this->_getWriteAdapter()->quoteInto('attachment_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->_getCategoriesTable(), $where);
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _deletePagesToAttachment(Lanot_Attachments_Model_Attachments $object)
    {
        $where = $this->_getWriteAdapter()->quoteInto('attachment_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->_getCmsPageTable(), $where);
        return $this;
    }

    /**
     * @param array $productIds
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _insertProductsToAttachment(array $productIds, Lanot_Attachments_Model_Attachments $object)
    {
        if ($object->getSetId()) {
            $data = $this->_prepareInsertData('product_id', $productIds, $object);
        } else {
            $data = parent::_prepareInsertDataByProducts($productIds, $object);
        }
        $this->_getWriteAdapter()->insertArray($this->_getProductsTable(), array_keys(current($data)), $data);
        return $this;
    }

    /**
     * @param $key
     * @param array $entityIds
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    protected function _prepareInsertData($key, array $entityIds, Lanot_Attachments_Model_Attachments $object)
    {
        $websiteIds = $object->getWebsiteIds();
        $customerGroupIds = $object->getCustomerGroupIds();
        $fromTime = strtotime($object->getFromDate());
        $fromTime = $fromTime ? $fromTime : 0;
        $toTime = strtotime($object->getToDate());
        $toTime = $toTime ? ($toTime + self::SECONDS_IN_DAY - 1) : 0;

        $rows = array();
        foreach($entityIds as $eId) {
            foreach ($websiteIds as $wId) {
                foreach ($customerGroupIds as $cgId) {
                    $rows[] = $this->_getRowData($key, $eId, $object->getId(), $wId, $cgId, $fromTime, $toTime);
                }
            }
        }
        return $rows;
    }

    /**
     * @param $key
     * @param $entityId
     * @param $attachmentId
     * @param $websiteId
     * @param $groupId
     * @param $fromTime
     * @param $toTime
     * @return array
     */
    protected function _getRowData($key, $entityId, $attachmentId, $websiteId, $groupId, $fromTime, $toTime)
    {
        return array(
            'attachment_id'     => $attachmentId,
            $key                => $entityId,
            'website_id'        => $websiteId,
            'customer_group_id' => $groupId,
            'from_time'         => $fromTime,
            'to_time'           => $toTime,
        );
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param bool $onlyMass
     * @return array
     */
    public function getSelectedAttachmentIdsToProduct($product, $onlyMass = true)
    {
        return $this->getReadConnection()->fetchCol(
            $this->getSelectedAttachmentIdsSelect($product, $this->_getProductsTable(), 'product_id', $onlyMass)
        );
    }

    /**
     * @param Mage_Catalog_Model_Category $category
     * @param bool $onlyMass
     * @return array
     */
    public function getSelectedAttachmentIdsToCategory($category, $onlyMass = true)
    {
        return $this->getReadConnection()->fetchCol(
            $this->getSelectedAttachmentIdsSelect($category, $this->_getCategoriesTable(), 'category_id', $onlyMass)
        );
    }

    /**
     * @param Mage_Cms_Model_Page $cmspage
     * @param bool $onlyMass
     * @return array
     */
    public function getSelectedAttachmentIdsToCmsPage($cmspage, $onlyMass = true)
    {
        return $this->getReadConnection()->fetchCol(
            $this->getSelectedAttachmentIdsSelect($cmspage, $this->_getCmsPageTable(), 'page_id', $onlyMass)
        );
    }

    /**
     * @param Mage_Core_Model_Abstract $entity
     * @param $table
     * @param $idField
     * @param bool $onlyMass
     * @return Varien_Db_Select
     */
    public function getSelectedAttachmentIdsSelect($entity, $table, $idField, $onlyMass = true)
    {
        $select = $this->getReadConnection()
            ->select()
            ->distinct(true)
            ->from(array('main_table' => $table), array('attachment_id'))
            ->where("main_table.{$idField} = ?", $entity->getId())
            ->joinInner(array('att' => $this->getMainTable()),
                'att.attachment_id=main_table.attachment_id',
                array()
            );

        if ($onlyMass) {
            $select->where('att.set_id IS NOT NULL');
        } else {
            $select->where('att.set_id IS NULL');
        }
        return $select;
    }
}
