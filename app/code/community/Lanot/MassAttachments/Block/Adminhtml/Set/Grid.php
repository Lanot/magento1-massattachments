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
 
/**
 * Sets list admin grid
 *
 * @author Lanot
 */
class Lanot_MassAttachments_Block_Adminhtml_Set_Grid
    extends Lanot_MassAttachments_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId        = 'lanot_attachments_set_list_grid';
    protected $_entityIdField = 'set_id';
    protected $_itemParam     = 'set_id';
    protected $_formFieldName = 'set';
    protected $_isTabGrid     = false;

    /**
     * @return Lanot_MassAttachments_Block_Adminhtml_Set_Grid
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->_removeColumn('is_active');
        return $this;
    }

    /**
     * @return Lanot_MassAttachments_Block_Adminhtml_Set_Grid
     */
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        $this->getMassactionBlock()->removeItem('active_enable');
        $this->getMassactionBlock()->removeItem('active_disable');
        return $this;
    }
}
