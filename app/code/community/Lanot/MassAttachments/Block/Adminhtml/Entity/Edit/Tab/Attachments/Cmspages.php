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

class Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Cmspages
    extends Lanot_MassAttachments_Block_Adminhtml_Entity_Edit_Tab_Attachments_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_url = 'lanotmassattachmnets/adminhtml_attachments/cmspagesgrid';

    /**
     * @return array
     */
    protected function _getUrlParams()
    {
        $params = parent::_getUrlParams();
        $params['page_id'] = $this->getEntity()->getId();
        return $params;
    }

    /**
     * @return Mage_Cms_Model_Page
     */
    protected function getEntity()
    {
        return Mage::registry('cms_page');
    }
}
