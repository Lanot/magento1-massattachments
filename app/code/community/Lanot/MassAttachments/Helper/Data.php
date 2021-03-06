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

class Lanot_MassAttachments_Helper_Data extends Lanot_Attachments_Helper_Data
{
    /**
     * @return Lanot_MassAttachments_Model_Set
     */
    public function getSetItemInstance()
    {
        return Mage::registry('lanot.attachments.set.item');
    }

    /**
     * @return Lanot_MassAttachments_Model_Attachments
     */
    public function getAttachmentsItemInstance()
    {
        return Mage::registry('lanot.attachments.attachments.item');
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return Mage::app()->getWebsite()->getId();
    }

    /**
     * @return int
     */
    public function getCustomerGroupId()
    {
        return Mage::getSingleton('customer/session')->getCustomerGroupId();
    }

    /**
     * @return string
     */
    public function getPostMaxSize()
    {
        return ini_get('post_max_size');
    }

    /**
     * @return string
     */
    public function getUploadMaxSize()
    {
        return ini_get('upload_max_filesize');
    }

    /**
     * @return mixed
     */
    public function getDataMaxSize()
    {
        return min($this->getPostMaxSize(), $this->getUploadMaxSize());
    }

    /**
     * @return string
     */
    public function getDataMaxSizeInBytes()
    {
        $iniSize = $this->getDataMaxSize();
        $size = substr($iniSize, 0, strlen($iniSize)-1);
        $parsedSize = 0;
        switch (strtolower(substr($iniSize, strlen($iniSize)-1))) {
            case 't':
                $parsedSize = $size*(1024*1024*1024*1024);
                break;
            case 'g':
                $parsedSize = $size*(1024*1024*1024);
                break;
            case 'm':
                $parsedSize = $size*(1024*1024);
                break;
            case 'k':
                $parsedSize = $size*1024;
                break;
            case 'b':
            default:
                $parsedSize = $size;
                break;
        }
        return $parsedSize;
    }
}
