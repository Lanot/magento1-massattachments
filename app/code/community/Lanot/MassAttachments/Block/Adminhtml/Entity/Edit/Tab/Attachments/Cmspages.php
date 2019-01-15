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
