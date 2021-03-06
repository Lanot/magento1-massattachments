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
