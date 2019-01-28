<?php
/**
 * Private Entrepreneur Anatolii Lehkyi (aka Lanot)
 *
 * @category    Lanot
 * @package     Lanot_Attachments
 * @copyright   Copyright (c) 2010 Anatolii Lehkyi
 * @license     http://opensource.org/licenses/osl-3.0.php
 * @link        http://www.lanot.biz/
 */

class Lanot_Attachments_Helper_File extends Mage_Downloadable_Helper_File
{
    /**
     * @param $file
     * @return bool
     */
    public function deleteFile($file)
    {
        $filename = $this->getFilePath($this->_getConfigHelper()->getBasePath(), $file);
        $io = new Varien_Io_File();
        if ($io->fileExists($filename)) {
            $io->rm($filename);
        }

        if ($this->_getConfigHelper()->canUseFileStorage()) {
            $this->_getStorageHelper()->deleteFile($filename);
        }
        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    public function canUploadFile($file)
    {
        $file = array_shift($file);
        if (is_array($file) && isset($file['status']) && ($file['status'] == 'new')) {
            return true;
        }

        return false;
    }

    /**
     * @param $item
     * @return string
     */
    public function getFileExtension($item)
    {
        $file = $item->getFile();
        $ext = 'none';
        if ($item->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_FILE
            && !empty($file)
        ) {
            $info = pathinfo($file);
            $ext = trim($info['extension']);
        }
        return $ext;
    }

    /**
     * @param array $files
     * @return $this
     */
    public function deleteFiles(array $files)
    {
        if (!empty($files)) {
            foreach ($files as $file) {
                try {
                    $this->deleteFile($file);
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
        }
        return $this;
    }

    /**
     * @return Lanot_Attachments_Helper_Config
     */
    protected function _getConfigHelper()
    {
        return Mage::helper('lanot_attachments/config');
    }

    /**
     * @return Mage_Core_Helper_File_Storage_Database
     */
    protected function _getStorageHelper()
    {
        return Mage::helper('core/file_storage_database');
    }
}
