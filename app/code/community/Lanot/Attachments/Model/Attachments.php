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

class Lanot_Attachments_Model_Attachments extends Mage_Core_Model_Abstract
{
    protected $_files = array();
    protected $_storeId = 0;
    protected $_eventPrefix = 'lanot_attachments';
    protected $_eventObject = 'attachments';

    protected function _construct()
    {
        $this->_init('lanot_attachments/attachments');
    }

    /**
     * @param $storeId
     * @return Lanot_Attachments_Model_Attachments
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * @param $attachmentId
     * @param $storeId
     * @return Mage_Core_Model_Abstract
     */
    public function loadByStore($attachmentId, $storeId)
    {
        $this->setStoreId($storeId);
        return $this->load($attachmentId);
    }

    /**
     * @return string
     */
    public function getAttachmentUrl()
    {
        $params = array(
            'id' => $this->getId(),
            'store' => $this->getStoreId() ? $this->getStoreId() : Mage::app()->getStore()->getId(),
        );
        $url = Mage::getUrl('lanotattachments/download/file', $params);

        if ($this->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_FILE &&
            $this->_getConfigHelper()->canShowUrlFilename()
        ) {
            $url .= basename($this->getFile());
        }

        return $url;
    }

    /**
     * @param array $data
     * @return Lanot_Attachments_Model_Attachments
     */
    public function prepareLinkData(array $data)
    {
        if (isset($data['attachment_id']) && empty($data['attachment_id'])) {
            unset($data['attachment_id']);
        }

        $this->setData($data);
        $this->_prepareFileData();
        $this->_prepareLinkData();
        $this->_prepareDeleted();

        return $this;
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _prepareFileData()
    {
        $file = $this->getFile();
        $this->setFile(null);
        if ($this->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_FILE) {
            $this->setFile($this->_upload($this->_getFileFromJson($file)));
        }
        return $this;
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _prepareDeleted()
    {
        $isDel = $this->getData('is_delete');
        if ((($this->getStoreId() == 0) && !$this->getTitle() && !$this->getUrl() && !$this->getFile()) || $isDel) {
            $this->_isDeleted = true;
        }
        return $this;
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _beforeDelete()
    {
        if ($this->getId()) {
            $this->_files = $this->getResource()->getDeleteFiles($this->getId());
        }
        return parent::_beforeDelete();
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _afterDeleteCommit()
    {
        parent::_afterDeleteCommit();
        $this->_getFileHelper()->deleteFiles($this->_files);
        return $this;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        if ($this->isObjectNew()) {
            $this->setData('created_at', $this->getResource()->formatDate(time()));
        }
        $this->setData('updated_at', $this->getResource()->formatDate(time()));
        return parent::_beforeSave();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterSave()
    {
        $this->getResource()
            ->saveTitles($this)
            ->saveLinks($this)
            ->saveRelations($this);
        return parent::_afterSave();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterLoad()
    {
        $this->_prepareLinkData();
        return parent::_afterLoad();
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _prepareLinkData()
    {
        if ($this->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_FILE) {
            $this->setUrl(null);
        } elseif ($this->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_URL) {
            $this->setFile(null);
        }
        return $this;
    }

    /**
     * @param array $file
     * @return string
     */
    protected function _upload(array $file)
    {
        $conf = $this->_getConfigHelper();
        return $this->_getFileHelper()->moveFileFromTmp($conf->getBaseTmpPath(), $conf->getBasePath(), $file);
    }

    /**
     * @return Lanot_Attachments_Helper_File
     */
    protected function _getFileHelper()
    {
        return Mage::helper('lanot_attachments/file');
    }

    /**
     * @return Lanot_Attachments_Helper_Config
     */
    protected function _getConfigHelper()
    {
        return Mage::helper('lanot_attachments/config');
    }

    /**
     * @param $file
     * @return mixed
     */
    protected function _getFileFromJson($file)
    {
        return Mage::helper('core')->jsonDecode($file);
    }
}
