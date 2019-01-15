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
 * @package     Lanot_Attachments
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lanot_Attachments_Model_Mysql4_Attachments
    extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('lanot_attachments/entity', 'attachment_id');
    }

    /**
     * @return string
     */
    protected function _getEntityTable()
    {
        return $this->getTable('lanot_attachments/entity');
    }

    /**
     * @return string
     */
    protected function _getFilesTable()
    {
        return $this->getTable('lanot_attachments/links');
    }

    /**
     * @return string
     */
    protected function _getTitlesTable()
    {
        return $this->getTable('lanot_attachments/titles');
    }

    /**
     * @return string
     */
    protected function _getProductsTable()
    {
        return $this->getTable('lanot_attachments/products');
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        /** @var $readAdapter Varien_Db_Adapter_Interface */
        $readAdapter = $this->_getReadAdapter();
        $bind = array(
            ':attachment_id' => (int)$object->getId(),
        );

        $select = $readAdapter->select()
            ->from(array('main_table' => $this->_getEntityTable()), array('attachment_id'))
            ->where('main_table.attachment_id = :attachment_id', $bind)
            ->joinLeft(array('l' => $this->_getFilesTable()),
                'l.attachment_id=main_table.attachment_id AND l.store_id = 0',
                array('default_type' => 'type', 'default_file' => 'file', 'default_url' => 'url')
            )
            ->joinLeft(array('lt' => $this->_getFilesTable()),
                'lt.attachment_id=main_table.attachment_id AND lt.store_id = ' . (int) $object->getStoreId(),
                array(
                    'store_type' => 'type', 'type' => $this->getIfNullSql('lt.type', 'l.type'),
                    'store_url'  => 'url',  'url' => $this->getIfNullSql('lt.url', 'l.url'),
                    'store_file' => 'file', 'file' => $this->getIfNullSql('lt.file', 'l.file'),
                )
            );

        //update data
        $row = $readAdapter->fetchRow($select, $bind);
        $object->addData($row);
        return parent::_afterLoad($object);
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    public function saveTitles(Lanot_Attachments_Model_Attachments $object)
    {
        $writeAdapter = $this->_getWriteAdapter();
        $bind = array(
            ':attachment_id' => (int)$object->getId(),
            ':store_id' => (int)$object->getStoreId(),
        );

        $select = $writeAdapter->select()
            ->from($this->_getTitlesTable())
            ->where('attachment_id=:attachment_id AND store_id=:store_id');

        //update data
        if ($writeAdapter->fetchOne($select, $bind)) {
            $where = array(
                'attachment_id = ?' => (int)$object->getId(),
                'store_id = ?' => (int)$object->getStoreId(),
            );
            if ($object->getUseDefaultTitle()) {
                $writeAdapter->delete($this->_getTitlesTable(), $where);
            } else {
                $writeAdapter->update($this->_getTitlesTable(), array('title' => $object->getTitle()), $where);
            }
        } else if (!$object->getUseDefaultTitle()) {
            $writeAdapter->insert(
                $this->_getTitlesTable(),
                array(
                    'attachment_id' => (int)$object->getId(),
                    'store_id' => (int)$object->getStoreId(),
                    'title' => $object->getTitle(),
                )
            );
        }
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    public function saveLinks(Lanot_Attachments_Model_Attachments $object)
    {
        $writeAdapter = $this->_getWriteAdapter();
        $bind = array(
            ':attachment_id' => (int)$object->getId(),
            ':store_id' => (int)$object->getStoreId(),
        );

        $select = $writeAdapter->select()
            ->from($this->_getFilesTable())
            ->where('attachment_id=:attachment_id AND store_id=:store_id');

        $data = $this->_prepareRow($object);
        //update data
        if ($writeAdapter->fetchOne($select, $bind)) {
            $where = array(
                'attachment_id = ?' => (int)$object->getId(),
                'store_id = ?' => (int)$object->getStoreId(),
            );
            if ($object->getUseDefaultLink()) {
                $writeAdapter->delete($this->_getFilesTable(), $where);
            } else {
                $writeAdapter->update($this->_getFilesTable(), $data, $where);
            }
        } else if (!$object->getUseDefaultLink()) {
            $data['attachment_id'] = (int)$object->getId();
            $data['store_id'] = (int)$object->getStoreId();
            $writeAdapter->insert($this->_getFilesTable(), $data);
        }
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    protected function _prepareRow(Lanot_Attachments_Model_Attachments $object)
    {
        return array(
            'url' => $object->getUrl(),
            'file' => $object->getFile(),
            'type' => $object->getType(),
        );
    }

    /**
     * @param mixed $attachmentIds
     * @return array
     */
    public function getDeleteFiles($attachmentIds)
    {
        if (empty($attachmentIds)) {
            return array();
        }
        if (!is_array($attachmentIds)) {
            $attachmentIds = array($attachmentIds);
        }

        $writeAdapter = $this->_getWriteAdapter();
        $bind = array(':type' => Lanot_Attachments_Helper_Download::LINK_TYPE_FILE);
        $select = $writeAdapter->select()
            ->distinct()
            ->from(array('l' => $this->_getFilesTable()), array('file'))
            ->where('l.`attachment_id` IN (?) AND l.`type`=:type', $attachmentIds);
        return $writeAdapter->fetchCol($select, $bind);
    }

    /**
     * Returns valid IFNULL expression
     *
     * @param Zend_Db_Expr|Zend_Db_Select|string $expression
     * @param string $value OPTIONAL. Applies when $expression is NULL
     * @return Zend_Db_Expr
     */
    public function getIfNullSql($expression, $value = 0)
    {
        if ($expression instanceof Zend_Db_Expr || $expression instanceof Zend_Db_Select) {
            $expression = sprintf("IFNULL((%s), %s)", $expression, $value);
        } else {
            $expression = sprintf("IFNULL(%s, %s)", $expression, $value);
        }
        return new Zend_Db_Expr($expression);
    }


    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    public function saveRelations(Lanot_Attachments_Model_Attachments $object)
    {
        $this->_saveRelationsToProducts($object);
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _saveRelationsToProducts(Lanot_Attachments_Model_Attachments $object)
    {
        if (null === $object->getProductId()) {
            return $this;
        }

        $newProducts = is_array($object->getProductId()) ? $object->getProductId() : array($object->getProductId());
        $this->_deleteProductsToAttachment($object);
        if (!empty($newProducts)) {
            $this->_insertProductsToAttachment($newProducts, $object);
        }
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    public function getSelectedProducts(Lanot_Attachments_Model_Attachments $object)
    {
        return $this->getReadConnection()->fetchCol($this->_getSelectedProductsSelect($object));
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Varien_Db_Select
     */
    protected function _getSelectedProductsSelect(Lanot_Attachments_Model_Attachments $object)
    {
        $select = $this->getReadConnection()
            ->select()
            ->from($this->_getProductsTable(), array('product_id'))
            ->where('attachment_id = ?', $object->getId());
        return $select;
    }

    /**
     * @param array $productIds
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _insertProductsToAttachment(array $productIds, Lanot_Attachments_Model_Attachments $object)
    {
        $data = $this->_prepareInsertDataByProducts($productIds, $object);
        $this->_getWriteAdapter()->insertArray($this->_getProductsTable(), array_keys(current($data)), $data);
        return $this;
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $object
     * @return Lanot_Attachments_Model_Mysql4_Attachments
     */
    protected function _deleteProductsToAttachment(Lanot_Attachments_Model_Attachments $object)
    {
        $where = $this->_getWriteAdapter()->quoteInto('attachment_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->_getProductsTable(), $where);
        return $this;
    }

    /**
     * @param array $productIds
     * @param Lanot_Attachments_Model_Attachments $object
     * @return array
     */
    protected function _prepareInsertDataByProducts(array $productIds, Lanot_Attachments_Model_Attachments $object)
    {
        $data = array();
        foreach($productIds as $productId) {
            $data[] = array('attachment_id' => $object->getId(), 'product_id' => $productId);
        }
        return $data;
    }
}
