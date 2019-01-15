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

class Lanot_Attachments_Adminhtml_FilesController
    extends Mage_Adminhtml_Controller_Action
{
    protected $_filesKey = 'lanot_attachments'; //$_FILES array key

    /**
     * Upload file controller action
     */
    public function uploadAction()
    {
        try {
            //upload file
            $tmpPath = $this->_getConfigHelper()->getBaseTmpPath();
            $result = $this->_getUploader($this->_filesKey)->save($tmpPath);
            //wrap result data
            $result['cookie'] = $this->_getCookieData();
            $this->_saveToStorage($result);
        } catch (Exception $e) {
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
            Mage::logException($e);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * @param $key
     * @return Varien_File_Uploader
     */
    protected function _getUploader($key)
    {
        $uploader = new Varien_File_Uploader($key);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $uploader->setAllowCreateFolders(true);
        return $uploader;
    }

    /**
     * @return array
     */
    protected function _getCookieData()
    {
        return array(
            'name' => session_name(),
            'value' => $this->_getSession()->getSessionId(),
            'lifetime' => $this->_getSession()->getCookieLifetime(),
            'path' => $this->_getSession()->getCookiePath(),
            'domain' => $this->_getSession()->getCookieDomain()
        );
    }

    /**
     * @param array $result
     * @return Lanot_Attachments_Adminhtml_FilesController
     */
    protected function _saveToStorage(array $result)
    {
        if (isset($result['file'])) {
            if ($this->_getConfigHelper()->canUseFileStorage()) { //check if class exists
                $filePath = rtrim($this->_getConfigHelper()->getBaseTmpPath(), DS) . DS . ltrim($result['file'], DS);
                $this->_getStorageHelper()->saveFile($filePath);
            }
        }
        return $this;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_getAclHelper()->isActionAllowed('manage_attachments');
    }

    /**
     * @return Lanot_Attachments_Helper_Config
     */
    protected function _getConfigHelper()
    {
        return Mage::helper('lanot_attachments/config');
    }

    /**
     * @return Lanot_Attachments_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_attachments/admin');
    }

    /**
     * @return Mage_Core_Helper_File_Storage_Database
     */
    protected function _getStorageHelper()
    {
        return Mage::helper('core/file_storage_database');
    }
}
