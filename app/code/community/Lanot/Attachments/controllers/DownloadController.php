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

class Lanot_Attachments_DownloadController
    extends Mage_Core_Controller_Front_Action
{
    const HTTP_STATUS_OK = 200;

    /**
     * Download file action
     *
     */
    public function fileAction()
    {
        $attachmentId = $this->getRequest()->getParam('id', 0);
        $storeId = $this->getRequest()->getParam('store', 0);
        $attachment = $this->_getItemModel()->loadByStore($attachmentId, $storeId);

        if ($attachment->getId()) {
            $resource = $this->_getResource($attachment);
            try {
                if (($attachment->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_URL) &&
                    $this->_getConfigHelper()->canUseUrlRedirect()
                ) {
                    $this->_redirectUrl($resource);
                    $this->getResponse()->sendResponse();
                } else { //output file contents
                    $this->_processDownload($resource, $attachment->getType());
                }
                exit(0);
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($this->_getHelper()->__('An error occurred while getting requested content. Please contact the store owner.'));
                Mage::logException($e);
            }
        }
        return $this->_redirectReferer();
    }

    /**
     * @param Lanot_Attachments_Model_Attachments $attachment
     * @return null|string
     */
    protected function _getResource(Lanot_Attachments_Model_Attachments $attachment)
    {
        if ($attachment->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_URL) {
            return $attachment->getUrl();
        } elseif ($attachment->getType() == Lanot_Attachments_Helper_Download::LINK_TYPE_FILE) {
            return $this->_getFileHelper()->getFilePath(
                $this->_getConfigHelper()->getBasePath(), $attachment->getFile()
            );
        }
        return null;
    }

    /**
     * @param $resource
     * @param $resourceType
     */
    protected function _processDownload($resource, $resourceType)
    {
        /* @var $helper Lanot_Attachments_Helper_Download */
        $helper = $this->_getDownloadHelper();

        $helper->setResource($resource, $resourceType);

        $fileName = $helper->getFilename();
        $contentType = $helper->getContentType();

        $responce = $this->getResponse();
        $responce
            ->setHttpResponseCode(self::HTTP_STATUS_OK)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        if ($fileSize = $helper->getFilesize()) {
            $responce->setHeader('Content-Length', $fileSize);
        }

        if ($contentDisposition = $helper->getContentDisposition()) {
            $responce->setHeader('Content-Disposition', $contentDisposition . '; filename=' . $fileName);
        }

        $responce->clearBody();
        $responce->sendHeaders();

        $helper->output();
    }

    /**
     * Return core session object
     *
     * @return Mage_Core_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }


    /**
     * @return Lanot_Attachments_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_attachments');
    }

    /**
     * @return Lanot_Attachments_Helper_Config
     */
    protected function _getConfigHelper()
    {
        return Mage::helper('lanot_attachments/config');
    }

    /**
     * @return Lanot_Attachments_Helper_File
     */
    protected function _getFileHelper()
    {
        return Mage::helper('lanot_attachments/file');
    }

    /**
     * @return Lanot_Attachments_Helper_Download
     */
    protected function _getDownloadHelper()
    {
        return Mage::helper('lanot_attachments/download');
    }

    /**
     * @return Lanot_Attachments_Model_Attachments
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_attachments/attachments');
    }
}
